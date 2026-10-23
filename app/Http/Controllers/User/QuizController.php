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
    public function show(Course $course, Lesson $lesson, ?Quiz $quiz = null)
    {
        $lesson = $course->lessons()->with('quizzes')->findOrFail($lesson->id);

        // if (Auth::check() && !Auth::user()->isEnrolled($course)) {
        //     abort(403, 'Anda belum terdaftar pada kelas ini.');
        // }

        $quizzes = $lesson->quizzes;
        if ($quizzes->isEmpty()) {
            return redirect()->route('learn', [$course->slug, $lesson->id])
                ->with('error', 'Materi ini belum memiliki quiz.');
        }

        $activeQuiz = ($quiz && $quiz->exists) ? $quiz : $quizzes->first();

        return view('pages.course.quiz', compact('course', 'lesson', 'quizzes', 'activeQuiz'));
    }

    public function submit(Request $request, Course $course, Lesson $lesson, ?Quiz $quiz = null)
    {
        $lesson = $course->lessons()->with('quizzes')->findOrFail($lesson->id);

        if (Auth::check() && !Auth::user()->isEnrolled($course)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $quizzes = $lesson->quizzes;
        $answers = $request->input('answers', []);
        $score = 0;
        $results = [];

        foreach ($quizzes as $item) {
            $userAnswer = $answers[$item->id] ?? null;
            $isCorrect = $userAnswer !== null && strtolower((string)$userAnswer) === strtolower((string)$item->correct_answer);

            if ($isCorrect) {
                $score++;
            }

            $results[$item->id] = [
                'user_answer' => $userAnswer,
                'correct_answer' => $item->correct_answer,
                'is_correct' => $isCorrect,
            ];
        }

        $totalQuestions = $quizzes->count();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'score' => $score,
                'total' => $totalQuestions,
                'results' => $results,
            ]);
        }

        return redirect()->back()->with([
            'quiz_submitted' => true,
            'score' => $score,
            'total' => $totalQuestions,
            'results' => $results,
        ]);
    }
}

