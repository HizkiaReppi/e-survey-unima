<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SurveyAnalyticsController extends Controller
{
    public function courseAverage()
    {
        $data = SurveyResponse::select('courses.name as course_name', DB::raw('ROUND(AVG(score),2) as avg_score'))
            ->join('courses', 'survey_responses.course_id', '=', 'courses.id')
            ->groupBy('course_name')
            ->orderBy('avg_score', 'desc')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function questionPerformance()
    {
        $data = SurveyResponse::select('questions.question_text', DB::raw('ROUND(AVG(score),2) as avg_score'))
            ->join('questions', 'survey_responses.question_id', '=', 'questions.id')
            ->groupBy('question_text')
            ->orderBy('avg_score', 'asc')
            ->limit(10)
            ->get();

        return response()->json(['data' => $data]);
    }

    public function topLecturers()
    {
        $data = SurveyResponse::select('lecturers.fullname as lecturer_name', DB::raw('ROUND(AVG(score),2) as avg_score'))
            ->join('lecturers', 'survey_responses.lecturer_id', '=', 'lecturers.id')
            ->groupBy('lecturer_name')
            ->orderBy('avg_score', 'desc')
            ->limit(10)
            ->get();

        return response()->json(['data' => $data]);
    }
}
