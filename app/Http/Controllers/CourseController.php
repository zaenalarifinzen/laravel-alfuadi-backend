<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
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
     * Show checkout page for the specified course.
     */
    public function checkout(Course $course)
    {
        $course->load(['mentor', 'category', 'lessons']);

        if (auth()->check() && auth()->user()->isEnrolled($course)) {
            return redirect()->route('learn', $course->slug)->with('info', 'Anda sudah terdaftar di kelas ini.');
        }

        return view('pages.course.checkout', compact('course'));
    }

    /**
     * Process course checkout (100% free bypass or bank transfer).
     */
    public function processCheckout(Request $request, Course $course)
    {
        $request->validate([
            'voucher_code' => 'nullable|string|max:50',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'total_amount' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string',
            'whatsapp' => 'nullable|string',
        ]);

        $discountPercent = (float) $request->input('discount_percent', 0);
        $totalAmount = (float) $request->input('total_amount', $course->price);

        // Jika diskon 100% atau kursus gratis, langsung daftarkan peserta
        if ($course->isFree() || $discountPercent >= 100 || $totalAmount <= 0) {
            if (auth()->check()) {
                Enrollment::firstOrCreate([
                    'user_id' => auth()->id(),
                    'course_id' => $course->id,
                ], [
                    'enrolled_at' => now(),
                ]);

                return redirect()->route('enrollments')->with('success', 'Selamat! Pendaftaran Anda di kelas ' . $course->title . ' berhasil secara gratis.');
            }

            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk mengklaim akses kelas gratis.');
        }

        // Jika berbayar, kembalikan dengan session order_success untuk membuka instruksi transfer & CS WhatsApp
        $invoiceNumber = 'INV-AF-' . date('Ymd') . '-' . rand(1000, 9999);

        return redirect()->route('courses.checkout', $course->slug)->with('checkout_success', [
            'invoice' => $invoiceNumber,
            'course_id' => $course->id,
            'course_title' => $course->title,
            'course_slug' => $course->slug,
            'total_amount' => $totalAmount,
            'bank_name' => $request->input('bank_name', 'Bank Syariah Indonesia (BSI)'),
            'bank_account' => $request->input('bank_account', '7188 2938 19'),
            'whatsapp' => $request->input('whatsapp'),
        ]);
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
