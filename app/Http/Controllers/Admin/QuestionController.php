<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course, Quiz $quiz)
    {
        $questions = $quiz->questions();
        return view('mentor.questions.index', compact('quiz', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course, Quiz $quiz)
    {
        return view('mentor.questions.create', compact('course', 'quiz'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course, Quiz $quiz)
    {
        $validated = $request->validate([
            'text' => 'require|string',
            'option_a' => 'require|string|max:255',
            'option_b' => 'require|string|max:255',
            'option_c' => 'require|string|max:255',
            'option_d' => 'require|string|max:255',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);

        $quiz->questions()->create($validated);

        return redirect()->route('mentor.questions.index', compact('course','quiz'))
            ->with('success', 'Question created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, Quiz $quiz, Question $question)
    {
        return view('mentor.questions.edit', compact('course', 'quiz', 'question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Quiz $quiz, Question $question)
    {
        $validated = $request->validate([
            'text' => 'require|string',
            'option_a' => 'require|string|max:255',
            'option_b' => 'require|string|max:255',
            'option_c' => 'require|string|max:255',
            'option_d' => 'require|string|max:255',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);

        $question->update($validated);

        return redirect()->route('mentor.questions.index', compact('course','quiz'))
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Quiz $quiz, Question $question)
    {
        $question->delete();
        return back()->with('success', 'Question deleted successfully.');
    }
}
