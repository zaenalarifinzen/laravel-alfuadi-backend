@extends('layouts.app')

@section('title', 'Checkout Kursus - ' . ($course->title ?? 'Al-Fuadi'))

@push('style')
<style>
    /* Styling Checkout Modern Al-Fuadi */
    :root {
        --checkout-primary: #138a84;
        --checkout-primary-dark: #0d6561;
        --checkout-primary-light: #2dd4bf;
        --checkout-primary-soft: rgba(19, 138, 132, 0.08);
        --checkout-card-bg: #ffffff;
        --checkout-card-border: #e6edf0;
        --checkout-text-main: #1e293b;
        --checkout-text-muted: #64748b;
        --checkout-badge-bg: #ecfdf5;
        --checkout-badge-text: #065f46;
    }

    html[data-theme="dark"] {
        --checkout-card-bg: #0d191e;
        --checkout-card-border: #1f4d49;
        --checkout-text-main: #f1f5f9;
        --checkout-text-muted: #94a3b8;
        --checkout-badge-bg: rgba(45, 212, 191, 0.15);
        --checkout-badge-text: #5eead4;
        --checkout-primary-soft: rgba(45, 212, 191, 0.08);
    }

    .checkout-card {
        background-color: var(--checkout-card-bg);
        border: 1px solid var(--checkout-card-border);
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.25s ease;
        margin-bottom: 24px;
        overflow: hidden;
    }

    html[data-theme="dark"] .checkout-card {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    }

    .checkout-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--checkout-card-border);
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .checkout-card-header h5 {
        margin: 0;
        font-size: 1.08rem;
        font-weight: 700;
        color: var(--checkout-text-main);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkout-card-header h5 i {
        color: var(--checkout-primary);
    }

    .checkout-card-body {
        padding: 24px;
    }

    /* Notice Banner Digital Product */
    .digital-product-notice {
        background: linear-gradient(135deg, rgba(19, 138, 132, 0.1) 0%, rgba(45, 212, 191, 0.05) 100%);
        border: 1px solid rgba(19, 138, 132, 0.25);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .digital-product-notice .icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--checkout-primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .digital-product-notice .notice-title {
        font-weight: 700;
        color: var(--checkout-text-main);
        margin-bottom: 2px;
        font-size: 0.95rem;
    }

    .digital-product-notice .notice-desc {
        color: var(--checkout-text-muted);
        font-size: 0.85rem;
        margin: 0;
    }

    /* Bank Selection Card */
    .bank-option-card {
        border: 2px solid var(--checkout-card-border);
        border-radius: 12px;
        padding: 14px 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        background: var(--checkout-card-bg);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .bank-option-card:hover {
        border-color: var(--checkout-primary);
        transform: translateY(-1px);
    }

    .bank-option-card.selected {
        border-color: var(--checkout-primary);
        background: var(--checkout-primary-soft);
    }

    .bank-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .bank-badge {
        font-weight: 800;
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #0f172a;
        letter-spacing: 0.5px;
        min-width: 65px;
        text-align: center;
    }

    html[data-theme="dark"] .bank-badge {
        background: #1e293b;
        color: #f8fafc;
    }

    .bank-badge.bsi { background: #00a39d; color: #fff; }
    .bank-badge.bca { background: #005baa; color: #fff; }
    .bank-badge.mandiri { background: #003366; color: #fff; }
    .bank-badge.bri { background: #00529c; color: #fff; }

    .bank-details .bank-name {
        font-weight: 700;
        font-size: 0.92rem;
        color: var(--checkout-text-main);
        margin-bottom: 2px;
    }

    /* Course Summary Card */
    .course-summary-box {
        display: flex;
        gap: 16px;
        align-items: flex-start;
        padding-bottom: 18px;
        border-bottom: 1px dashed var(--checkout-card-border);
        margin-bottom: 18px;
    }

    .course-summary-thumb {
        width: 88px;
        height: 64px;
        border-radius: 8px;
        object-fit: cover;
        background-color: #cbd5e1;
        flex-shrink: 0;
    }

    .course-summary-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--checkout-text-main);
        margin-bottom: 4px;
        line-height: 1.35;
    }

    .course-summary-mentor {
        font-size: 0.82rem;
        color: var(--checkout-text-muted);
        margin-bottom: 0;
    }

    /* Voucher Box */
    .voucher-section {
        background: var(--checkout-primary-soft);
        border: 1px dashed var(--checkout-primary);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .voucher-input-group {
        display: flex;
        gap: 8px;
    }

    .voucher-input-group input {
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .voucher-badge-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        padding: 4px 10px;
        border-radius: 20px;
        background: rgba(19, 138, 132, 0.15);
        color: var(--checkout-primary);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
        margin-right: 6px;
        margin-top: 6px;
    }

    .voucher-badge-chip:hover {
        background: var(--checkout-primary);
        color: #fff;
    }

    /* 100% Free / Gratis State */
    .free-course-alert {
        display: none;
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
        animation: fadeInScale 0.3s ease-out;
    }

    .free-course-alert h4 {
        color: #ffffff;
        font-weight: 800;
        margin-bottom: 6px;
        font-size: 1.25rem;
    }

    .free-course-alert p {
        margin-bottom: 0;
        font-size: 0.88rem;
        opacity: 0.95;
    }

    @keyframes fadeInScale {
        from { opacity: 0; transform: scale(0.97); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Price Breakdown */
    .price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: 0.9rem;
        color: var(--checkout-text-muted);
    }

    .price-row.total-row {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid var(--checkout-card-border);
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--checkout-text-main);
    }

    .price-row.total-row .total-amount {
        color: var(--checkout-primary);
        font-size: 1.35rem;
    }

    .price-row.total-row .total-amount.is-free {
        color: #10b981;
    }

    /* Button Action */
    .btn-checkout-submit {
        background: linear-gradient(135deg, #138a84 0%, #0d6561 100%);
        border: none;
        color: #fff;
        padding: 14px 20px;
        font-size: 1.05rem;
        font-weight: 700;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(19, 138, 132, 0.3);
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
    }

    .btn-checkout-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(19, 138, 132, 0.4);
        color: #fff;
    }

    .btn-checkout-submit.is-free-btn {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
    }

    .btn-checkout-submit.is-free-btn:hover {
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.45);
    }

    /* Trust Badges */
    .trust-badges {
        display: flex;
        justify-content: center;
        gap: 18px;
        margin-top: 16px;
        font-size: 0.78rem;
        color: var(--checkout-text-muted);
    }

    .trust-badges span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .trust-badges i {
        color: var(--checkout-primary);
    }

    /* Modal Konfirmasi & CS */
    .confirmation-modal .modal-content {
        border-radius: 18px;
        border: none;
        background: var(--checkout-card-bg);
        color: var(--checkout-text-main);
        overflow: hidden;
    }

    .confirmation-modal .modal-header {
        background: linear-gradient(135deg, #138a84, #0d6561);
        color: #fff;
        border: none;
        padding: 22px 28px;
    }

    .confirmation-modal .modal-header h4 {
        color: #fff;
        font-weight: 800;
        margin: 0;
        font-size: 1.25rem;
    }

    .cs-card-box {
        background: linear-gradient(135deg, rgba(37, 211, 102, 0.08) 0%, rgba(19, 138, 132, 0.05) 100%);
        border: 1px solid rgba(37, 211, 102, 0.3);
        border-radius: 14px;
        padding: 18px;
        margin-top: 20px;
    }

    .cs-wa-btn {
        background: #25D366;
        color: #fff;
        font-weight: 700;
        padding: 12px 18px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 1rem;
        text-decoration: none;
        box-shadow: 0 6px 18px rgba(37, 211, 102, 0.35);
        transition: all 0.25s ease;
    }

    .cs-wa-btn:hover {
        background: #1eb956;
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.45);
    }

    .copy-btn {
        background: transparent;
        border: 1px solid var(--checkout-card-border);
        color: var(--checkout-text-muted);
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.78rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .copy-btn:hover {
        background: var(--checkout-primary-soft);
        color: var(--checkout-primary);
        border-color: var(--checkout-primary);
    }
</style>
@endpush

@section('main')
@php
    // Safe fallbacks if course is passed or not
    $courseTitle = $course->title ?? 'Program Pembelajaran Nahwu & Bahasa Arab';
    $coursePrice = isset($course->price) ? (float)$course->price : 250000;
    $courseThumbnail = $course->thumbnail ?? null;
    $courseMentor = $course->mentor->name ?? 'Ustadz Pembimbing Al-Fuadi';
    $courseCategory = $course->category->name ?? 'Kategori Nahwu';
    $courseSlug = $course->slug ?? 'kursus';
    $uniqueCode = rand(100, 399);
    $initialTotal = $coursePrice > 0 ? ($coursePrice + $uniqueCode) : 0;
    
    // CS Setting data
    $csPhone = '6281234567890';
    $csPhoneDisplay = '0812-3456-7890';
    $csName = 'CS Al-Fuadi';
@endphp

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Checkout Kursus</h1>
        </div>

        <div class="section-body">
            @include('layouts.alert')

            <!-- Digital Product Notice -->
            <div class="digital-product-notice">
                <div class="icon-circle">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <div class="notice-title">Produk Digital (Akses Kursus Online)</div>
                    <div class="notice-desc">
                        Kursus ini berupa materi online & video interaktif. Akses pembelajaran akan langsung aktif di akun Anda setelah pendaftaran terkonfirmasi.
                    </div>
                </div>
            </div>

            <!-- Form Checkout -->
            <form id="checkoutForm" action="{{ isset($course) ? route('courses.checkout.process', $course->slug) : '#' }}" method="POST">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id ?? 1 }}">
                <input type="hidden" name="base_price" id="inputBasePrice" value="{{ $coursePrice }}">
                <input type="hidden" name="unique_code" id="inputUniqueCode" value="{{ $coursePrice > 0 ? $uniqueCode : 0 }}">
                <input type="hidden" name="discount_amount" id="inputDiscountAmount" value="0">
                <input type="hidden" name="discount_percent" id="inputDiscountPercent" value="0">
                <input type="hidden" name="total_amount" id="inputTotalAmount" value="{{ $initialTotal }}">
                <input type="hidden" name="voucher_code" id="inputVoucherCode" value="">
                <input type="hidden" name="bank_name" id="inputBankName" value="BSI">
                <input type="hidden" name="bank_account" id="inputBankAccount" value="7188293819">

                <div class="row">
                    <!-- LEFT COLUMN: Data Pemesan & Metode Pembayaran -->
                    <div class="col-12 col-lg-7">
                        <!-- Data Pemesan -->
                        <div class="checkout-card">
                            <div class="checkout-card-header">
                                <h5><i class="fas fa-user-circle"></i> Informasi Peserta</h5>
                            </div>
                            <div class="checkout-card-body">
                                @guest
                                    <div class="alert alert-light border mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span>Sudah punya akun Al-Fuadi?</span>
                                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary font-weight-bold">
                                                Login Sekarang
                                            </a>
                                        </div>
                                    </div>
                                @endguest

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-600">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="buyer_name" 
                                            value="{{ auth()->user()->name ?? old('buyer_name', '') }}" 
                                            placeholder="Nama Lengkap Anda" required {{ auth()->check() ? 'readonly' : '' }}>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="font-weight-600">Alamat Email</label>
                                        <input type="email" class="form-control" name="buyer_email" 
                                            value="{{ auth()->user()->email ?? old('buyer_email', '') }}" 
                                            placeholder="email@anda.com" required {{ auth()->check() ? 'readonly' : '' }}>
                                    </div>
                                    <div class="col-12 form-group mb-0">
                                        <label class="font-weight-600">Nomor WhatsApp / HP Aktif</label>
                                        <input type="tel" class="form-control" name="whatsapp" id="inputWhatsapp" 
                                            value="{{ auth()->user()->phone ?? old('whatsapp', '') }}" 
                                            placeholder="Contoh: 081234567890" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Banner Saat Diskon 100% / Gratis -->
                        <div id="freeCourseAlert" class="free-course-alert">
                            <div class="mb-2">
                                <i class="fas fa-gift fa-3x"></i>
                            </div>
                            <h4>VOUCHER DISKON 100% AKTIF!</h4>
                            <p>
                                Selamat! Anda mendapatkan akses penuh ke kelas ini secara <strong>100% GRATIS</strong>. Anda tidak perlu memilih metode pembayaran atau mentransfer uang.
                            </p>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="checkout-card" id="paymentMethodCard">
                            <div class="checkout-card-header">
                                <h5><i class="fas fa-university"></i> Metode Pembayaran</h5>
                            </div>
                            <div class="checkout-card-body">

                                <div class="bank-option-card selected" onclick="selectBank(this, 'BSI', '7188 2938 19', 'Yayasan Al-Fuadi Indonesia')">
                                    <div class="bank-info">
                                        <span class="bank-badge bsi">BSI</span>
                                        <div class="bank-details">
                                            <div class="bank-name">Transfer ke Bank BSI</div>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="bank_radio" class="custom-control-input" checked>
                                        <label class="custom-control-label"></label>
                                    </div>
                                </div>

                                <div class="bank-option-card" onclick="selectBank(this, 'Bank Central Asia (BCA)', '829 019 2841', 'Al-Fuadi Learning Center')">
                                    <div class="bank-info">
                                        <span class="bank-badge bca">BCA</span>
                                        <div class="bank-details">
                                            <div class="bank-name">Transfer ke Bank BCA</div>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="bank_radio" class="custom-control-input">
                                        <label class="custom-control-label"></label>
                                    </div>
                                </div>
                                
                                <div class="bank-option-card" onclick="selectBank(this, 'Seabank (BKE)', '829 019 2841', 'Al-Fuadi Learning Center')">
                                    <div class="bank-info">
                                        <span class="bank-badge bca">SEA</span>
                                        <div class="bank-details">
                                            <div class="bank-name">Transfer ke Bank Seabank</div>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="bank_radio" class="custom-control-input">
                                        <label class="custom-control-label"></label>
                                    </div>
                                </div>

                                <div class="bank-option-card" onclick="selectBank(this, 'Dana', '0829 019 2841', 'Al-Fuadi Learning Center')">
                                    <div class="bank-info">
                                        <span class="bank-badge bca">DANA</span>
                                        <div class="bank-details">
                                            <div class="bank-name">Transfer ke E-Wallet Dana</div>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="bank_radio" class="custom-control-input">
                                        <label class="custom-control-label"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Ringkasan Kursus, Voucher & Rincian Pembayaran -->
                    <div class="col-12 col-lg-5">
                        <div class="checkout-card">
                            <div class="checkout-card-header">
                                <h5><i class="fas fa-receipt"></i> Ringkasan Pesanan</h5>
                            </div>
                            <div class="checkout-card-body">
                                <!-- Ringkasan Item Kursus -->
                                <div class="course-summary-box">
                                    @if($courseThumbnail)
                                        <img src="{{ asset('storage/' . $courseThumbnail) }}" alt="{{ $courseTitle }}" class="course-summary-thumb">
                                    @else
                                        <div class="course-summary-thumb d-flex align-items-center justify-content-center text-secondary">
                                            <i class="fas fa-book-open fa-2x"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="course-summary-title">{{ $courseTitle }}</div>
                                        <p class="course-summary-mentor">
                                            <i class="fas fa-user-tie mr-1 text-muted"></i> {{ $courseMentor }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Redeem Voucher -->
                                <div class="voucher-section">
                                    <label class="font-weight-bold text-dark mb-1" style="font-size: 0.88rem;">
                                        Punya Kode Voucher?
                                    </label>
                                    <div class="voucher-input-group">
                                        <input type="text" class="form-control" id="voucherCodeInput" placeholder="Masukkan kode promo">
                                        <button type="button" class="btn btn-primary" id="btnApplyVoucher" onclick="applyVoucher()">
                                            Terapkan
                                        </button>
                                    </div>

                                    <div id="voucherMessage" class="mt-2 small" style="display: none;"></div>
                                </div>

                                <!-- Resume -->
                                <div class="price-breakdown">
                                    <div class="price-row">
                                        <span>Harga Kursus</span>
                                        <span id="displayBasePrice">Rp {{ number_format($coursePrice, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="price-row" id="rowDiscount" style="display: none; color: #10b981;">
                                        <span>Potongan Voucher <span id="displayDiscountPercentTag" class="badge badge-success ml-1">0%</span></span>
                                        <span id="displayDiscountAmount">-Rp 0</span>
                                    </div>

                                    <div class="price-row" id="rowUniqueCode">
                                        <span>Kode Unik Pembayaran</span>
                                        <span id="displayUniqueCode">+Rp {{ number_format($uniqueCode, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="price-row total-row">
                                        <span>Total Bayar</span>
                                        <span class="total-amount" id="displayTotalAmount">
                                            @if($coursePrice == 0)
                                                <span class="text-success font-weight-bold">GRATIS</span>
                                            @else
                                                Rp {{ number_format($initialTotal, 0, ',', '.') }}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="button" id="btnSubmitCheckout" class="btn-checkout-submit" onclick="handleSubmitCheckout()">
                                        <i class="fas fa-lock" id="btnSubmitIcon"></i>
                                        <span id="btnSubmitText">
                                            {{ $coursePrice == 0 ? 'Daftar Sekarang' : 'Bayar & Konfirmasi Sekarang' }}
                                        </span>
                                    </button>
                                </div>

                                <!-- Trust & Security Badges -->
                                {{-- <div class="trust-badges">
                                    <span><i class="fas fa-shield-alt"></i> Transaksi Aman</span>
                                    <span><i class="fas fa-bolt"></i> Verifikasi Cepat</span>
                                    <span><i class="fas fa-headset"></i> CS Bantuan</span>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- MODAL INSTRUKSI PEMBAYARAN & KONFIRMASI CS (DITAMPILKAN SETELAH CHECKOUT) -->
<div class="modal fade confirmation-modal" id="orderConfirmationModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4><i class="fas fa-clock mr-2"></i> Menunggu Konfirmasi Transfer</h4>
                    <p class="mb-0 text-white-50 small">Pesanan berhasil dibuat. Silakan selesaikan pembayaran & konfirmasi ke CS Al-Fuadi.</p>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <!-- Info Nomor Invoice & Nominal -->
                <div class="row align-items-center mb-4 pb-3 border-bottom">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <span class="text-muted small">Nomor Invoice Pesanan:</span>
                        <div class="font-weight-bold text-dark font-size-h5 d-flex align-items-center gap-2">
                            <span id="modalInvoiceNumber">INV-AF-{{ date('Ymd') }}-{{ rand(1000, 9999) }}</span>
                            <button type="button" class="copy-btn" onclick="copyText('modalInvoiceNumber')">
                                <i class="far fa-copy mr-1"></i> Salin
                            </button>
                        </div>
                        <div class="small text-muted mt-1">Kelas: <strong>{{ $courseTitle }}</strong></div>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <span class="text-muted small">Total yang Harus Ditransfer:</span>
                        <div class="d-flex align-items-center justify-content-md-end gap-2">
                            <span class="h4 font-weight-bold text-primary mb-0" id="modalTotalTransfer">Rp {{ number_format($initialTotal, 0, ',', '.') }}</span>
                            <button type="button" class="copy-btn" onclick="copyText('modalRawTransferAmount')">
                                <i class="far fa-copy mr-1"></i> Salin
                            </button>
                            <span id="modalRawTransferAmount" style="display: none;">{{ $initialTotal }}</span>
                        </div>
                        <small class="text-danger font-weight-600">
                            *Transfer tepat sesuai 3 digit kode unik agar mudah diverifikasi.
                        </small>
                    </div>
                </div>

                <!-- Rekening Tujuan Transfer -->
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <span class="badge badge-primary mb-2" id="modalBankNameBadge">Bank Syariah Indonesia (BSI)</span>
                                <div class="h5 font-weight-bold text-dark mb-1">
                                    <span id="modalAccountNumber">7188 2938 19</span>
                                    <button type="button" class="copy-btn ml-2" onclick="copyText('modalAccountNumber')">
                                        <i class="far fa-copy mr-1"></i> Salin No. Rekening
                                    </button>
                                </div>
                                <div class="text-muted small">Atas Nama: <strong id="modalAccountHolder">Yayasan Al-Fuadi Indonesia</strong></div>
                            </div>
                            <div class="text-right mt-2 mt-md-0">
                                <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Rekening Resmi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kontak Customer Service (CS) untuk Konfirmasi Manual -->
                <div class="cs-card-box">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: #25D366; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem;">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold text-dark">{{ $csName }}</h6>
                                <span class="text-muted small">
                                    <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i> Online • Jam Kerja (08:00 - 21:00 WIB)
                                </span>
                            </div>
                        </div>
                        <div class="mt-2 mt-sm-0">
                            <span class="badge badge-outline-success font-weight-bold px-3 py-2 border border-success text-success">
                                <i class="fab fa-whatsapp mr-1"></i> {{ $csPhoneDisplay }}
                            </span>
                        </div>
                    </div>

                    <p class="text-muted small mb-3">
                        Kirimkan bukti transfer beserta nomor invoice Anda ke CS resmi kami untuk aktivasi kelas secara manual. Tim kami akan segera mengaktifkan akses kelas Anda dalam 5-15 menit.
                    </p>

                    <!-- Tombol Konfirmasi Langsung ke WhatsApp CS -->
                    <a href="#" id="btnModalWhatsappCS" target="_blank" class="cs-wa-btn">
                        <i class="fab fa-whatsapp fa-lg"></i>
                        <span>Konfirmasi Pembayaran ke WhatsApp CS Sekarang</span>
                    </a>
                </div>

                <!-- Langkah Selanjutnya -->
                <div class="mt-4">
                    <h6 class="font-weight-bold small text-uppercase text-muted">Langkah Konfirmasi:</h6>
                    <ol class="small text-muted pl-3 mb-0">
                        <li>Lakukan transfer ke rekening bank di atas sebesar <strong id="modalStepAmount">Rp {{ number_format($initialTotal, 0, ',', '.') }}</strong>.</li>
                        <li>Simpan bukti transfer (struk ATM / screenshot m-Banking).</li>
                        <li>Klik tombol WhatsApp di atas untuk mengirim bukti transfer ke CS.</li>
                        <li>Kelas akan langsung terbuka di menu <strong>Kelas Saya</strong> setelah diverifikasi.</li>
                    </ol>
                </div>
            </div>
            <div class="modal-footer bg-light justify-content-between">
                <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Katalog Kelas
                </a>
                <a href="{{ route('enrollments') }}" class="btn btn-primary">
                    <i class="fas fa-book-reader mr-1"></i> Cek Halaman Kelas Saya
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification Container untuk Feedback Salin -->
<div id="copyToast" style="display: none; position: fixed; bottom: 30px; right: 30px; z-index: 9999; background: #0f172a; color: #fff; padding: 12px 20px; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.3); font-size: 0.9rem; align-items: center; gap: 8px;">
    <i class="fas fa-check-circle text-success"></i>
    <span id="copyToastText">Berhasil disalin ke clipboard!</span>
</div>
@endsection

@push('scripts')
<script>
    // State Data Checkout
    const courseData = {
        title: @json($courseTitle),
        slug: @json($courseSlug),
        basePrice: {{ $coursePrice }},
        uniqueCode: {{ $coursePrice > 0 ? $uniqueCode : 0 }},
        discountPercent: 0,
        discountAmount: 0,
        isFree: {{ $coursePrice == 0 ? 'true' : 'false' }},
        selectedBank: {
            name: 'Bank Syariah Indonesia (BSI)',
            account: '7188 2938 19',
            holder: 'Yayasan Al-Fuadi Indonesia'
        },
        csPhone: @json($csPhone),
        invoice: 'INV-AF-' + new Date().toISOString().slice(0,10).replace(/-/g, '') + '-' + Math.floor(1000 + Math.random() * 9000)
    };

    // Daftar Voucher Valid (Dapat diperluas atau divalidasi server-side)
    const validVouchers = {
        'ALFUADI100': { percent: 100, label: 'Diskon 100%' },
        'GRATIS100': { percent: 100, label: 'Diskon 100%' },
        'BERKAH50': { percent: 50, label: 'Diskon 50%' },
        'ALFUADI25': { percent: 25, label: 'Diskon 25%' },
        'HEMAT20': { percent: 20, label: 'Diskon 20%' }
    };

    // Format Rupiah Helper
    function formatRupiah(amount) {
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    // Pilihan Bank
    function selectBank(cardElement, bankName, accountNumber, accountHolder) {
        document.querySelectorAll('.bank-option-card').forEach(card => {
            card.classList.remove('selected');
            const radio = card.querySelector('input[type="radio"]');
            if (radio) radio.checked = false;
        });

        cardElement.classList.add('selected');
        const activeRadio = cardElement.querySelector('input[type="radio"]');
        if (activeRadio) activeRadio.checked = true;

        courseData.selectedBank = {
            name: bankName,
            account: accountNumber,
            holder: accountHolder
        };

        document.getElementById('inputBankName').value = bankName;
        document.getElementById('inputBankAccount').value = accountNumber;
    }

    // Terapkan Voucher & Hitung Diskon
    function applyVoucher() {
        const input = document.getElementById('voucherCodeInput');
        const code = input.value.trim().toUpperCase();
        const msgDiv = document.getElementById('voucherMessage');

        if (!code) {
            msgDiv.style.display = 'block';
            msgDiv.className = 'mt-2 small text-danger';
            msgDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i> Masukkan kode voucher terlebih dahulu.';
            return;
        }

        if (validVouchers[code]) {
            const voucher = validVouchers[code];
            courseData.discountPercent = voucher.percent;
            courseData.discountAmount = (courseData.basePrice * voucher.percent) / 100;

            msgDiv.style.display = 'block';
            msgDiv.className = 'mt-2 small text-success';
            msgDiv.innerHTML = `
                <div class="d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-check-circle mr-1"></i> Voucher <strong>${code}</strong> berhasil diterapkan (${voucher.label})</span>
                    <a href="javascript:void(0)" onclick="removeVoucher()" class="text-danger ml-2 font-weight-bold" style="text-decoration: underline;">Hapus</a>
                </div>
            `;

            document.getElementById('inputVoucherCode').value = code;
            recalculateTotal();
        } else {
            msgDiv.style.display = 'block';
            msgDiv.className = 'mt-2 small text-danger';
            msgDiv.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Kode voucher tidak valid.';
        }
    }

    function removeVoucher() {
        document.getElementById('voucherCodeInput').value = '';
        document.getElementById('inputVoucherCode').value = '';
        courseData.discountPercent = 0;
        courseData.discountAmount = 0;

        const msgDiv = document.getElementById('voucherMessage');
        msgDiv.style.display = 'none';
        recalculateTotal();
    }

    function recalculateTotal() {
        let total = 0;
        const isFreeVoucher = courseData.discountPercent >= 100;
        const isCourseFree = courseData.basePrice === 0;

        if (isFreeVoucher || isCourseFree) {
            // Jika diskon 100% atau kursus gratis
            total = 0;
            courseData.isFree = true;

            // Sembunyikan bagian rekening bank & kode unik
            document.getElementById('paymentMethodCard').style.display = 'none';
            document.getElementById('freeCourseAlert').style.display = 'block';
            document.getElementById('rowUniqueCode').style.display = 'none';

            // Update Tampilan Harga & Diskon
            document.getElementById('rowDiscount').style.display = 'flex';
            document.getElementById('displayDiscountPercentTag').textContent = '100%';
            document.getElementById('displayDiscountAmount').textContent = '-' + formatRupiah(courseData.basePrice);

            document.getElementById('displayTotalAmount').innerHTML = '<span class="text-success font-weight-bold">Rp 0</span>';

            // Ubah Teks & Style Tombol Submit
            const btnSubmit = document.getElementById('btnSubmitCheckout');
            btnSubmit.className = 'btn-checkout-submit is-free-btn';
            document.getElementById('btnSubmitIcon').className = 'fas fa-bolt';
            document.getElementById('btnSubmitText').textContent = 'Daftar Sekarang (100% Gratis)';

            // Update Hidden Inputs
            document.getElementById('inputUniqueCode').value = 0;
            document.getElementById('inputDiscountPercent').value = 100;
            document.getElementById('inputDiscountAmount').value = courseData.basePrice;
            document.getElementById('inputTotalAmount').value = 0;
        } else {
            // Berbayar
            courseData.isFree = false;
            const discountedPrice = Math.max(0, courseData.basePrice - courseData.discountAmount);
            total = discountedPrice + courseData.uniqueCode;

            // Tampilkan kembali bagian rekening bank
            document.getElementById('paymentMethodCard').style.display = 'block';
            document.getElementById('freeCourseAlert').style.display = 'none';
            document.getElementById('rowUniqueCode').style.display = 'flex';

            if (courseData.discountPercent > 0) {
                document.getElementById('rowDiscount').style.display = 'flex';
                document.getElementById('displayDiscountPercentTag').textContent = courseData.discountPercent + '%';
                document.getElementById('displayDiscountAmount').textContent = '-' + formatRupiah(courseData.discountAmount);
            } else {
                document.getElementById('rowDiscount').style.display = 'none';
            }

            document.getElementById('displayTotalAmount').textContent = formatRupiah(total);

            // Tombol Normal
            const btnSubmit = document.getElementById('btnSubmitCheckout');
            btnSubmit.className = 'btn-checkout-submit';
            document.getElementById('btnSubmitIcon').className = 'fas fa-lock';
            document.getElementById('btnSubmitText').textContent = 'Bayar & Konfirmasi Sekarang';

            // Update Hidden Inputs
            document.getElementById('inputUniqueCode').value = courseData.uniqueCode;
            document.getElementById('inputDiscountPercent').value = courseData.discountPercent;
            document.getElementById('inputDiscountAmount').value = courseData.discountAmount;
            document.getElementById('inputTotalAmount').value = total;
        }

        return total;
    }

    // Submit Checkout Handler
    function handleSubmitCheckout() {
        const form = document.getElementById('checkoutForm');
        
        // Validasi input WhatsApp jika belum login atau belum terisi
        const waInput = document.getElementById('inputWhatsapp');
        if (waInput && !waInput.value.trim()) {
            alert('Silakan masukkan nomor WhatsApp Anda untuk konfirmasi aktivasi kelas.');
            waInput.focus();
            return;
        }

        recalculateTotal();

        // Tampilkan status loading di tombol
        const btnSubmit = document.getElementById('btnSubmitCheckout');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Memproses pesanan...';

        // Submit form ke server (tersimpan ke database transactions)
        form.submit();
    }

    // Buka Modal Instruksi Pembayaran & Kontak CS
    function openConfirmationModal(totalAmount, customInvoice = null, customBankName = null, customBankAccount = null) {
        const buyerName = document.querySelector('input[name="buyer_name"]')?.value || 'Peserta';
        const buyerEmail = document.querySelector('input[name="buyer_email"]')?.value || '-';
        const buyerWa = document.getElementById('inputWhatsapp')?.value || '-';

        const invoiceCode = customInvoice || courseData.invoice;
        const bankName = customBankName || courseData.selectedBank.name;
        const bankAccount = customBankAccount || courseData.selectedBank.account;
        const bankHolder = courseData.selectedBank.holder;

        // Set Isi Modal
        document.getElementById('modalInvoiceNumber').textContent = invoiceCode;
        document.getElementById('modalTotalTransfer').textContent = formatRupiah(totalAmount);
        document.getElementById('modalRawTransferAmount').textContent = totalAmount;
        document.getElementById('modalStepAmount').textContent = formatRupiah(totalAmount);

        document.getElementById('modalBankNameBadge').textContent = bankName;
        document.getElementById('modalAccountNumber').textContent = bankAccount;
        document.getElementById('modalAccountHolder').textContent = bankHolder;

        // Generate WhatsApp URL ke nomor CS dengan pesan template otomatis
        const waMessage = 
`Halo Admin Al-Fuadi, saya ingin konfirmasi pembayaran untuk pendaftaran kursus:
- *No. Invoice:* ${invoiceCode}
- *Kursus:* ${courseData.title}
- *Nama Peserta:* ${buyerName}
- *Email:* ${buyerEmail}
- *No. WhatsApp:* ${buyerWa}
- *Total Transfer:* ${formatRupiah(totalAmount)}
- *Bank Tujuan:* ${bankName} (${bankAccount})

Berikut saya lampirkan bukti transfernya. Mohon bantuannya untuk verifikasi dan aktivasi kelas saya. Terima kasih!`;

        const waUrl = `https://wa.me/${courseData.csPhone}?text=${encodeURIComponent(waMessage)}`;
        document.getElementById('btnModalWhatsappCS').href = waUrl;

        $('#orderConfirmationModal').modal('show');
    }

    // Salin Teks ke Clipboard Helper
    function copyText(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const text = element.innerText || element.textContent;
        navigator.clipboard.writeText(text.trim()).then(() => {
            showToast('Berhasil disalin: ' + text.trim());
        }).catch(err => {
            // Fallback
            const textarea = document.createElement('textarea');
            textarea.value = text.trim();
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            showToast('Berhasil disalin: ' + text.trim());
        });
    }

    // Toast Feedback
    function showToast(message) {
        const toast = document.getElementById('copyToast');
        const text = document.getElementById('copyToastText');
        text.textContent = message;
        toast.style.display = 'flex';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 2500);
    }

    // Cek jika halaman dimuat dengan session checkout_success dari server
    document.addEventListener('DOMContentLoaded', () => {
        recalculateTotal();

        @if(session('checkout_success'))
            const successData = @json(session('checkout_success'));
            if (successData) {
                openConfirmationModal(
                    successData.total_amount,
                    successData.invoice,
                    successData.bank_name,
                    successData.bank_account
                );
            }
        @endif
    });
</script>
@endpush
