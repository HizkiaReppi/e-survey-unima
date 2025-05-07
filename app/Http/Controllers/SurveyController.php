<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Period;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $period = Period::where('status', 'active')->first();
            if (!$period) {
                abort(404);
            }

            $model = Course::where('department_id', auth()->user()->student->department_id)->where('period_id', $period->id);

            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row['name'];
                })
                ->addColumn('status', function ($row) {
                    $hasSubmitted = SurveyResponse::where('user_id', auth()->user()->id)
                        ->where('course_id', $row['id'])
                        ->exists();

                    if ($hasSubmitted) {
                        return '<span class="badge bg-success">Sudah Diisi</span>';
                    }

                    return '<span class="badge bg-secondary">Belum Diisi</span>';
                })
                ->addColumn('action', function ($row) {
                    $hasSubmitted = SurveyResponse::where('user_id', auth()->user()->id)
                        ->where('course_id', $row['id'])
                        ->first();

                    if ($hasSubmitted) {
                        // return '<a class="link-underline-primary" href="' . route('dashboard.survey.create', $row['id']) . '">Lihat Jawaban</a>';
                        return '<a class="link-underline-primary" href="' . route('dashboard.survey.result.show', [$hasSubmitted->id, $row['id']]) . '">Lihat Jawaban</a>';
                    }

                    return '<a class="link-underline-primary" href="' . route('dashboard.survey.create', $row['id']) . '">Isi Kuesioner</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('dashboard.survey.index');
    }

    public function index_admin(Request $request)
    {
        $title = 'Apakah anda yakin?';
        $text = 'Anda tidak akan bisa mengembalikannya!';
        confirmDelete($title, $text);

        if ($request->ajax()) {
            $categories = Category::all();

            $surveyResults = SurveyResponse::with(['user', 'question.category', 'course', 'lecturer'])
                ->select('survey_responses.id', 'survey_responses.user_id', 'survey_responses.course_id', 'survey_responses.lecturer_id', 'survey_responses.question_id', 'survey_responses.score')
                ->get()
                ->groupBy(function ($item) {
                    return $item->user_id . '-' . $item->course_id . '-' . $item->lecturer_id;
                })
                ->map(function ($responses, $groupKey) use ($categories) {
                    $first = $responses->first();
                    $user = $first->user;
                    $course = $first->course;
                    $lecturer = $first->lecturer;

                    $categoryAverages = [];

                    foreach ($categories as $category) {
                        $categoryResponses = $responses->filter(fn($r) => $r->question->category_id == $category->id);
                        $avg = $categoryResponses->avg('score');
                        $categoryAverages[$category->id] = $avg ? number_format($avg, 2) : '-';
                    }

                    $totalAverage = $responses->avg('score');

                    return [
                        'respondent_name' => $user->name,
                        'course_name' => $course?->name ?? '-',
                        'lecturer_name' => $lecturer?->fullname ?? '-',
                        'category_averages' => $categoryAverages,
                        'total_average' => number_format($totalAverage, 2),
                        'user_id' => $user->id,
                        'course_id' => $course->id ?? null,
                        'lecturer_id' => $lecturer->id ?? null,
                        'response_id' => $first->id,
                    ];
                });

            return DataTables::of($surveyResults)
                ->addIndexColumn()
                ->addColumn('respondent_name', fn($row) => $row['respondent_name'])
                ->addColumn('course_name', fn($row) => $row['course_name'])
                ->addColumn('lecturer_name', fn($row) => $row['lecturer_name'])
                ->addColumn('category_averages', function ($row) use ($categories) {
                    return collect($categories)
                        ->map(function ($cat) use ($row) {
                            return $cat->name . ': ' . ($row['category_averages'][$cat->id] ?? '-');
                        })
                        ->implode('<br>');
                })
                ->addColumn('total_average', fn($row) => $row['total_average'])
                ->addColumn('action', function ($row) {
                    $btn =
                        '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                            data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' .
                        route('dashboard.survey.result.show', [$row['response_id'], $row['course_id'], $row['lecturer_id']]) .
                        '">
                                <i class="bx bxs-user-detail me-1"></i> Detail
                            </a>
                            <a class="dropdown-item" href="' .
                        route('dashboard.survey.result.destroy', [$row['user_id'], $row['course_id'], $row['lecturer_id']]) .
                        '"
                                data-confirm-delete="true">
                                <i class="bx bx-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>';
                    return $btn;
                })
                ->rawColumns(['category_averages', 'action'])
                ->make(true);
        }

        return view('dashboard.survey.index-admin');
    }

    public function show($id, string $courseId)
    {
        $course = Course::with('period')->where('id', $courseId)->first();
        $categories = Category::with([
            'questions' => function ($query) use ($courseId) {
                $query->with([
                    'responses' => function ($query) use ($courseId) {
                        $query->where('user_id', auth()->id())->where('course_id', $courseId);
                    },
                ]);
            },
        ])->get();
        $response = SurveyResponse::with(['lecturer'])
            ->where('id', $id)
            ->where('course_id', $courseId)
            ->first();

        return view('dashboard.survey.show', compact('categories', 'course', 'response'));
    }

    public function create(string $id)
    {
        $period = Period::where('status', 'active')->first();
        if (!$period) {
            abort(404);
        }
        $alreadySubmitted = SurveyResponse::where('user_id', auth()->id())
            ->where('course_id', $id)
            ->exists();
        if ($alreadySubmitted) {
            return redirect()->route('dashboard.survey.thankyou');
        }

        $categories = Category::with('questions')->get();
        $lecturers = Lecturer::all();
        $course = Course::where('id', $id)->first();
        return view('dashboard.survey.create', compact('categories', 'lecturers', 'course'));
    }

    public function store(Request $request, string $id)
    {
        $validated = $request->validate([
            'lecturer_id' => ['required', 'exists:lecturers,id'],
            'credits' => ['required', 'integer', 'min:1'],
            'responses' => ['required', 'array'],
            'responses.*' => ['required', 'integer', 'between:1,5'],
        ]);

        $period = Period::where('status', 'active')->first();
        if (!$period) {
            abort(404);
        }

        $course = Course::where('id', $id)->first();
        if (!$course) {
            abort(404);
        }

        // ✅ Cek hanya untuk course & lecturer tertentu
        $alreadySubmitted = SurveyResponse::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->where('lecturer_id', $validated['lecturer_id'])
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('dashboard.survey.thankyou');
        }

        // ✅ Simpan berdasarkan kombinasi user + question + course + lecturer
        foreach ($validated['responses'] as $question_id => $score) {
            SurveyResponse::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'question_id' => $question_id,
                    'course_id' => $course->id,
                    'lecturer_id' => $validated['lecturer_id'],
                ],
                [
                    'credits' => $validated['credits'],
                    'score' => $score,
                ],
            );
        }

        session()->flash('success', 'Hasil survey berhasil disimpan.');

        return redirect()->route('dashboard.survey.thankyou');
    }

    public function thankyou(string $courseId)
    {
        $course = Course::where('id', $courseId)->first();
        if (!$course) {
            abort(404);
        }
        $courseId = $course->id;
        $id = SurveyResponse::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first()->id;
        return view('dashboard.survey.thankyou', compact('id', 'courseId'));
    }

    public function delete(string $userId, string $courseId)
    {
        $surveyResult = SurveyResponse::where('course_id', $courseId)->where('user_id', $userId)->first();
        if (!$surveyResult) {
            return redirect()->route('dashboard.survey.results.index')->with('error', 'Hasil survey tidak ditemukan.');
        }

        SurveyResponse::where('user_id', $userId)->where('course_id', $courseId)->delete();

        return redirect()->route('dashboard.survey.results.index')->with('success', 'Hasil survey berhasil dihapus.');
    }
}
