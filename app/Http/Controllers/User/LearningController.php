<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function show(Course $course, ?Lesson $lesson = null)
    {
        $user = auth()->user();

        // if (!$user->isEnrolled($course)) {
        //     return redirect()->route('courses.show', $course->slug)
        //         ->with('error', 'Anda belum terdaftar di kursus ini.');
        // }

        $course->loadMissing('category');
        $lessons = $course->lessons()->with('quizzes')->get();
        $currentLesson = ($lesson && $lesson->exists) ? $lesson : $lessons->first();

        if (!$currentLesson) {
            return back()->with('error', 'Kursus ini belum memiliki materi.');
        }

        $currentLesson->loadMissing('quizzes');

        return view('pages.course.learn', compact('course', 'lessons', 'currentLesson'));
    }
}
