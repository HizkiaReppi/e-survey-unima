<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createOrEdit(Category $category): View
    {
        $category->load('questions');
        $questions = $category->questions;
        return view('dashboard.questions.createOrEdit', compact('category', 'questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Category $category)
    {
        $validated = $request->validate([
            'questions.*.question_text' => ['required', 'string'],
            'questions.*.scale' => ['required', 'in:1-3,1-5'],
        ]);

        DB::beginTransaction();
        try {
            $category->questions()->delete();

            foreach ($validated['questions'] as $question) {
                $category->questions()->create([
                    'question_text' => $question['question_text'],
                    'scale' => $question['scale'],
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard.category.questions.show', $category->slug)
            ->with('success', 'Pertanyaan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()
            ->with('error', 'Gagal menambahkan pertanyaan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load('questions');
        return view('dashboard.questions.show', compact('category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        //
    }
}
