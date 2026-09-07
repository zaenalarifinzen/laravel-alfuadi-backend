@extends('layouts.app')

@section('title', 'Metode Al-Fuadi - Nahwu Mudah Jilid 1')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Metode Al-Fuadi</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <!-- Hero Section (Stisla Native) -->
                <div class="hero bg-primary text-white mb-4">
                    <div class="hero-inner">
                        <h2>Mengenal Metode Al-Fuadi</h2>
                        <p class="lead">
                            Solusi praktis, modern, dan sistematis dalam mempelajari Ilmu Nahwu & memahami tata bahasa Arab dari dasar hingga mahir.
                        </p>
                    </div>
                </div>

                <!-- Apa itu Metode Al-Fuadi? -->
                <div class="row">
                    <div class="col-12">
                        <div class="p-4 bg-white rounded shadow-sm border mb-4">
                            <h3 class="text-primary font-weight-bold mb-3">
                                <i class="fas fa-book-open mr-2"></i> Apa itu Metode Al-Fuadi?
                            </h3>
                            <p class="lead text-dark mb-3">
                                <strong>Metode Al-Fuadi</strong> adalah metode pembelajaran tata bahasa Arab (Ilmu Nahwu) yang dirancang khusus untuk mempermudah santri dan pembelajar awam dalam menguasai struktur kalimat Arab secara cepat, rasional, dan intuitif.
                            </p>
                            <p class="text-muted mb-0">
                                Berbeda dengan metode klasik yang umumnya berfokus pada hafalan kaidah dan teori yang rumit di awal, Metode Al-Fuadi menekankan pada <strong>pemetaan logika kalimat</strong>, <strong>pengenalan pola kata</strong>, serta <strong>latihan analisa langsung (I'rab)</strong> pada ayat Al-Qur'an dan teks bahasa Arab.
                            </p>
                        </div>
                    </div>
                </div>

                <h2 class="section-title">Prinsip Utama Pembelajaran</h2>
                <p class="section-lead">3 Tatanan dasar yang membuat Metode Al-Fuadi mudah dipahami</p>

                <!-- 3 Pilar / Prinsip -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="p-4 bg-white rounded border h-100 shadow-sm">
                            <div class="text-primary mb-3">
                                <i class="fas fa-sitemap fa-3x"></i>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">1. Skema Visual</h4>
                            <p class="text-muted mb-0">
                                Kaidah nahwu disusun dalam bentuk diagram dan peta alur sederhana sehingga pembelajar dapat melihat gambaran besar struktur bahasa Arab dengan mudah.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="p-4 bg-white rounded border h-100 shadow-sm">
                            <div class="text-success mb-3">
                                <i class="fas fa-microchip fa-3x"></i>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">2. Pendekatan Formula</h4>
                            <p class="text-muted mb-0">
                                Mengubah aturan tata bahasa menjadi rumus-rumus praktis yang mudah diingat tanpa perlu menghafal bait-bait teori yang membingungkan.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="p-4 bg-white rounded border h-100 shadow-sm">
                            <div class="text-info mb-3">
                                <i class="fas fa-vial fa-3x"></i>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">3. Langsung Praktik</h4>
                            <p class="text-muted mb-0">
                                Teori yang dipelajari langsung diterapkan melalui 4 langkah praktis analisa kalimat (I'rab) untuk membaca teks berharakat maupun gundul.
                            </p>
                        </div>
                    </div>
                </div>

                <h2 class="section-title">Keunggulan Metode Al-Fuadi</h2>
                <p class="section-lead">Mengapa memilih Metode Al-Fuadi?</p>

                <!-- Media List Keunggulan -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="p-4 bg-white rounded border shadow-sm">
                            <div class="media mb-4">
                                <div class="mr-3 text-primary">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <div class="media-body">
                                    <h5 class="font-weight-bold text-dark mt-0">Alur Berpikir yang Runtut</h5>
                                    <p class="text-muted mb-0">
                                        Membimbing pembelajar dari mengenali jenis kata paling dasar (Isim, Fi'il, Harf) sebelum masuk ke analisis kedudukan kalimat (I'rab).
                                    </p>
                                </div>
                            </div>

                            <div class="media mb-4">
                                <div class="mr-3 text-primary">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <div class="media-body">
                                    <h5 class="font-weight-bold text-dark mt-0">Tanpa Perdebatan Istilah Rumit</h5>
                                    <p class="text-muted mb-0">
                                        Fokus pada kaidah yang paling umum dan langsung terpakai, menghindarkan pemula dari perbedaan pendapat antar madzhab ulama nahwu.
                                    </p>
                                </div>
                            </div>

                            <div class="media">
                                <div class="mr-3 text-primary">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <div class="media-body">
                                    <h5 class="font-weight-bold text-dark mt-0">Membangun Kepercayaan Diri</h5>
                                    <p class="text-muted mb-0">
                                        Dengan formula sederhana, pembelajar merasa mampu dan percaya diri untuk menguraikan kalimat bahasa Arab sejak pertemuan-pertemuan awal.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
@endpush

