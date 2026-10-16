<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function show(Course $course, Lesson $lesson, Quiz $quiz)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quiz = $lesson->quizzes()->with('questions')->findOrFail($quiz->id);

        if (!$course->enrollments()->where('user_id', Auth::id())->exists()) {
            abort(403);
        }

        return view('siswa.quiz.show', compact('course', 'lesson', 'quiz'));
    }

    public function submit(Request $request, Course $course, Lesson $lesson, Quiz $quiz)
    {
        $lesson = $course->lessons()->findOrFail($lesson->id);
        $quiz = $lesson->quizzes()->with('questions')->findOrFail($quiz->id);

        if (!$course->enrollments()->where('user_id', Auth::id())->exists()) {
            abort(403);
        }

        $rules = [
            'answer' => ['nullable', 'array'],
        ];

        foreach ($quiz->questions as $question) {
            $rules['answer.' . $question->id] = ['nullable', 'in:a,b,c,d'];
        }

        $answer = $request->validate($rules)['answer'] ?? [];

        $score = 0;
        $results = [];

        foreach ($quiz->questions as $question) {
            $userAnswer = $answer[$question->id] ?? null;
            $isCorrect = $userAnswer === $question->correct_answer;

            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'question' => $question,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
            ];
        }

        $totalQuestions = $quiz->questions->count();

        return view('siswa.quiz.result', compact('course', 'lesson', 'quiz', 'score', 'totalQuestions', 'results'));
    }
}
