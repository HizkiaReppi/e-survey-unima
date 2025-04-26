<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): View
    {
        if (!Gate::allows('admin') && !Gate::allows('super-admin')) {
            abort(403);
        }

        return view('dashboard.index');
    }

    public function surveyData(): JsonResponse
    {
        $respondents = DB::table('survey_responses')
            ->select('user_id')
            ->distinct()
            ->count();

        $categories = Category::with('questions')->get();

        $categoryData = [];

        foreach ($categories as $category) {
            $questionIds = $category->questions->pluck('id')->toArray();

            $average = DB::table('survey_responses')
                ->whereIn('question_id', $questionIds)
                ->avg('score');

            $categoryData[] = [
                'category' => $category->name,
                'average' => round($average, 2) ?? 0,
            ];
        }

        return response()->json([
            'respondents' => $respondents,
            'categories' => $categoryData,
        ]);
    }
}
