<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courses = Course::where('status', 'published')
            ->with('enrollments', 'lessons')
            ->latest()
            ->paginate(10);

        $type_menu = 'courses';

        return view('pages.dashboard.course.index', compact('courses', 'type_menu'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $type_menu = 'courses';
        return view('pages.dashboard.course.create', compact('categories', 'type_menu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exist:categories,id',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $validated['mentor_id'] = auth()->id();

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        Course::create($validated);

        return redirect()->route('mentor.courses.index')
            ->with('success', 'Kursus berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(['category', 'lessons', 'reviews.user']);
        $course->loadCount(['enrollments', 'lessons', 'reviews', 'quizzes']);
        $course->loadAvg('reviews', 'rating');

        $type_menu = 'courses';
        return view('pages.dashboard.course.show', compact('course', 'type_menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $categories = Category::all();
        $type_menu = 'courses';
        return view('pages.dashboard.course.edit', compact('categories', 'course', 'type_menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exist:categories,id',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        $course->update($validated);

        return redirect()->route('mentor.courses.index')
            ->with('success', 'Kursus berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return back()->with('success', 'Kursus berhasil dihapus');
    }

    /**
     * Show a list student of a course.
     */
    public function students(Course $course)
    {
        $enrollments = $course->enrollments()->with('user')->latest()->paginate(20);
        return view('mentor.courses.students', compact('course', 'enrollments'));
    }

    /**
     * Show a review list of a course.
     */
    public function reviews(Course $course)
    {
        $reviews = $course->reviews()->with('user')->latest()->paginate(20);
        return view('mentor.courses.reviews', compact('course', 'reviews'));
    }
}
