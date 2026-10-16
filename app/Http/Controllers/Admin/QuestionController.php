<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course, Lesson $lesson, Quiz $quiz)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quiz = $lesson->quizzes()->findOrFail($quiz->id);
        $questions = $quiz->questions()->get();

        return view('mentor.questions.index', compact('course', 'lesson', 'quiz', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course, Lesson $lesson, Quiz $quiz)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quiz = $lesson->quizzes()->findOrFail($quiz->id);

        return view('mentor.questions.create', compact('course', 'lesson', 'quiz'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course, Lesson $lesson, Quiz $quiz)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quiz = $lesson->quizzes()->findOrFail($quiz->id);
        $validated = $request->validate([
            'text' => 'required|string',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);

        $quiz->questions()->create($validated);

        return redirect()->route('dashboard.questions.index', [$course, $lesson, $quiz])
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
    public function edit(Course $course, Lesson $lesson, Quiz $quiz, Question $question)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quiz = $lesson->quizzes()->findOrFail($quiz->id);
        $question = $quiz->questions()->findOrFail($question->id);

        return view('mentor.questions.edit', compact('course', 'lesson', 'quiz', 'question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Lesson $lesson, Quiz $quiz, Question $question)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quiz = $lesson->quizzes()->findOrFail($quiz->id);
        $question = $quiz->questions()->findOrFail($question->id);
        $validated = $request->validate([
            'text' => 'required|string',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);

        $question->update($validated);

        return redirect()->route('dashboard.questions.index', [$course, $lesson, $quiz])
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Lesson $lesson, Quiz $quiz, Question $question)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quiz = $lesson->quizzes()->findOrFail($quiz->id);
        $question = $quiz->questions()->findOrFail($question->id);
        $question->delete();
        return back()->with('success', 'Question deleted successfully.');
    }
}
