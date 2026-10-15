<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courses = Course::where('status', 'published')
            ->with(['mentor', 'category'])
            ->withCount('enrollments', 'reviews')
            ->withAvg('reviews', 'rating')
            ->when($request->search, fn($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->when($request->category, fn($q, $category) => $q->where('category_id', 'like', "%{$category}%"))
            ->when($request->price == 'free', fn($q) => $q->where('price', 0))
            ->when($request->price == 'paid', fn($q) => $q->where('price', '>', 0))
            ->latest()
            ->paginate(12);

        $categories = Category::all();

        return view('pages.course.index', compact('courses', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(['mentor', 'category', 'lessons', 'reviews.user']);
        $course->loadCount('enrollments', 'reviews');
        $course->loadAvg('reviews', 'rating');

        // $isEnrolled = auth()->check() ? auth()->user()->isEnrolled($course) : false;

        return view('pages.course.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
