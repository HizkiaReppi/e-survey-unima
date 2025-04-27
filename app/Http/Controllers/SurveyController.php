<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Ambil semua kategori yang ada
            $categories = Category::all();

            // Ambil hasil survey berdasarkan user dan kategorinya
            $surveyResults = SurveyResponse::with('user', 'question.category')->select('survey_responses.id', 'survey_responses.user_id', 'survey_responses.score', 'questions.category_id', 'users.name as respondent_name')->join('users', 'survey_responses.user_id', '=', 'users.id')->join('questions', 'survey_responses.question_id', '=', 'questions.id')->get();

            // Kelompokkan berdasarkan user
            $groupedResults = $surveyResults->groupBy('user_id')->map(function ($responses) use ($categories) {
                $user = $responses->first()->user; // Ambil user pertama sebagai data user
                $categoryAverages = [];

                // Hitung rata-rata untuk setiap kategori yang ada
                foreach ($categories as $category) {
                    $categoryResponses = $responses->where('category_id', $category->id);
                    $averageScore = $categoryResponses->avg('score');
                    $categoryAverages[$category->id] = number_format($averageScore, 2);
                }

                // Hitung rata-rata keseluruhan
                $totalAverage = $responses->avg('score');
                return [
                    'respondent_name' => $user->name,
                    'category_averages' => $categoryAverages,
                    'total_average' => number_format($totalAverage, 2),
                    'user_id' => $user->id,
                ];
            });

            // Kirim data yang sudah dikelompokkan untuk ditampilkan dengan DataTables
            return DataTables::of($groupedResults)
                ->addIndexColumn()
                ->addColumn('respondent_name', function ($row) {
                    return $row['respondent_name'];
                })
                ->addColumn('category_averages', function ($row) use ($categories) {
                    // Tampilkan rata-rata untuk setiap kategori dinamis
                    $averages = '';
                    foreach ($categories as $category) {
                        $averages .= $row['category_averages'][$category->id] ?? '-';
                        $averages .= ' | '; // Pisahkan dengan tanda "|"
                    }
                    return rtrim($averages, ' | '); // Hapus karakter "|" terakhir
                })
                ->addColumn('total_average', function ($row) {
                    return $row['total_average'];
                })
                ->addColumn('action', function ($row) {
                    $btn =
                        '<div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' .
                        route('dashboard.survey.result.show', $row['user_id']) .
                        '">
                                    <i class="bx bxs-user-detail me-1"></i> Detail
                                </a>
                                <a class="dropdown-item" href="' .
                        route('dashboard.survey.result.destroy', $row['user_id']) .
                        '"
                                    data-confirm-delete="true">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </a>
                            </div>
                        </div>';
                    return $btn;
                })
                ->make(true);
        }

        return view('dashboard.survey.index');
    }

    public function show($id)
    {
        $categories = Category::with([
            'questions.responses' => function ($query) {
                $query->where('user_id', auth()->id());
            },
        ])->get();

        return view('dashboard.survey.show', compact('categories'));
    }

    public function create()
    {
        $alreadySubmitted = SurveyResponse::where('user_id', auth()->id())->exists();
        if ($alreadySubmitted) {
            return redirect()->route('dashboard.survey.thankyou');
        }

        $categories = Category::with('questions')->get();
        $lecturers = Lecturer::all();
        $courses = Course::where('department_id', auth()->user()->student->department_id)->get();
        return view('dashboard.survey.create', compact('categories', 'lecturers', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lecturer_id' => ['required', 'exists:lecturers,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'credits' => ['required', 'integer', 'min:1'],
            'responses' => ['required', 'array'],
            'responses.*' => ['required', 'integer', 'between:1,5'],
        ]);

        foreach ($validated['responses'] as $question_id => $score) {
            SurveyResponse::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'question_id' => $question_id,
                ],
                [
                    'lecturer_id' => $validated['lecturer_id'],
                    'course_id' => $validated['course_id'],
                    'credits' => $validated['credits'],
                    'score' => $score,
                ],
            );
        }

        return redirect()->route('dashboard.survey.thankyou');
    }

    public function thankyou()
    {
        $id = SurveyResponse::where('user_id', auth()->id())->first()->id;
        return view('dashboard.survey.thankyou', compact('id'));
    }

    public function delete($id)
    {
        $surveyResult = SurveyResponse::findOrFail($id);
        $surveyResult->delete();

        return redirect()->route('dashboard.survey.results.index')->with('success', 'Hasil survey berhasil dihapus.');
    }
}
