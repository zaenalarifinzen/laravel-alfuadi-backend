@extends('layouts.app')

@section('title', 'Kelas Online')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@push('style')
    <style>
        html[data-theme="dark"] .course-card {
            background: #0d191e94;
            border-color: #1f4d49;
        }

        html[data-theme="dark"] .course-card:hover {
            border-color: #2f6b64;
            box-shadow: 0 16px 35px rgba(0, 0, 0, .3);
        }

        html[data-theme="dark"] .course-card.is-popular {
            border-color: #2dd4bf;
            box-shadow: 0 16px 40px rgba(45, 212, 191, .18);
        }

        html[data-theme="dark"] .course-card h3 {
            color: #f2fffe;
        }

        html[data-theme="dark"] .course-card>p {
            color: #9fb8b4;
        }

        html[data-theme="dark"] .course-features li {
            color: #d7e6e4;
        }

        html[data-theme="dark"] .course-features li i {
            color: #5eead4;
        }

        html[data-theme="dark"] .course-card .btn-outline-primary {
            color: #5eead4;
            border-color: #5eead4;
        }

        html[data-theme="dark"] .course-card.is-popular .btn-outline-primary {
            background: linear-gradient(135deg, #138a84, #0d6561);
            border-color: #2dd4bf;
            color: #fff;
        }
    </style>
@endpush('style')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kelas Online</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Semua Kelas</h2>
                <p class="section-lead">
                    Daftar semua kelas yang tersedia
                </p>


                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">

                        </div>
                    </div>
                </div>
        </section>

        <div class="row">
            <!-- Kelas 1 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="course-card">
                    <h3>Kelas Al-Qur'an</h3>
                    <p>Dirancang untuk santri & umum yang ingin membaca Al-Qur'an dengan baik dan benar.</p>
                    <ul class="course-features">
                        <li><i class="fas fa-circle-check"></i> Pengenalan Bacaan Tajwid</li>
                        <li><i class="fas fa-circle-check"></i> Teknik Membaca yang Benar</li>
                        <li><i class="fas fa-circle-check"></i> Latihan Kuis Pilihan Ganda</li>
                    </ul>
                    <a href="{{ route('dashboard') }}"class="btn btn-outline-primary btn-block disabled">Segera Hadir</a>
                </div>
            </div>

            <!-- Kelas 2 (Popular) -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="course-card is-popular">
                    <span class="course-badge">Paling Populer</span>
                    <h3>Kelas Nahwu</h3>
                    <p>Mempelajari kaidah tata bahasa Arab untuk menganalisis struktur kalimat dan kata.</p>
                    <ul class="course-features">
                        <li><i class="fas fa-circle-check"></i> Kaidah I'rob</li>
                        <li><i class="fas fa-circle-check"></i> Latihan Analisa Kalimat</li>
                        <li><i class="fas fa-circle-check"></i> Peta Konsep Nahwu</li>
                    </ul>
                    <a href="{{ route('dashboard') }}"class="btn btn-outline-primary btn-block disabled">Segera Hadir</a>
                </div>
            </div>

            <!-- Kelas 3 -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="course-card">
                    <h3>Kelas Shorof</h3>
                    <p>Program intensif belajar ilmu Shorof untuk baca kitab gundul.</p>
                    <ul class="course-features">
                        <li><i class="fas fa-circle-check"></i> Bentuk Kata</li>
                        <li><i class="fas fa-circle-check"></i> Ujian & Bank Soal Komprehensif</li>
                        <li><i class="fas fa-circle-check"></i> Sertifikat Capaian Pembelajaran</li>
                    </ul>
                    <a href="{{ route('dashboard') }}"class="btn btn-outline-primary btn-block disabled">Segera Hadir</a>
                </div>
            </div>
        </div>

        {{-- <section id="kelas-kursus" class="py-5">
            <div class="text-center homepage-section-header">
                <span class="homepage-section-subtitle">Jenjang Pembelajaran</span>
                <h2 class="homepage-section-title">Pilihan Kelas & Program Belajar</h2>
                <p class="homepage-section-lead">
                    Pilih program belajar yang sesuai dengan tingkat pemahaman dan target capaian Anda.
                </p>
            </div>

        </section> --}}
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>


    <!-- Page Specific JS File -->
@endpush
