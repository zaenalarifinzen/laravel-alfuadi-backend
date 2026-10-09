<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(Course $course, Quiz $quiz)
    {
        $user = auth()->user();

        if (!$user->isEnrolled($course)) {
            abort(403);
        }

        $quiz->load('questions');

        return view('siswa.quiz.show', compact('course', 'quiz'));
    }

    public function submit(Request $request, Course $course, Quiz $quiz)
    {
        $user = auth()->user();

        if (!$user->isEnrolled($course)) {
            abort(403);
        }

        $quiz->load('questions');
        $answer = $request->input('answer', []);

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

        $attemp = QuizAttemp::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $score,
            'total_questions' => $quiz->questions->count(),
            'completed_at' => now(),
        ]);

        return view('siswa.quiz.result', compact('course', 'quiz', 'attemp', 'results'));
    }
}
