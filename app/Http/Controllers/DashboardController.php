<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Course;
use App\Models\Period;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin')) {
            abort(403);
        }

        $lecturers = Lecturer::orderBy('fullname')->get();
        $courses = Course::orderBy('name')->get();
        $periods = Period::orderBy('name', 'desc')->get();

        return view('dashboard.index', compact('lecturers', 'courses', 'periods'));
    }

    public function surveyChartData(Request $request)
    {
        $query = SurveyResponse::select('lecturers.fullname as lecturer_name', DB::raw('ROUND(AVG(survey_responses.score),2) as avg_score'))->join('lecturers', 'survey_responses.lecturer_id', '=', 'lecturers.id');

        // Dynamic filter
        if ($request->filled('period_id')) {
            $query->where('survey_responses.period_id', $request->period_id);
        }

        if ($request->filled('lecturer_id')) {
            $query->where('survey_responses.lecturer_id', $request->lecturer_id);
        }

        if ($request->filled('course_id')) {
            $query->where('survey_responses.course_id', $request->course_id);
        }

        $data = $query->groupBy('survey_responses.lecturer_id', 'lecturers.fullname')->orderBy('avg_score', 'desc')->get();

        return response()->json([
            'data' => $data,
        ]);
    }
}
