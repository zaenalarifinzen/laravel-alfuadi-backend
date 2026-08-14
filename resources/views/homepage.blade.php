<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Al-Fuadi</title>
    <!-- Material Symbols -->
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600&amp;family=Manrope:wght@600;700&amp;family=Noto+Serif:wght@400&amp;display=swap"
        rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        :root {
            --bs-primary: #006160;
            --bs-primary-rgb: 0, 97, 96;
            --primary-container: #0d7c7a;
            --primary-fixed: #98f2ef;
            --background: #f8fafb;
            --on-background: #191c1d;
            --surface-variant: #e1e3e4;
            --on-surface-variant: #3e4948;
            --surface-container-lowest: #ffffff;
            --secondary-container: #d4e6e5;
            --surface-container: #eceeef;
            --surface-container-high: #e6e8e9;
        }

        body {
            background-color: var(--background);
            color: var(--on-background);
            font-family: 'Hanken Grotesk', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-headline {
            font-family: 'Manrope', sans-serif;
        }

        .font-arabic {
            font-family: 'Noto Serif', serif;
        }

        .text-primary-custom {
            color: var(--bs-primary);
        }

        .bg-primary-custom {
            background-color: var(--bs-primary);
            color: white;
        }

        .bg-primary-custom:hover {
            background-color: rgba(0, 97, 96, 0.9);
            color: white;
        }

        .bg-primary-container {
            background-color: var(--primary-container);
        }

        .text-on-primary-container {
            color: #c2fffc;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(13, 124, 122, 0.1);
        }

        .soft-shadow {
            box-shadow: 0 4px 24px rgba(13, 124, 122, 0.08);
        }

        .soft-shadow-hover:hover {
            box-shadow: 0 12px 32px rgba(13, 124, 122, 0.12);
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        @keyframes subtle-drift {
            0% {
                background-position: 0% 0%;
            }

            50% {
                background-position: 100% 100%;
            }

            100% {
                background-position: 0% 0%;
            }
        }

        .bg-animated-mesh {
            background: radial-gradient(circle at 10% 20%, rgba(152, 242, 239, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(0, 97, 96, 0.05) 0%, transparent 40%);
            background-size: 200% 200%;
            animation: subtle-drift 30s ease infinite;
            background-color: var(--background);
        }

        .top-nav {
            background: rgba(248, 250, 251, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(13, 124, 122, 0.08);
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .feature-card {
            background-color: var(--surface-container-lowest);
            border-radius: 1rem;
            border: 1px solid rgba(225, 227, 228, 0.5);
            height: 100%;
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .abstract-visual {
            background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCdCV1GGr5xnTdwIk9nr1EIjHqEjpQRIRlg5wpFbSn69Y7Ke2AujrsHX41PaXKvm5cB5i_-1ijwa5hqpPL31_MSuSHvPli6O43553D1K01mjuNVGgh9CO3lUwBvNhMekgasDVy9uTewuBXH70Z3WKMwNT0n01mAvOe4ukALe7SyPx-4dIy8GPUXRLC5kPZ6KU5KgWell_wwBdbbPdWue_HqQtXb5usiHCM-I8DneYtWrz4bod1zLHJd');
            background-size: cover;
            background-position: center;
            opacity: 0.8;
            mix-blend-mode: multiply;
        }
    </style>
</head>

<body class="bg-animated-mesh">
    <!-- TopNavBar -->
    <header class="sticky-top top-nav shadow-sm">
        <div class="container py-3">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Brand -->
                <a class="text-decoration-none text-primary-custom font-headline fs-4 fw-semibold d-flex align-items-center gap-2"
                    href="{{ route('home') }}">Al-Fuadi
                </a>
                <!-- Navigation Links (Desktop) -->
                <nav class="d-none d-md-flex gap-4">
                    <a class="text-decoration-none text-secondary fw-medium opacity-75" href="#">Program</a>
                    <a class="text-decoration-none text-secondary fw-medium opacity-75" href="#">Kurikulum</a>
                    <a class="text-decoration-none text-secondary fw-medium opacity-75" href="#">Ulama</a>
                    <a class="text-decoration-none text-secondary fw-medium opacity-75" href="#">Sumber Daya</a>
                </nav>
                <!-- Actions -->
                <div class="d-flex align-items-center gap-3">
                    <a class="btn bg-primary-custom rounded-pill px-4 fw-medium" href="{{ route('login') }}">Masuk</a>
                </div>
            </div>
        </div>
    </header>
    <!-- Main Content Canvas -->
    <main class="flex-grow-1">
        <!-- Hero Section -->
        <section class="container py-5 text-center position-relative">
            <h1 class="hero-title mb-3 mx-auto" style="max-width: 800px;">
                Jelajahi Kedalaman <span class="text-primary-custom">Linguistik Al-Qur'an</span>
            </h1>
            <p class="fs-5 text-secondary mx-auto mb-4" style="max-width: 600px;">
                Kuasai tata bahasa, sintaksis, dan morfologi Al-Qur'an melalui analisis struktural tingkat lanjut.
                Lingkungan yang tenang dirancang untuk fokus yang mendalam dan pemahaman yang sebenarnya.
            </p>
            <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3 mb-5">
                <a class="btn bg-primary-custom rounded-pill px-5 py-2 fs-5 font-headline" href="{{ route('register') }}">
                    Mulai Gratis
                </a>
                <a class="text-decoration-none text-primary-custom fw-medium d-flex align-items-center gap-1"
                    href="#features">
                    Jelajahi Fitur
                    <span class="material-symbols-outlined fs-6">arrow_downward</span>
                </a>
            </div>
            <!-- Hero Abstract Visual -->
            <div class="mx-auto position-relative rounded-4 overflow-hidden soft-shadow bg-white"
                style="max-width: 960px; height: 350px;">
                <div class="w-100 h-100 abstract-visual"></div>
                <div
                    class="position-absolute top-50 start-50 translate-middle glass-panel p-4 rounded-4 d-flex flex-column align-items-center border border-success-subtle">
                    <span class="font-arabic text-primary-custom" style="font-size: 2.5rem;">بِسْمِ اللَّهِ</span>
                    <span class="text-secondary mt-2 tracking-widest text-uppercase"
                        style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em;">Dengan nama Allah</span>
                </div>
            </div>
        </section>
        <!-- Value Proposition / Features -->
        <section class="container py-5" id="features">
            <div class="text-center mb-5">
                <h2 class="font-headline fs-2 mb-3">Alat untuk Pemahaman Mendalam</h2>
                <p class="text-secondary mx-auto" style="max-width: 600px;">Platform kami menggabungkan penghormatan
                    tradisional dengan presisi analitis modern untuk memandu studi Anda.</p>
            </div>
            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-4">
                    <div class="feature-card p-4 soft-shadow soft-shadow-hover d-flex flex-column">
                        <div class="icon-box bg-info bg-opacity-10 text-primary-custom mb-4">
                            <span class="material-symbols-outlined"
                                style="font-variation-settings: 'FILL' 1;">explore</span>
                        </div>
                        <h3 class="font-headline fs-4 mb-2">Navigator Surah</h3>
                        <p class="text-secondary flex-grow-1">Jelajahi dan pilih ayat mana saja dengan mudah untuk
                            dipelajari. Navigasi teks dengan antarmuka intuitif yang dirancang untuk meminimalkan beban
                            kognitif.</p>
                        <div class="mt-4 pt-3 border-top text-primary-custom fw-semibold d-flex align-items-center gap-1"
                            style="font-size: 0.85rem; cursor: pointer;">
                            Pelajari lebih lanjut <span class="material-symbols-outlined"
                                style="font-size: 1rem;">arrow_forward</span>
                        </div>
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="col-md-4">
                    <div class="bg-primary-container p-4 rounded-4 soft-shadow soft-shadow-hover d-flex flex-column h-100 text-white"
                        style="transform: translateY(-10px);">
                        <div class="icon-box bg-white text-primary-custom mb-4">
                            <span class="material-symbols-outlined"
                                style="font-variation-settings: 'FILL' 1;">analytics</span>
                        </div>
                        <h3 class="font-headline fs-4 mb-2 text-on-primary-container">Analisis Linguistik</h3>
                        <p class="flex-grow-1 text-white-50">Selami lebih dalam ke dalam I'rob, morfologi, dan
                            sintaksis. Visualisasikan struktur kalimat dan aturan tata bahasa dengan kejelasan yang tak
                            tertandingi.</p>
                        <div class="mt-4 bg-white bg-opacity-10 rounded p-3 d-flex align-items-end gap-2"
                            style="height: 100px;">
                            <div class="w-100 bg-info bg-opacity-50 rounded-top" style="height: 60%;"></div>
                            <div class="w-100 bg-info bg-opacity-75 rounded-top" style="height: 80%;"></div>
                            <div class="w-100 bg-info bg-opacity-25 rounded-top" style="height: 40%;"></div>
                            <div class="w-100 bg-info rounded-top" style="height: 100%;"></div>
                        </div>
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="col-md-4">
                    <div class="feature-card p-4 soft-shadow soft-shadow-hover d-flex flex-column">
                        <div class="icon-box bg-info bg-opacity-10 text-primary-custom mb-4">
                            <span class="material-symbols-outlined"
                                style="font-variation-settings: 'FILL' 1;">monitoring</span>
                        </div>
                        <h3 class="font-headline fs-4 mb-2">Kemajuan Belajar</h3>
                        <p class="text-secondary flex-grow-1">Lacak perjalanan Anda mempelajari bahasa Arab Al-Qur'an.
                            Pantau pencapaian dan visualisasikan penguasaan Anda dari waktu ke waktu.</p>
                        <div class="mt-4 pt-3 border-top">
                            <div class="progress" style="height: 8px;">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="65"
                                    class="progress-bar bg-primary-custom" role="progressbar" style="width: 65%;"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 text-secondary fw-semibold"
                                style="font-size: 0.75rem;">
                                <span>Tingkat 2</span>
                                <span>65%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Call to Action Banner -->
        <section class="container py-5 mb-5">
            <div class="p-5 rounded-4 d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 position-relative overflow-hidden"
                style="background-color: var(--surface-container-high);">
                <div class="position-absolute bg-info opacity-25 rounded-circle blur-3xl"
                    style="width: 250px; height: 250px; right: -50px; top: -50px; filter: blur(50px);"></div>
                <div class="position-relative z-1">
                    <h2 class="font-headline fs-2 mb-2">Mulai Perjalanan Anda Hari Ini</h2>
                    <p class="text-secondary mb-0">Bergabunglah dengan ribuan siswa yang mengungkap keindahan linguistik
                        Al-Qur'an.</p>
                </div>
                <div class="position-relative z-1">
                    <a class="btn bg-primary-custom rounded-pill px-5 py-3 font-headline fs-5 shadow-sm" href="{{ route('register') }}">
                        Buat Akun Gratis
                    </a>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <footer class="py-5" style="background-color: var(--surface-container);">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
            <div class="text-center text-md-start">
                <span
                    class="font-headline fs-4 text-primary-custom d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">menu_book</span>
                    Pusat Pembelajaran Al-Fuadi
                </span>
                <p class="text-secondary mb-0" style="font-size: 0.75rem;">© 2024 Pusat Pembelajaran Al-Fuadi. Semua hak
                    cipta dilindungi undang-undang.</p>
            </div>
            <nav class="d-flex flex-wrap justify-content-center gap-4">
                <a class="text-decoration-none text-secondary text-decoration-underline" href="#"
                    style="font-size: 0.75rem;">Kebijakan Privasi</a>
                <a class="text-decoration-none text-secondary text-decoration-underline" href="#"
                    style="font-size: 0.75rem;">Ketentuan Layanan</a>
                <a class="text-decoration-none text-secondary text-decoration-underline" href="#"
                    style="font-size: 0.75rem;">Hubungi Kami</a>
                <a class="text-decoration-none text-secondary text-decoration-underline" href="#"
                    style="font-size: 0.75rem;">Tentang Metodologi Kami</a>
            </nav>
        </div>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>