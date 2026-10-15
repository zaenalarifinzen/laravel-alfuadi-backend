<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        $lessons = $course->lessons()->orderBy('display_order')->get();
        $type_menu = 'courses';
        return view('pages.dashboard.course.lesson.index', compact('course', 'lessons', 'type_menu'));
    }

    public function create(Course $course)
    {
        $type_menu = 'courses';
        return view('pages.dashboard.course.lesson.create', compact('course', 'type_menu'));
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_markdown' => 'required|string',
            'video_url' => 'nullable|url',
            'display_order' => 'required|integer|min:0',
        ]);

        $data = [
            'course_id' => $course->id,
            'title' => $validated['title'],
            'content_markdown' => $validated['content_markdown'],
            'video_url' => $validated['video_url'],
            'display_order' => $validated['display_order'],
        ];

        $lesson = Lesson::create($data);

        return redirect()->route('dashboard.lessons.index', $course)
            ->with('success', $lesson->title . ' berhasil dibuat.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Course $course, Lesson $lesson)
    {
        $type_menu = 'courses';
        return view('pages.dashboard.course.lesson.edit', compact('course', 'lesson', 'type_menu'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_markdown' => 'required|string',
            'video_url' => 'nullable|url',
            'display_order' => 'required|integer|min:0',
        ]);

        $data = [
            'course_id' => $course->id,
            'title' => $validated['title'],
            'content_markdown' => $validated['content_markdown'],
            'video_url' => $validated['video_url'],
            'display_order' => $validated['display_order'],
        ];

        $lesson->update($data);

        return redirect()->route('dashboard.lessons.index', $course)
            ->with('success', $lesson->title . ' berhasil diperbarui.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $lesson->delete();

        return back()->with('success', $lesson->title . ' berhasil dihapus.');
    }
}
