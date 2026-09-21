@extends('layouts.app')

@section('title', 'Metode Al-Fuadi')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">

    <style>
        .metode-page {
            color: #191d21;
        }

        html[data-theme="dark"] .metode-page {
            color: #e6f3f1;
        }

        /* Hero */
        .metode-hero {
            background: linear-gradient(135deg, #103d3a 0%, #138a84 100%);
            border-radius: 20px;
            padding: 48px 40px;
            color: #fff;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(16, 61, 58, .2);
        }

        .metode-hero::before {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, .12), transparent 70%);
            top: -80px;
            right: -60px;
        }

        .metode-hero::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 185, 66, .18), transparent 70%);
            bottom: -70px;
            left: -50px;
        }

        .metode-hero>* {
            position: relative;
            z-index: 1;
        }

        .metode-hero-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #fff;
            margin-bottom: 18px;
        }

        .metode-hero h2 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .metode-hero p.lead {
            font-size: 16px;
            max-width: 640px;
            margin: 0 auto;
            color: rgba(255, 255, 255, .9);
        }

        html[data-theme="dark"] .metode-hero {
            box-shadow: 0 20px 45px rgba(0, 0, 0, .4);
        }

        /* Section header */
        .metode-section-header {
            margin: 55px 0 30px;
        }

        .metode-section-header:first-of-type {
            margin-top: 0;
        }

        .metode-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #e9fbf8;
            color: #138a84;
            border: 1px solid #c2f2ea;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 6px 16px;
            border-radius: 50px;
            margin-bottom: 14px;
        }

        html[data-theme="dark"] .metode-badge {
            background: #123330;
            border-color: #1f4d49;
            color: #5eead4;
        }

        .metode-section-title {
            color: #103d3a;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        html[data-theme="dark"] .metode-section-title {
            color: #f2fffe;
        }

        .metode-section-lead {
            color: #667085;
            font-size: 15px;
            max-width: 600px;
            margin: 0 auto;
        }

        html[data-theme="dark"] .metode-section-lead {
            color: #9fb8b4;
        }

        /* Generic card */
        .metode-card {
            background: #fff;
            border: 1px solid #e2f1ee;
            border-radius: 16px;
            padding: 32px;
        }

        html[data-theme="dark"] .metode-card {
            background: #14302d;
            border-color: #1f4d49;
        }

        /* Intro card (definition) */
        .metode-intro-card {
            display: flex;
            gap: 24px;
            align-items: flex-start;
        }

        .metode-icon-circle {
            width: 56px;
            height: 56px;
            flex-shrink: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, #138a84, #0d6561);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .metode-intro-card .lead {
            color: #103d3a;
            font-size: 17px;
            font-weight: 600;
            line-height: 1.7;
            margin-bottom: 12px;
        }

        html[data-theme="dark"] .metode-intro-card .lead {
            color: #f2fffe;
        }

        .metode-intro-card p.text-muted {
            color: #667085 !important;
            font-size: 14.5px;
            line-height: 1.75;
        }

        html[data-theme="dark"] .metode-intro-card p.text-muted {
            color: #9fb8b4 !important;
        }

        /* Pillar cards (3 prinsip) */
        .pillar-card {
            background: #fff;
            border: 1px solid #e2f1ee;
            border-radius: 16px;
            padding: 32px 26px;
            height: 100%;
            transition: all .25s cubic-bezier(.165, .84, .44, 1);
        }

        .pillar-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 35px rgba(19, 138, 132, .12);
            border-color: #7fd8cf;
        }

        html[data-theme="dark"] .pillar-card {
            background: #14302d;
            border-color: #1f4d49;
        }

        html[data-theme="dark"] .pillar-card:hover {
            border-color: #2f6b64;
            box-shadow: 0 16px 35px rgba(0, 0, 0, .3);
        }

        .pillar-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: #e9fbf8;
            color: #138a84;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        html[data-theme="dark"] .pillar-icon {
            background: #1f4d49;
            color: #5eead4;
        }

        .pillar-card h4 {
            font-size: 17px;
            font-weight: 700;
            color: #103d3a;
            margin-bottom: 10px;
        }

        html[data-theme="dark"] .pillar-card h4 {
            color: #f2fffe;
        }

        .pillar-card p {
            color: #667085;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 0;
        }

        html[data-theme="dark"] .pillar-card p {
            color: #9fb8b4;
        }

        /* Advantage list (keunggulan) */
        .advantage-card {
            padding: 12px 32px;
        }

        .advantage-item {
            display: flex;
            gap: 18px;
            align-items: flex-start;
            padding: 22px 0;
            border-bottom: 1px dashed #e2f1ee;
        }

        .advantage-item:last-child {
            border-bottom: none;
        }

        html[data-theme="dark"] .advantage-item {
            border-bottom-color: rgba(255, 255, 255, .1);
        }

        .advantage-icon {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            border-radius: 50%;
            background: linear-gradient(135deg, #138a84, #0d6561);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .advantage-item h5 {
            font-size: 15.5px;
            font-weight: 700;
            color: #103d3a;
            margin-bottom: 6px;
        }

        html[data-theme="dark"] .advantage-item h5 {
            color: #f2fffe;
        }

        .advantage-item p {
            color: #667085;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 0;
        }

        html[data-theme="dark"] .advantage-item p {
            color: #9fb8b4;
        }

        @media (max-width: 767.98px) {
            .metode-hero {
                padding: 36px 24px;
            }

            .metode-hero h2 {
                font-size: 22px;
            }

            .metode-intro-card {
                flex-direction: column;
            }

            .metode-section-title {
                font-size: 22px;
            }

            .metode-card {
                padding: 24px;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content metode-page">
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

                <!-- HERO -->
                <div class="metode-hero mb-5">
                    <h2>Mengenal Metode Al-Fuadi</h2>
                    <p class="lead">
                        Solusi praktis, modern, dan sistematis dalam mempelajari Ilmu Nahwu & memahami tata bahasa
                        Arab dari dasar hingga mahir.
                    </p>
                </div>

                <!-- APA ITU METODE AL-FUADI -->
                <div class="text-center metode-section-header">
                    <h2 class="metode-section-title">Apa itu Metode Al-Fuadi?</h2>
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <div class="metode-card metode-intro-card">
                            <div>
                                <p class="lead">
                                    <strong>Metode Al-Fuadi</strong> adalah metode pembelajaran tata bahasa Arab (Ilmu
                                    Nahwu) yang dirancang khusus untuk mempermudah santri dan pembelajar awam dalam
                                    menguasai struktur kalimat Arab secara cepat, rasional, dan intuitif.
                                </p>
                                <p class="text-muted mb-0">
                                    Berbeda dengan metode klasik yang umumnya berfokus pada hafalan kaidah dan teori
                                    yang rumit di awal, Metode Al-Fuadi menekankan pada <strong>pemetaan logika
                                        kalimat</strong>, <strong>pengenalan pola kata</strong>, serta
                                    <strong>latihan analisa langsung (I'rab)</strong> pada ayat Al-Qur'an dan teks
                                    bahasa Arab.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PRINSIP UTAMA PEMBELAJARAN -->
                <div class="text-center metode-section-header">
                    <h2 class="metode-section-title">Prinsip Utama Pembelajaran</h2>
                    <p class="metode-section-lead">3 tatanan dasar yang membuat Metode Al-Fuadi mudah dipahami</p>
                </div>

                <div class="row mb-5">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="pillar-card">
                            <span class="pillar-icon"><i class="fas fa-quote-left"></i></span>
                            <h4>1. Syair</h4>
                            <p>
                                Setiap materi diringkas menjadi sebuah syair untuk memudahkan ketika menghafal istilah 
                                dan kaidahnya.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="pillar-card">
                            <span class="pillar-icon"><i class="fas fa-sitemap"></i></span>
                            <h4>2. Skema Visual</h4>
                            <p>
                                Kaidah nahwu disusun dalam bentuk diagram dan peta alur sederhana sehingga pembelajar
                                dapat melihat gambaran besar struktur bahasa dengan mudah.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="pillar-card">
                            <span class="pillar-icon"><i class="fas fa-vial"></i></span>
                            <h4>3. Simbol I'rob</h4>
                            <p>
                                Penambahan simbol i'rob dibawah setiap kedudukan sebuah lafadz.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- KEUNGGULAN METODE AL-FUADI -->
                <div class="text-center metode-section-header">
                    <h2 class="metode-section-title">Keunggulan Metode Al-Fuadi</h2>
                    <p class="metode-section-lead">Mengapa memilih Metode Al-Fuadi?</p>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="metode-card advantage-card">
                            <div class="advantage-item">
                                <span class="advantage-icon"><i class="fas fa-check"></i></span>
                                <div>
                                    <h5>Alur Berpikir yang Runtut</h5>
                                    <p>
                                        Membimbing pembelajar dari mengenali jenis kata paling dasar (Isim, Fi'il,
                                        Harf) sebelum masuk ke analisis kedudukan kalimat (I'rab).
                                    </p>
                                </div>
                            </div>

                            <div class="advantage-item">
                                <span class="advantage-icon"><i class="fas fa-check"></i></span>
                                <div>
                                    <h5>Tanpa Perdebatan Istilah Rumit</h5>
                                    <p>
                                        Fokus pada kaidah yang paling umum dan langsung terpakai, menghindarkan pemula
                                        dari perbedaan pendapat antar madzhab ulama nahwu.
                                    </p>
                                </div>
                            </div>

                            <div class="advantage-item">
                                <span class="advantage-icon"><i class="fas fa-check"></i></span>
                                <div>
                                    <h5>Membangun Kepercayaan Diri</h5>
                                    <p>
                                        Dengan formula sederhana, pembelajar merasa mampu dan percaya diri untuk
                                        menguraikan kalimat bahasa Arab sejak pertemuan-pertemuan awal.
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
