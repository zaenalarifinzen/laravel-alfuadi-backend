<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        $quizzes = $course->quizzes()->withCount('question')->get();
        return view('mentor.quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        return view('mentor.quizzes.create', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'require|string|max:255',
        ]);

        $course->quizzes()->create($validated);

        return redirect()->route('mentor.quizzes.index', $course)
            ->with('success', 'Quiz created successfully.');
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
    public function edit(Course $course, Quiz $quiz)
    {
        return view('mentor.quizzes.edit', compact('course', 'quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'require|string|max:255',
        ]);

        $quiz->update($validated);

        return redirect()->route('mentor.quizzes.index')
            ->with('success', 'Quiz updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Quiz $quiz)
    {
        $quiz->delete();

        return back()->with('success', 'Quiz deleted successfully');
    }
}
