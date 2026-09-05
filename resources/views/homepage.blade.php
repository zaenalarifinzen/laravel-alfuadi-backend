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

        .homepage-badge-top {
            background-color: #e9fbf8;
            color: #138a84;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            border: 1px solid #c2f2ea;
        }

        .homepage-title {
            color: #103d3a;
            font-size: 42px;
            font-weight: 800;
            line-height: 1.2;
            margin: 10px auto 16px;
            max-width: 860px;
            letter-spacing: -0.5px;
        }

        .homepage-title span {
            color: #138a84;
            background: linear-gradient(135deg, #138a84 0%, #0d6561 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
            gap: 14px;
            margin-bottom: 40px;
        }

        .homepage-visual {
            background:
                linear-gradient(135deg, rgba(19, 138, 132, .92), rgba(29, 148, 142, .75)),
                radial-gradient(circle at top left, rgba(255, 255, 255, .32), transparent 40%),
                #138a84;
            border-radius: 12px;
            box-shadow: 0 20px 45px rgba(19, 138, 132, .18);
            margin: 0 auto 50px;
            max-width: 920px;
            overflow: hidden;
            padding: 40px;
            position: relative;
            text-align: left;
        }

        .homepage-visual::after {
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
            background: rgba(255, 255, 255, .96);
            border-radius: 10px;
            box-shadow: 0 12px 34px rgba(16, 61, 58, .18);
            max-width: 580px;
            padding: 28px 32px;
            position: relative;
            z-index: 1;
        }

        .homepage-arabic {
            color: #103d3a;
            font-family: "LPMQ Isepmisbah", "Scheherazade New", "Amiri Quran", serif;
            font-size: 36px;
            line-height: 2;
            text-align: right;
        }

        /* Section Titles */
        .homepage-section-header {
            margin-bottom: 45px;
        }

        .homepage-section-subtitle {
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 13px;
            font-weight: 700;
            color: #138a84;
            margin-bottom: 8px;
            display: block;
        }

        .homepage-section-title {
            color: #103d3a;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .homepage-section-lead {
            color: #667085;
            margin: 0 auto;
            max-width: 680px;
            font-size: 16px;
        }

        /* Card Styles  */
        .bab-card {
            border: 1px solid #e2f1ee;
            border-radius: 12px;
            background: #fff;
            padding: 28px;
            height: 100%;
            transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .bab-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 35px rgba(19, 138, 132, .12);
            border-color: #b7e8df;
        }

        .bab-number {
            font-size: 12px;
            font-weight: 800;
            color: #138a84;
            background: #e9fbf8;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 16px;
            width: fit-content;
        }

        .bab-card h3 {
            font-size: 19px;
            font-weight: 700;
            color: #103d3a;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .bab-card p {
            color: #667085;
            font-size: 14px;
            line-height: 1.65;
            margin-bottom: 18px;
            flex-grow: 1;
        }

        .bab-meta {
            font-size: 13px;
            font-weight: 600;
            color: #138a84;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Testimonial Cards */
        .testimonial-card {
            border: 1px solid #e9f4f2;
            border-radius: 12px;
            background: #fff;
            padding: 28px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 6px 20px rgba(15, 23, 42, .03);
        }

        .testimonial-stars {
            color: #ffc107;
            margin-bottom: 14px;
            font-size: 14px;
        }

        .testimonial-quote {
            color: #344054;
            font-size: 15px;
            line-height: 1.7;
            font-style: italic;
            margin-bottom: 24px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .testimonial-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #138a84;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .testimonial-info h4 {
            font-size: 15px;
            font-weight: 700;
            color: #103d3a;
            margin: 0;
        }

        .testimonial-info p {
            font-size: 13px;
            color: #667085;
            margin: 0;
        }

        /* CTA Section */
        .homepage-cta-banner {
            background: linear-gradient(135deg, #103d3a 0%, #138a84 100%);
            border-radius: 16px;
            padding: 48px;
            color: #fff;
            text-align: center;
            margin: 60px 0 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(16, 61, 58, .2);
        }

        .homepage-cta-banner h2 {
            color: #fff;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .homepage-cta-banner p {
            color: rgba(255, 255, 255, .84);
            font-size: 17px;
            max-width: 640px;
            margin: 0 auto 30px;
        }

        @media (max-width: 767.98px) {
            .homepage-hero {
                padding-top: 24px;
            }

            .homepage-title {
                font-size: 30px;
            }

            .homepage-lead {
                font-size: 15px;
            }

            .homepage-visual {
                padding: 20px;
            }

            .homepage-verse-card {
                padding: 20px;
            }

            .homepage-arabic {
                font-size: 28px;
            }

            .homepage-cta-banner {
                padding: 32px 20px;
            }

            .homepage-cta-banner h2 {
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content homepage">
        <section class="section">

            <!-- HERO SECTION -->
            <div class="homepage-hero text-center">
                @guest
                    <div class="homepage-badge-top">
                        <i class="fas fa-sparkles"></i> Metode Pembelajaran Nahwu Modern & Terstruktur
                    </div>

                    <h1 class="homepage-title">
                        Kuasai Ilmu <span>Nahwu Al-Qur'an</span> dengan Mudah & Efektif
                    </h1>

                    <p class="homepage-lead">
                        Pelajari tata bahasa, sintaksis, dan struktur i'rob Al-Qur'an secara interaktif melalui pendekatan
                        bertahap bersama Metode Al-Fuadi.
                    </p>

                    <div class="homepage-actions">
                        <a class="btn btn-primary btn-lg px-4" href="{{ route('register') }}">
                            <i class="fas fa-user-plus mr-2"></i>Mulai Belajar Gratis
                        </a>
                        <a class="btn btn-outline-primary btn-lg px-4" href="#bab-materi">
                            <i class="fas fa-book-open mr-2"></i>Jelajahi Materi
                        </a>
                    </div>
                @else
                    <div class="homepage-badge-top">
                        <i class="fas fa-user-check"></i> Selamat Datang Kembali
                    </div>

                    <h1 class="homepage-title">
                        Halo, <span>{{ auth()->user()->name }}</span>
                    </h1>

                    <p class="homepage-lead">
                        Siap untuk melanjutkan pembelajaran? Mari perdalam pemahaman kaidah nahwu dan tingkatkan latihan analisa
                        i'rob ayat Anda hari ini.
                    </p>

                    <div class="homepage-actions">
                        <a class="btn btn-primary btn-lg px-4" href="{{ route('enrollments') }}">
                            <i class="fas fa-gauge-high mr-2"></i>Lanjutkan Belajar
                        </a>
                        <a class="btn btn-outline-primary btn-lg px-4" href="{{ route('courses') }}">
                            <i class="fas fa-layer-group mr-2"></i>Lihat Kelas Online
                        </a>
                    </div>
                @endguest

                <!-- VISUAL VERSE CARD -->
                <div class="homepage-visual">
                    <div class="homepage-verse-card">
                        <div class="homepage-arabic">بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ</div>
                        <p class="text-muted mb-0 mt-3 font-weight-500">
                            "Mulai pembelajaran dari pengenalan struktur kata, tanda i'rob, hingga analisis kedudukan ayat
                            Al-Qur'an secara komprehensif."
                        </p>
                    </div>
                </div>
            </div>

            <div class="section-body">

                <!-- SECTION 1: BAB & MATERI METODE AL-FUADI (6 CARDS) -->
                <section id="bab-materi" class="py-4">
                    <div class="text-center homepage-section-header">
                        <span class="homepage-section-subtitle">Metode Al-Fuadi</span>
                        <h2 class="homepage-section-title">Modul & Kurikulum Materi Nahwu</h2>
                        <p class="homepage-section-lead">
                            Materi disusun secara sistematis agar Anda dapat menguasai fondasi kaidah tata bahasa Arab
                            hingga praktik analisis i'rob ayat.
                        </p>
                    </div>

                    <div class="row">
                        <!-- Bab 1 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="bab-card">
                                <span class="bab-number">BAB 01</span>
                                <h3>Kalimat & Pembagiannya</h3>
                                <p>
                                    Pengenalan dasar 3 jenis kata dalam bahasa Arab: Isim, Fi'il, dan Harf beserta ciri dan
                                    tanda khas masing-masing.
                                </p>

                                <div class="bab-meta">
                                    <i class="fas fa-file-lines"></i> Pembagian Al-Kalimah
                                </div>
                            </div>
                        </div>

                        <!-- Bab 2 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="bab-card">
                                <span class="bab-number">BAB 02</span>
                                <h3>Tanda-Tanda I'rob</h3>
                                <p>
                                    Memahami 4 kondisi I'rob (Rofa', Nasab, Khofad/Jar, Jazam) serta perubahan harokat asli
                                    dan penggantinya.
                                </p>
                                <div class="bab-meta">
                                    <i class="fas fa-tags"></i> العلامات والإعراب
                                </div>
                            </div>
                        </div>

                        <!-- Bab 3 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="bab-card">
                                <span class="bab-number">BAB 03</span>
                                <h3>Marfu'atul Asma'</h3>
                                <p>
                                    Struktur Isim-isim yang wajib Dibaca Rofa': Fa'il, Naibul Fa'il, Mubtada', Khobar, Isim
                                    Kana, dan Khobar Inna.
                                </p>
                                <div class="bab-meta">
                                    <i class="fas fa-arrow-up-right-dots"></i> Subjek & Predikat (مرفوعات)
                                </div>
                            </div>
                        </div>

                        <!-- Bab 4 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="bab-card">
                                <span class="bab-number">BAB 04</span>
                                <h3>Manshubatul Asma'</h3>
                                <p>
                                    Pembahasan lengkap Objek dan Keterangan: Maf'ul Bih, Maf'ul Mutlaq, Dhorof, Hal, Tamyiz,
                                    dan Mustatsna.
                                </p>
                                <div class="bab-meta">
                                    <i class="fas fa-arrows-left-right"></i> Objek & Pelengkap (منصوبات)
                                </div>
                            </div>
                        </div>

                        <!-- Bab 5 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="bab-card">
                                <span class="bab-number">BAB 05</span>
                                <h3>Majruratul Asma'</h3>
                                <p>
                                    Kaidah kata yang dibaca Jar karena Huruf Jar, Idhofah (Mudhof & Mudhof Ilaih), serta
                                    Pengikut (Tawaabi').
                                </p>
                                <div class="bab-meta">
                                    <i class="fas fa-link"></i> Sandaran & Pengikut (مجرورات)
                                </div>
                            </div>
                        </div>

                        <!-- Bab 6 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="bab-card">
                                <span class="bab-number">BAB 06</span>
                                <h3>Analisis I'rob Ayat Al-Qur'an</h3>
                                <p>
                                    Praktik langsung membedah kedudukan kalimat kata demi kata dalam ayat-ayat pilihan
                                    Al-Qur'an secara presisi.
                                </p>
                                <div class="bab-meta">
                                    <i class="fas fa-circle-nodes"></i> Praktik I'rob (إعراب القرآن)
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                <!-- SECTION 2: KELAS & PROGRAM KURSUS (3 CARDS) -->
                <section id="kelas-kursus" class="py-5">
                    <div class="text-center homepage-section-header">
                        <span class="homepage-section-subtitle">Jenjang Pembelajaran</span>
                        <h2 class="homepage-section-title">Pilihan Kelas & Program Belajar</h2>
                        <p class="homepage-section-lead">
                            Pilih program belajar yang sesuai dengan tingkat pemahaman dan target capaian Anda.
                        </p>
                    </div>

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
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-block disabled">Segera Hadir</a>
                            </div>
                        </div>
                    </div>
                </section>


                <!-- SECTION 3: TESTIMONI -->
                <section id="testimoni" class="py-4">
                    <div class="text-center homepage-section-header">
                        <span class="homepage-section-subtitle">Pengalaman Peserta</span>
                        <h2 class="homepage-section-title">Apa Kata Mereka yang Sudah Belajar?</h2>
                        <p class="homepage-section-lead">
                            Testimoni dari santri, pengajar, dan penggiat ilmu Al-Qur'an yang telah merasakan kemudahan
                            Metode Al-Fuadi.
                        </p>
                    </div>

                    <div class="row">
                        <!-- Testimoni 1 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="testimonial-card">
                                <div>
                                    <div class="testimonial-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="testimonial-quote">
                                        "Metode Al-Fuadi membuat ilmu Nahwu yang dulunya terasa rumit menjadi sangat
                                        sistematis dan mudah dipahami."
                                    </p>
                                </div>
                                <div class="testimonial-author">
                                    <div class="testimonial-avatar">ZA</div>
                                    <div class="testimonial-info">
                                        <h4>Zaenal Arifin</h4>
                                        <p>Mahasiswa</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimoni 2 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="testimonial-card">
                                <div>
                                    <div class="testimonial-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="testimonial-quote">
                                        "Sangat membantu dalam memahami i'rob ayat Al-Qur'an secara presisi. Visualisasi
                                        skema nahwunya luar biasa jelas!"
                                    </p>
                                </div>
                                <div class="testimonial-author">
                                    <div class="testimonial-avatar">SN</div>
                                    <div class="testimonial-info">
                                        <h4>Siti Nurhaliza</h4>
                                        <p>Pengajar Rumah Tahfidz</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Testimoni 3 -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="testimonial-card">
                                <div>
                                    <div class="testimonial-stars">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <p class="testimonial-quote">
                                        "Latihan interaktifnya membuat saya lebih percaya diri saat membaca dan menganalisis
                                        struktur kalimat dalam Al-Qur'an."
                                    </p>
                                </div>
                                <div class="testimonial-author">
                                    <div class="testimonial-avatar">HR</div>
                                    <div class="testimonial-info">
                                        <h4>Ustadz H. Rizky</h4>
                                        <p>Pembimbing Kajian Bahasa Arab</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                <!-- SECTION 4: CALL TO ACTION (CTA) -->
                <section class="homepage-cta-banner">
                    <h2>Mulai Perjalanan Memahami Al-Qur'an Hari Ini</h2>
                    <p>
                        Bergabunglah dengan platform pembelajaran Metode Al-Fuadi dan rasakan pengalaman belajar nahwu yang
                        modern, terstruktur, dan efektif.
                    </p>
                    <div>
                        @auth
                            <a class="btn btn-light btn-lg px-4 font-weight-bold text-primary"
                                href="{{ route('enrollments') }}">
                                <i class="fas fa-arrow-right mr-2"></i>Kelas Saya
                            </a>
                        @else
                            <a class="btn btn-light btn-lg px-4 font-weight-bold text-primary mr-2"
                                href="{{ route('register') }}">
                                <i class="fas fa-user-plus mr-2"></i>Buat Akun Gratis
                            </a>
                            <a class="btn btn-outline-light btn-lg px-4 font-weight-bold" href="{{ route('login') }}">
                                <i class="fas fa-right-to-bracket mr-2"></i>Masuk
                            </a>
                        @endauth
                    </div>
                </section>

            </div>
        </section>
    </div>
@endsection
