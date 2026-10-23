<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Transaction;
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
            'buyer_name' => 'nullable|string|max:255',
            'buyer_email' => 'nullable|email|max:255',
            'whatsapp' => 'required|string|max:30',
            'voucher_code' => 'nullable|string|max:50',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'unique_code' => 'nullable|integer|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
        ]);

        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk melanjutkan pendaftaran.');
        }

        $user = auth()->user();
        $buyerName = $request->input('buyer_name', $user->name);
        $buyerEmail = $request->input('buyer_email', $user->email);
        $buyerPhone = $request->input('whatsapp', $user->phone ?? '-');

        $discountPercent = (float) $request->input('discount_percent', 0);
        $discountAmount = (float) $request->input('discount_amount', 0);
        $uniqueCode = (int) $request->input('unique_code', 0);
        $totalAmount = (float) $request->input('total_amount', $course->price);
        $voucherCode = $request->input('voucher_code');
        $invoiceNumber = 'INV-AF-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // 1. JIKA DISKON 100% ATAU KURSUS GRATIS (Langsung Lunas & Terdaftar)
        if ($course->isFree() || $discountPercent >= 100 || $totalAmount <= 0) {
            Transaction::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'invoice_code' => $invoiceNumber,
                'base_price' => $course->price,
                'discount_amount' => $course->price,
                'discount_percent' => 100,
                'voucher_code' => $voucherCode,
                'unique_code' => 0,
                'total_amount' => 0,
                'payment_method' => 'free_voucher',
                'buyer_name' => $buyerName,
                'buyer_email' => $buyerEmail,
                'buyer_phone' => $buyerPhone,
                'status' => 'paid',
                'paid_at' => now(),
                'notes' => 'Pendaftaran gratis via voucher / kursus cuma-cuma.',
            ]);

            Enrollment::firstOrCreate([
                'user_id' => $user->id,
                'course_id' => $course->id,
            ], [
                'enrolled_at' => now(),
            ]);

            return redirect()->route('enrollments')->with('success', 'Selamat! Pendaftaran Anda di kelas ' . $course->title . ' berhasil secara gratis.');
        }

        // 2. JIKA BERBAYAR (Simpan Transaksi dengan Status PENDING untuk transfer bank)
        $bankName = $request->input('bank_name', 'Bank Syariah Indonesia (BSI)');
        $bankAccount = $request->input('bank_account', '7188 2938 19');

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'invoice_code' => $invoiceNumber,
            'base_price' => $course->price,
            'discount_amount' => $discountAmount,
            'discount_percent' => $discountPercent,
            'voucher_code' => $voucherCode,
            'unique_code' => $uniqueCode,
            'total_amount' => $totalAmount,
            'payment_method' => 'bank_transfer',
            'bank_name' => $bankName,
            'bank_account' => $bankAccount,
            'buyer_name' => $buyerName,
            'buyer_email' => $buyerEmail,
            'buyer_phone' => $buyerPhone,
            'status' => 'pending',
            'notes' => 'Menunggu transfer bank dan verifikasi CS.',
        ]);

        return redirect()->route('courses.checkout', $course->slug)->with('checkout_success', [
            'invoice' => $transaction->invoice_code,
            'transaction_id' => $transaction->id,
            'course_id' => $course->id,
            'course_title' => $course->title,
            'course_slug' => $course->slug,
            'buyer_name' => $transaction->buyer_name,
            'buyer_email' => $transaction->buyer_email,
            'buyer_phone' => $transaction->buyer_phone,
            'total_amount' => $transaction->total_amount,
            'bank_name' => $transaction->bank_name,
            'bank_account' => $transaction->bank_account,
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
