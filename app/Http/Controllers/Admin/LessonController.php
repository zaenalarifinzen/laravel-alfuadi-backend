<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        $lessons = $course->lessons()->orderBy('display_order')->get();
        return view('mentor.lessons.index', compact("lessons"));
    }

    public function create(Course $course)
    {
        return view('mentor.lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'require|string|max:255',
            'content_markdown' => 'required|string',
            'video_url' => 'nullable|url',
            'display_order' => 'required|integer|min:0',
        ]);

        $data = [
            'course_id' => $course->id,
            'title' => $validated['title'],
            'video_url' => $validated['video_url'],
            'display_order' => $validated['display_order'],
        ];

        $lesson = Lesson::create($data);

        return redirect()->route('mentor.lessons.index', $course)
            ->with('success', $lesson . ' succesfully created');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Course $course, Lesson $lesson)
    {
        return view('mentor.lessons.edit', compact($course, $lesson));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'require|string|max:255',
            'content_markdown' => 'required|string',
            'video_url' => 'nullable|url',
            'display_order' => 'required|integer|min:0',
        ]);

        $data = [
            'course_id' => $course->id,
            'title' => $validated['title'],
            'video_url' => $validated['video_url'],
            'display_order' => $validated['display_order'],
        ];

        $lesson->update($data);

        return redirect()->route('mentor.lessons.index', $course)
            ->with('success', $lesson->title . ' succesfully updated');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $lesson->delete();

        return back()->with('success', $lesson->title . ' successfully deleted');
    }
}
