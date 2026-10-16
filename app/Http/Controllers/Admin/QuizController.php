<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course, Lesson $lesson)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quizzes = $lesson->quizzes()->withCount('questions')->get();

        return view('mentor.quizzes.index', compact('course', 'lesson', 'quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course, Lesson $lesson)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);

        return view('mentor.quizzes.create', compact('course', 'lesson'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course, Lesson $lesson)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $lesson->quizzes()->create($validated);

        return redirect()->route('dashboard.quizzes.index', [$course, $lesson])
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
    public function edit(Course $course, Lesson $lesson, Quiz $quiz)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quiz = $lesson->quizzes()->findOrFail($quiz->id);

        return view('mentor.quizzes.edit', compact('course', 'lesson', 'quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Lesson $lesson, Quiz $quiz)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $quiz = $lesson->quizzes()->findOrFail($quiz->id);
        $quiz->update($validated);

        return redirect()->route('dashboard.quizzes.index', [$course, $lesson])
            ->with('success', 'Quiz updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Lesson $lesson, Quiz $quiz)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quiz = $lesson->quizzes()->findOrFail($quiz->id);
        $quiz->delete();

        return back()->with('success', 'Quiz deleted successfully');
    }
}
