@extends('layouts.app')

@section('title', 'Beranda')

@push('style')
    <style>
        .homepage {
            color: #191d21;
        }

        .homepage-hero {
            padding: 56px 0 40px;
        }

        .homepage-title {
            color: #103d3a;
            font-size: 44px;
            font-weight: 800;
            line-height: 1.15;
            margin: 18px auto 16px;
            max-width: 860px;
        }

        .homepage-title span {
            color: #138a84;
        }

        .homepage-lead {
            color: #667085;
            font-size: 18px;
            line-height: 1.8;
            margin: 0 auto 28px;
            max-width: 720px;
        }

        .homepage-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-bottom: 36px;
        }

        .homepage-visual {
            background:
                linear-gradient(135deg, rgba(19, 138, 132, .92), rgba(29, 148, 142, .7)),
                radial-gradient(circle at top left, rgba(255, 255, 255, .32), transparent 36%),
                #138a84;
            border-radius: 8px;
            box-shadow: 0 16px 40px rgba(19, 138, 132, .16);
            margin: 0 auto;
            max-width: 980px;
            min-height: 320px;
            overflow: hidden;
            padding: 42px;
            position: relative;
            text-align: left;
        }

        .homepage-visual:after {
            background-image:
                linear-gradient(rgba(255, 255, 255, .12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .12) 1px, transparent 1px);
            background-size: 28px 28px;
            content: "";
            inset: 0;
            opacity: .45;
            position: absolute;
        }

        .homepage-verse-card {
            background: rgba(255, 255, 255, .94);
            border-radius: 8px;
            box-shadow: 0 12px 34px rgba(16, 61, 58, .18);
            max-width: 560px;
            padding: 30px;
            position: relative;
            z-index: 1;
        }

        .homepage-arabic {
            color: #103d3a;
            font-family: "LPMQ Isepmisbah", "Scheherazade New", "Amiri Quran", serif;
            font-size: 38px;
            line-height: 2;
            text-align: right;
        }

        .homepage-section-title {
            color: #103d3a;
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .homepage-section-lead {
            color: #667085;
            margin: 0 auto;
            max-width: 680px;
        }

        .homepage-feature {
            border: 1px solid #e9f4f2;
            border-radius: 8px;
            box-shadow: 0 8px 22px rgba(15, 23, 42, .04);
            height: 100%;
            padding: 26px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .homepage-feature:hover {
            box-shadow: 0 14px 30px rgba(19, 138, 132, .1);
            transform: translateY(-2px);
        }

        .homepage-feature.is-featured {
            background: #138a84;
            border-color: #138a84;
            color: #fff;
        }

        .homepage-feature.is-featured p,
        .homepage-feature.is-featured .homepage-feature-meta {
            color: rgba(255, 255, 255, .76);
        }

        .homepage-icon {
            align-items: center;
            background: #e9fbf8;
            border-radius: 50%;
            color: #138a84;
            display: inline-flex;
            font-size: 20px;
            height: 48px;
            justify-content: center;
            margin-bottom: 18px;
            width: 48px;
        }

        .is-featured .homepage-icon {
            background: rgba(255, 255, 255, .16);
            color: #fff;
        }

        .homepage-feature h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .homepage-feature p {
            color: #667085;
            line-height: 1.7;
        }

        .homepage-feature-meta {
            color: #138a84;
            font-size: 13px;
            font-weight: 700;
            margin-top: 18px;
        }

        .homepage-cta {
            align-items: center;
            background: #f1fbf9;
            border: 1px solid #d5f3ef;
            border-radius: 8px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 48px;
            padding: 32px;
        }

        .homepage-cta h2 {
            color: #103d3a;
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .homepage-cta p {
            color: #667085;
            margin-bottom: 0;
        }

        @media (max-width: 767.98px) {
            .homepage-hero {
                padding-top: 32px;
            }

            .homepage-title {
                font-size: 32px;
            }

            .homepage-lead {
                font-size: 16px;
            }

            .homepage-visual {
                min-height: auto;
                padding: 20px;
            }

            .homepage-verse-card {
                padding: 22px;
            }

            .homepage-arabic {
                font-size: 30px;
            }

            .homepage-cta {
                display: block;
                padding: 24px;
                text-align: center;
            }

            .homepage-cta .btn {
                margin-top: 18px;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content homepage">
        <section class="section">
            <div class="homepage-hero text-center">

                <h1 class="homepage-title">
                    Jelajahi Kedalaman <span>Linguistik Al-Qur'an</span>
                </h1>

                <p class="homepage-lead">
                    Kuasai tata bahasa, sintaksis, dan morfologi Al-Qur'an melalui analisis struktural yang tenang,
                    bertahap, dan mudah diikuti.
                </p>

                <div class="homepage-actions">
                    <a class="btn btn-primary btn-lg" href="{{ route('register') }}">
                        <i class="fas fa-user-plus mr-2"></i>Mulai Gratis
                    </a>
                    <a class="btn btn-outline-primary btn-lg" href="#features">
                        Jelajahi Fitur
                    </a>
                </div>

                <div class="homepage-visual">
                    <div class="homepage-verse-card">
                        <div class="homepage-arabic">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</div>
                        <p class="text-muted mb-0 mt-3">
                            Mulai pembelajaran dari struktur lafadz, kedudukan kalimat, hingga latihan analisa ayat.
                        </p>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <section id="features" class="py-5">
                    <div class="text-center mb-5">
                        <h2 class="homepage-section-title">Alat untuk Pemahaman Mendalam</h2>
                        <p class="homepage-section-lead">
                            Platform ini menggabungkan pembelajaran nahwu, navigasi Al-Qur'an, dan latihan analisa dalam
                            tampilan yang lebih fokus.
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="homepage-feature card">
                                <div class="homepage-icon">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <h3>Navigator Surah</h3>
                                <p>
                                    Jelajahi surah dan ayat dengan mudah untuk dipelajari, dibaca, dan dianalisa secara
                                    bertahap.
                                </p>
                                <div class="homepage-feature-meta">Al-Qur'an digital</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="homepage-feature is-featured card">
                                <div class="homepage-icon">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                                <h3>Analisis Linguistik</h3>
                                <p>
                                    Pelajari i'rob, kategori kalimat, kedudukan, tanda, dan simbol dalam satu alur yang
                                    terstruktur.
                                </p>
                                <div class="homepage-feature-meta">Skema nahwu Al-Fuadi</div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="homepage-feature card">
                                <div class="homepage-icon">
                                    <i class="fas fa-pen-to-square"></i>
                                </div>
                                <h3>Latihan Bertahap</h3>
                                <p>
                                    Uji pemahaman melalui latihan analisa ayat sehingga proses belajar tidak berhenti di
                                    teori.
                                </p>
                                <div class="homepage-feature-meta">Belajar lewat praktik</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="homepage-cta">
                    <div>
                        <h2>Mulai Perjalanan Belajar Hari Ini</h2>
                        <p>Masuk ke dashboard untuk melanjutkan materi, latihan, atau eksplorasi ayat.</p>
                    </div>
                    <div>
                        @auth
                            <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}">
                                Buka Dashboard
                            </a>
                        @else
                            <a class="btn btn-primary btn-lg" href="{{ route('register') }}">
                                Buat Akun Gratis
                            </a>
                        @endauth
                    </div>
                </section>
            </div>
        </section>
    </div>
@endsection
