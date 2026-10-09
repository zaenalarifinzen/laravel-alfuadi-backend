<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        $user = auth()->user();

        if (!$user->isEnrolled($course)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'Anda belum terdaftar di kursus ini.');
        }

        $lessons = $course->lessons()->orderBy('display_order')->get();
        $currentLesson = $lesson ?? $lessons->first();

        if (!$currentLesson) {
            return back()->with('error', 'Kursus ini belum memiliki materi.');
        }

        return view('user.learning', compact('course', 'lessons', 'currentLesson'));
    }
}
