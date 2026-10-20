<!DOCTYPE html>
<html lang="id">

<head>
    @include('components.theme-script')
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $currentLesson->title }} &verbar; {{ $course->title }} &verbar; Al-Fuadi</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fira+Code:wght@400;500;600&family=Amiri+Quran&display=swap">

    <!-- Template & Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    @vite(['resources/css/custom.css'])

    <style>
        :root {
            --learn-bg: #f8fafc;
            --learn-sidebar-bg: #ffffff;
            --learn-header-bg: #ffffff;
            --learn-border: #e2e8f0;
            --learn-text-primary: #0f172a;
            --learn-text-muted: #64748b;
            --learn-active-bg: rgba(0, 143, 133, 0.08);
            --learn-active-border: #008f85;
            --learn-active-text: #008f85;
            --learn-hover-bg: #f1f5f9;
            --learn-code-bg: #0f172a;
            --learn-code-header: #1e293b;
            --learn-brand-gold: #f59e0b;
        }

        html[data-theme="dark"],
        html.dark-mode {
            --learn-bg: #0b1120;
            --learn-sidebar-bg: #0f172a;
            --learn-header-bg: #0f172a;
            --learn-border: #1e293b;
            --learn-text-primary: #f8fafc;
            --learn-text-muted: #94a3b8;
            --learn-active-bg: rgba(255, 255, 255, 0.06);
            --learn-active-border: #475569;
            --learn-active-text: #ffffff;
            --learn-hover-bg: rgba(255, 255, 255, 0.03);
            --learn-code-bg: #030712;
            --learn-code-header: #111827;
            --learn-brand-gold: #fbbf24;
        }

        body.learning-mode {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--learn-bg) !important;
            color: var(--learn-text-primary) !important;
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100vh;
        }

        /* Top Header */
        .learn-topbar {
            height: 60px;
            background: var(--learn-header-bg);
            border-bottom: 1px solid var(--learn-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.25rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .learn-topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0;
        }

        .learn-brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #008f85, #059669);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            text-decoration: none;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 143, 133, 0.3);
        }

        .learn-brand-icon:hover {
            color: #ffffff;
            opacity: 0.95;
        }

        .learn-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--learn-text-muted);
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .learn-back-btn:hover {
            color: var(--learn-text-primary);
            background: var(--learn-hover-bg);
            text-decoration: none;
        }

        .learn-course-heading {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--learn-text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 0;
            border-left: 1px solid var(--learn-border);
            padding-left: 1rem;
        }

        .learn-topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .learn-btn-dashboard {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--learn-text-primary);
            background: var(--learn-hover-bg);
            border: 1px solid var(--learn-border);
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .learn-btn-dashboard:hover {
            background: var(--learn-border);
            color: var(--learn-text-primary);
            text-decoration: none;
        }

        .learn-theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--learn-border);
            background: var(--learn-hover-bg);
            color: var(--learn-text-primary);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .learn-theme-toggle:hover {
            background: var(--learn-border);
        }

        /* Learning Layout Split */
        .learn-layout-wrapper {
            display: flex;
            height: calc(100vh - 60px);
            margin-top: 60px;
            position: relative;
            overflow: hidden;
        }

        /* Sidebar (Left) */
        .learn-sidebar {
            width: 360px;
            min-width: 360px;
            max-width: 360px;
            background: var(--learn-sidebar-bg);
            border-right: 1px solid var(--learn-border);
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1020;
        }

        .learn-sidebar-header {
            padding: 1.5rem 1.25rem 1.25rem;
            border-bottom: 1px solid var(--learn-border);
            flex-shrink: 0;
        }

        .learn-sidebar-badge {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--learn-brand-gold);
            margin-bottom: 0.5rem;
            display: inline-block;
        }

        .learn-sidebar-course-title {
            font-size: 1.125rem;
            font-weight: 800;
            line-height: 1.35;
            color: var(--learn-text-primary);
            margin-bottom: 0.5rem;
        }

        .learn-sidebar-progress-meta {
            font-size: 0.8125rem;
            color: var(--learn-text-muted);
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .learn-progress-track {
            height: 4px;
            background: var(--learn-border);
            border-radius: 9999px;
            overflow: hidden;
        }

        .learn-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #008f85, #10b981);
            border-radius: 9999px;
            transition: width 0.3s ease;
        }

        .learn-sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0.85rem 2.5rem;
            scrollbar-width: thin;
            scrollbar-color: var(--learn-border) transparent;
        }

        .learn-sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .learn-sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: var(--learn-border);
            border-radius: 4px;
        }

        .learn-chapter-header {
            font-size: 0.725rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--learn-text-muted);
            padding: 1rem 0.5rem 0.5rem;
            margin-top: 0.5rem;
        }

        .learn-chapter-header:first-of-type {
            margin-top: 0;
            padding-top: 0.25rem;
        }

        .learn-lesson-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            padding: 0.65rem 0.85rem;
            border-radius: 9999px;
            color: var(--learn-text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.35rem;
            transition: all 0.15s ease;
            position: relative;
            border: 1.5px solid transparent;
        }

        .learn-lesson-item:hover {
            color: var(--learn-text-primary);
            background: var(--learn-hover-bg);
            text-decoration: none;
        }

        .learn-lesson-item.active {
            color: var(--learn-active-text) !important;
            background: var(--learn-active-bg);
            border-color: var(--learn-active-border);
            font-weight: 700;
        }

        .learn-lesson-icon {
            font-size: 0.95rem;
            flex-shrink: 0;
            width: 18px;
            text-align: center;
            color: var(--learn-text-muted);
            transition: color 0.15s ease;
        }

        .learn-lesson-item.active .learn-lesson-icon {
            color: var(--learn-active-text);
        }

        .learn-lesson-item.completed .learn-lesson-icon {
            color: #10b981 !important;
        }

        .learn-lesson-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }

        /* Main Content (Right) */
        .learn-content-pane {
            flex: 1;
            height: 100%;
            overflow-y: auto;
            background: var(--learn-bg);
            scroll-behavior: smooth;
            padding: 2.5rem 1.5rem 5rem;
        }

        .learn-content-container {
            max-width: 860px;
            margin: 0 auto;
        }

        .learn-content-category {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--learn-text-muted);
            margin-bottom: 0.75rem;
        }

        .learn-content-title {
            font-size: 2.25rem;
            font-weight: 800;
            line-height: 1.25;
            color: var(--learn-text-primary);
            margin-bottom: 2rem;
            letter-spacing: -0.02em;
        }

        /* Video Container */
        .learn-video-wrapper {
            margin-bottom: 2.5rem;
            border-radius: 14px;
            overflow: hidden;
            background: #000000;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: 1px solid var(--learn-border);
        }

        .learn-video-responsive {
            position: relative;
            padding-bottom: 56.25%;
            /* 16:9 */
            height: 0;
            overflow: hidden;
        }

        .learn-video-responsive iframe,
        .learn-video-responsive video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Typography & Body Content */
        .learn-prose {
            font-size: 1.0625rem;
            line-height: 1.8;
            color: var(--learn-text-primary);
        }

        .learn-prose p {
            margin-bottom: 1.5rem;
        }

        .learn-prose h2 {
            font-size: 1.625rem;
            font-weight: 800;
            color: var(--learn-text-primary);
            margin-top: 2.5rem;
            margin-bottom: 1rem;
            letter-spacing: -0.015em;
        }

        .learn-prose h3 {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--learn-text-primary);
            margin-top: 2rem;
            margin-bottom: 0.85rem;
        }

        .learn-prose h4 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--learn-text-primary);
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .learn-prose ul,
        .learn-prose ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .learn-prose li {
            margin-bottom: 0.5rem;
        }

        .learn-prose blockquote {
            border-left: 4px solid #008f85;
            padding: 0.75rem 1.25rem;
            margin: 1.5rem 0;
            background: var(--learn-hover-bg);
            border-radius: 0 8px 8px 0;
            color: var(--learn-text-muted);
            font-style: italic;
        }

        .learn-prose img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1.5rem 0;
            border: 1px solid var(--learn-border);
        }

        .learn-prose table {
            width: 100%;
            margin-bottom: 1.5rem;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        .learn-prose table th,
        .learn-prose table td {
            padding: 0.75rem 1rem;
            border: 1px solid var(--learn-border);
        }

        .learn-prose table th {
            background: var(--learn-hover-bg);
            font-weight: 700;
        }

        /* Code Block Styling */
        .learn-code-card {
            background: var(--learn-code-bg);
            border: 1px solid var(--learn-border);
            border-radius: 12px;
            overflow: hidden;
            margin: 2rem 0;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .learn-code-header {
            background: var(--learn-code-header);
            border-bottom: 1px solid var(--learn-border);
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: 'Fira Code', monospace;
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        .learn-code-header-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .learn-code-copy-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 6px;
            padding: 0.25rem 0.6rem;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .learn-code-copy-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .learn-code-card pre {
            margin: 0;
            padding: 1.25rem;
            background: transparent;
            overflow-x: auto;
        }

        .learn-code-card code {
            font-family: 'Fira Code', monospace;
            font-size: 0.925rem;
            line-height: 1.6;
        }

        /* Quiz Banner Card */
        .learn-quiz-box {
            background: linear-gradient(135deg, rgba(0, 143, 133, 0.1), rgba(16, 185, 129, 0.05));
            border: 1px solid rgba(0, 143, 133, 0.3);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2.5rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        .learn-quiz-box-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--learn-text-primary);
            margin-bottom: 0.25rem;
        }

        .learn-quiz-box-desc {
            font-size: 0.875rem;
            color: var(--learn-text-muted);
            margin: 0;
        }

        /* Bottom Nav Buttons */
        .learn-bottom-nav {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid var(--learn-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .learn-nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            font-size: 0.925rem;
            font-weight: 600;
            padding: 0.65rem 1.25rem;
            border-radius: 9999px;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }

        .learn-nav-btn-prev {
            border: 1px solid var(--learn-border);
            background: var(--learn-hover-bg);
            color: var(--learn-text-primary);
        }

        .learn-nav-btn-prev:hover:not(.disabled) {
            background: var(--learn-border);
            color: var(--learn-text-primary);
        }

        .learn-nav-btn-next {
            background: #008f85;
            color: #ffffff !important;
            border: 1px solid #008f85;
        }

        .learn-nav-btn-next:hover:not(.disabled) {
            background: #007a71;
            border-color: #007a71;
            box-shadow: 0 4px 12px rgba(0, 143, 133, 0.25);
        }

        .learn-nav-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        .learn-complete-btn {
            border: 1px solid var(--learn-border);
            background: var(--learn-hover-bg);
            color: var(--learn-text-primary);
            border-radius: 9999px;
            padding: 0.65rem 1.25rem;
            font-size: 0.925rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .learn-complete-btn:hover {
            border-color: #10b981;
            color: #10b981;
        }

        .learn-complete-btn.is-completed {
            background: rgba(16, 185, 129, 0.15);
            border-color: #10b981;
            color: #10b981;
        }

        /* Mobile Sidebar Overlay */
        .learn-mobile-toggle {
            display: none;
        }

        .learn-sidebar-backdrop {
            display: none;
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            z-index: 1015;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        @media (max-width: 991.98px) {
            .learn-course-heading {
                display: none;
            }

            .learn-mobile-toggle {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                font-size: 0.8125rem;
                font-weight: 600;
                padding: 0.4rem 0.75rem;
                border-radius: 6px;
                background: var(--learn-hover-bg);
                border: 1px solid var(--learn-border);
                color: var(--learn-text-primary);
                cursor: pointer;
            }

            .learn-sidebar {
                position: fixed;
                top: 60px;
                left: 0;
                bottom: 0;
                transform: translateX(-100%);
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2);
            }

            .learn-sidebar.open {
                transform: translateX(0);
            }

            .learn-sidebar-backdrop.show {
                display: block;
                opacity: 1;
            }

            .learn-content-pane {
                padding: 1.75rem 1rem 4rem;
            }

            .learn-content-title {
                font-size: 1.65rem;
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>

<body class="learning-mode">
    @php
        // Resolve Video Embed URL if YouTube/Vimeo
        $videoUrl = $currentLesson->video_url ?? null;
        $embedVideoUrl = null;
        if ($videoUrl) {
            if (
                preg_match(
                    '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i',
                    $videoUrl,
                    $ytMatches,
                )
            ) {
                $embedVideoUrl = 'https://www.youtube-nocookie.com/embed/' . $ytMatches[1] . '?rel=0';
            } elseif (
                preg_match(
                    '/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)/i',
                    $videoUrl,
                    $vmMatches,
                )
            ) {
                $embedVideoUrl = 'https://player.vimeo.com/video/' . end($vmMatches);
            }
        }

        // Parse content (support both Summernote HTML and Markdown)
        $rawContent = $currentLesson->content_html ?? ($currentLesson->content_markdown ?? '');
        if ($rawContent && !preg_match('/<[a-z][\s\S]*>/i', $rawContent)) {
            $renderedBody = \Illuminate\Support\Str::markdown($rawContent);
        } else {
            $renderedBody = $rawContent;
        }

        // Calculate Prev and Next Lesson
        $currentIndex = $lessons->search(fn($l) => $l->id === $currentLesson->id);
        $prevLesson = $currentIndex !== false && $currentIndex > 0 ? $lessons->get($currentIndex - 1) : null;
        $nextLesson =
            $currentIndex !== false && $currentIndex < $lessons->count() - 1 ? $lessons->get($currentIndex + 1) : null;

        // Grouping chapters: detect if titles start with "BAB" or group logically
        $chapters = [];
        $hasCustomBab = $lessons->contains(fn($l) => preg_match('/^bab\s*\d+/i', $l->title));

        if ($hasCustomBab) {
            $currentChapterTitle = 'BAB 01 - PENDAHULUAN';
            foreach ($lessons as $item) {
                if (preg_match('/^(bab\s*\d+[^:\-\n]*[-:]?\s*[^:\n]*)/i', $item->title, $chMatch)) {
                    $currentChapterTitle = strtoupper(trim($chMatch[1]));
                }
                $chapters[$currentChapterTitle][] = $item;
            }
        } else {
            // Group by 5 lessons per chapter or single chapter
            if ($lessons->count() > 6) {
                $chunked = $lessons->chunk(5);
                foreach ($chunked as $index => $chunk) {
                    $chapterNum = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                    $chTitle = "BAB {$chapterNum} - MATERI BAGIAN " . ($index + 1);
                    $chapters[$chTitle] = $chunk;
                }
            } else {
                $chapters['BAB 01 - DAFTAR MATERI'] = $lessons;
            }
        }
    @endphp

    <!-- Top Navigation Bar -->
    <header class="learn-topbar">
        <div class="learn-topbar-left">
            <a href="{{ route('home') }}" class="learn-brand-icon" title="Beranda Al-Fuadi">
                <span>A</span>
            </a>

            <!-- Mobile Sidebar Toggle -->
            <button type="button" class="learn-mobile-toggle" id="sidebarToggleBtn" aria-label="Buka Daftar Materi">
                <i class="fas fa-list-ul"></i>
            </button>

            <a href="{{ route('courses.show', $course->slug) }}" class="learn-back-btn">
                <i class="fas fa-chevron-left"></i>
                <span>Kembali</span>
            </a>

            <h1 class="learn-course-heading" title="{{ $course->title }}">
                {{ $course->title }}
            </h1>
        </div>

        <div class="learn-topbar-right">
            <!-- Theme Toggle -->
            {{-- <button type="button" class="learn-theme-toggle theme-toggle-btn" onclick="toggleTheme()" title="Ganti Tema">
                <i class="fas fa-moon theme-toggle-moon"></i>
                <i class="fas fa-sun theme-toggle-sun"></i>
            </button> --}}

            <!-- Dashboard Button -->
            @auth
                <div class="dropdown"><a href="#" data-toggle="dropdown"
                        class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        {{-- <img alt="image" src="{{ asset('img/avatar/avatar-3.png') }}" class="rounded-circle mr-1"> --}}
                        <div class="d-sm-none d-lg-inline-block">{{ auth()->user()->name }}</div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if (auth()->user()->roles !== 'user')
                            <a href="{{ route('dashboard') }}" class="dropdown-item has-icon">
                                <i class="fas fa-border-all"></i>Dashboard
                            </a>
                        @endif
                        <a href="{{ route('enrollments') }}" class="dropdown-item has-icon">
                            <i class="fas fa-chalkboard"></i> Kelas Saya
                        </a>
                        <a href="{{ route('exercise-level.index') }}" class="dropdown-item has-icon">
                            <i class="far fa-pen-to-square"></i> Latihan
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile') }}" class="dropdown-item has-icon">
                            <i class="far fa-user"></i> Profil
                        </a>
                        <a href="javascript:void(0)" class="dropdown-item has-icon" id="theme-toggle-btn"
                            onclick="toggleTheme()">
                            <i class="fas fa-circle-half-stroke"></i> Ganti Tema
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item has-icon text-danger"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            @else
                <li>
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        </i> Masuk
                    </a>
                </li>
            @endauth
        </div>
    </header>

    <!-- Mobile Backdrop -->
    <div class="learn-sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Main Learning Layout Wrapper -->
    <div class="learn-layout-wrapper">
        <!-- Sidebar (Left) -->
        <aside class="learn-sidebar" id="learnSidebar">
            <div class="learn-sidebar-header">
                <span class="learn-sidebar-badge">KURSUS</span>
                <h2 class="learn-sidebar-course-title">{{ $course->title }}</h2>
                <div class="learn-sidebar-progress-meta">
                    <span><strong id="completedCountText">0</strong> / {{ $lessons->count() }} pelajaran</span>
                    <span id="completedPercentText">0%</span>
                </div>
                <div class="learn-progress-track">
                    <div class="learn-progress-fill" id="courseProgressFill" style="width: 0%"></div>
                </div>
            </div>

            <div class="learn-sidebar-scroll" id="sidebarScrollArea">
                @foreach ($chapters as $chapterName => $chapterLessons)
                    <div class="learn-chapter-header">{{ $chapterName }}</div>
                    @foreach ($chapterLessons as $item)
                        @php
                            $isActive = $item->id === $currentLesson->id;
                            // Clean title if it has redundant chapter prefix
                            $displayTitle = preg_replace('/^bab\s*\d+\s*[-:]\s*/i', '', $item->title);
                        @endphp
                        <a href="{{ route('learn', ['course' => $course->slug, 'lesson' => $item->id]) }}"
                            class="learn-lesson-item {{ $isActive ? 'active' : '' }}"
                            data-lesson-id="{{ $item->id }}" id="lesson-item-{{ $item->id }}"
                            title="{{ $item->title }}">
                            <span class="learn-lesson-icon">
                                @if ($isActive)
                                    <i class="fa-regular fa-circle-dot"></i>
                                @else
                                    <i class="fa-regular fa-circle"></i>
                                @endif
                            </span>
                            <span class="learn-lesson-title">{{ $displayTitle }}</span>
                        </a>
                    @endforeach
                @endforeach
            </div>
        </aside>

        <!-- Content Area (Right) -->
        <main class="learn-content-pane" id="learnContentPane">
            <div class="learn-content-container">
                <!-- Category / Breadcrumb -->
                <div class="learn-content-category">
                    {{ $course->title ?? 'Materi Pembelajaran' }}
                </div>

                <!-- Main Lesson Title -->
                <h1 class="learn-content-title">{{ $currentLesson->title }}</h1>

                <!-- Optional Video Player -->
                @if ($embedVideoUrl)
                    <div class="learn-video-wrapper">
                        <div class="learn-video-responsive">
                            <iframe src="{{ $embedVideoUrl }}" title="{{ $currentLesson->title }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                @elseif ($videoUrl && filter_var($videoUrl, FILTER_VALIDATE_URL) && preg_match('/\.(mp4|webm|ogg)$/i', $videoUrl))
                    <div class="learn-video-wrapper">
                        <div class="learn-video-responsive">
                            <video controls playsinline>
                                <source src="{{ $videoUrl }}">
                                Browser Anda tidak mendukung pemutar video.
                            </video>
                        </div>
                    </div>
                @endif

                <!-- Lesson Body Text / Content -->
                <div class="learn-prose" id="lessonProseContent">
                    @if (!empty(trim(strip_tags($renderedBody))))
                        {!! $renderedBody !!}
                    @else
                        <div class="text-muted font-italic py-4">
                            Materi untuk sesi ini belum tersedia.
                        </div>
                    @endif
                </div>

                <!-- Quizzes Box if Available -->
                @if ($currentLesson->quizzes && $currentLesson->quizzes->count() > 0)
                    @foreach ($currentLesson->quizzes as $quiz)
                        <div class="learn-quiz-box">
                            <div>
                                <div class="learn-quiz-box-title">
                                    <i class="fas fa-graduation-cap text-primary mr-2"></i>
                                    {{ $quiz->title ?? 'Kuis Evaluasi' }}
                                </div>
                                {{-- <p class="learn-quiz-box-desc">
                                    {{ $quiz->questions_count ?? $quiz->questions->count() }} pertanyaan &bull; Uji pemahaman Anda terhadap materi ini.
                                </p> --}}
                            </div>
                            <a href="{{ route('quiz.show', [$course->slug, $currentLesson->id, $quiz->id]) }}"
                                class="btn btn-primary px-4 py-2 font-weight-bold">
                                Mulai Kuis <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @endforeach
                @endif

                <div class="card">
                    <div class="card-header">
                        <span class="text-primary">
                            <i class="fa-regular fa-circle-check mr-2"></i>Progres Belajar
                        </span>
                    </div>
                    <div class="card-body">
                        <h5>Sudah paham materi ini?</h5>
                        <p>Tandai selesai untuk menyimpan progresmu di kelas ini.</p>
                        <button type="button" class="learn-complete-btn" id="markCompleteBtn"
                            data-lesson-id="{{ $currentLesson->id }}">
                            <i class="fa-regular fa-circle-check"></i>
                            <span id="completeBtnText">Tandai Selesai</span>
                        </button>
                    </div>
                </div>

                <!-- Bottom Navigation Actions -->
                <nav class="learn-bottom-nav" aria-label="Navigasi Materi">
                    @if ($prevLesson)
                        <a href="{{ route('learn', ['course' => $course->slug, 'lesson' => $prevLesson->id]) }}"
                            class="learn-nav-btn learn-nav-btn-prev">
                            <i class="fas fa-arrow-left"></i>
                            <span>Sebelumnya</span>
                        </a>
                    @else
                        <span class="learn-nav-btn learn-nav-btn-prev disabled">
                            <i class="fas fa-angle-left"></i>
                            <span>Sebelumnya</span>
                        </span>
                    @endif

                    @if ($nextLesson)
                        <a href="{{ route('learn', ['course' => $course->slug, 'lesson' => $nextLesson->id]) }}"
                            class="learn-nav-btn learn-nav-btn-next">
                            <span>Selanjutnya</span>
                            <i class="fas fa-angle-right"></i>
                        </a>
                    @else
                        <a href="{{ route('courses.show', $course->slug) }}"
                            class="learn-nav-btn learn-nav-btn-next">
                            <span>Klaim Sertifikat</span>
                        </a>
                    @endif
                </nav>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    @vite(['resources/js/app.js', 'resources/js/custom.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto scroll active lesson item into view in sidebar
            const activeLessonEl = document.querySelector('.learn-lesson-item.active');
            if (activeLessonEl) {
                activeLessonEl.scrollIntoView({
                    block: 'center',
                    behavior: 'smooth'
                });
            }

            // Mobile Sidebar Toggle
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const sidebar = document.getElementById('learnSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (toggleBtn && sidebar && backdrop) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    backdrop.classList.remove('show');
                });
            }

            // Code Block Beautifier & Syntax Highlighting
            const proseEl = document.getElementById('lessonProseContent');
            if (proseEl) {
                const codeBlocks = proseEl.querySelectorAll('pre');
                codeBlocks.forEach(function(pre, idx) {
                    const code = pre.querySelector('code') || pre;
                    const codeText = code.innerText;

                    // Detect language from class or default
                    let lang = 'code';
                    const classList = code.className || pre.className || '';
                    const match = classList.match(/language-([a-z0-9_-]+)/i);
                    if (match) {
                        lang = match[1];
                    }

                    // Format as modern card with topbar
                    const card = document.createElement('div');
                    card.className = 'learn-code-card';

                    const header = document.createElement('div');
                    header.className = 'learn-code-header';

                    const headerLeft = document.createElement('div');
                    headerLeft.className = 'learn-code-header-left';
                    headerLeft.innerHTML =
                        `<i class="fa-solid fa-code mr-1"></i> <span>${lang === 'code' ? 'snippet' : lang}</span>`;

                    const copyBtn = document.createElement('button');
                    copyBtn.type = 'button';
                    copyBtn.className = 'learn-code-copy-btn';
                    copyBtn.innerHTML = `<i class="fa-regular fa-copy"></i> Copy`;

                    copyBtn.addEventListener('click', function() {
                        navigator.clipboard.writeText(codeText).then(function() {
                            copyBtn.innerHTML =
                                `<i class="fa-solid fa-check text-success"></i> Tersalin!`;
                            setTimeout(function() {
                                copyBtn.innerHTML =
                                    `<i class="fa-regular fa-copy"></i> Copy`;
                            }, 2000);
                        }).catch(function() {
                            copyBtn.innerHTML = `<i class="fa-solid fa-check"></i> Disalin`;
                        });
                    });

                    header.appendChild(headerLeft);
                    header.appendChild(copyBtn);

                    // Insert card before pre and move pre inside
                    pre.parentNode.insertBefore(card, pre);
                    card.appendChild(header);
                    card.appendChild(pre);

                    // Highlight syntax if highlight.js loaded
                    if (window.hljs) {
                        hljs.highlightElement(code);
                    }
                });
            }

            // Progress & Completed Lessons Tracker in LocalStorage
            const courseId = "{{ $course->id }}";
            const storageKey = `alfuadi_course_${courseId}_completed_lessons`;
            let completedLessons = [];

            try {
                const saved = localStorage.getItem(storageKey);
                if (saved) {
                    completedLessons = JSON.parse(saved);
                }
            } catch (e) {
                completedLessons = [];
            }

            const currentLessonId = {{ $currentLesson->id }};
            const totalLessons = {{ $lessons->count() }};
            const markBtn = document.getElementById('markCompleteBtn');
            const completeBtnText = document.getElementById('completeBtnText');
            const completedCountText = document.getElementById('completedCountText');
            const completedPercentText = document.getElementById('completedPercentText');
            const progressFill = document.getElementById('courseProgressFill');

            function updateProgressUI() {
                // Update sidebar lesson checkmarks
                document.querySelectorAll('.learn-lesson-item').forEach(function(el) {
                    const id = parseInt(el.dataset.lessonId);
                    const icon = el.querySelector('.learn-lesson-icon i');
                    if (completedLessons.includes(id)) {
                        el.classList.add('completed');
                        if (!el.classList.contains('active')) {
                            icon.className = 'fa-solid fa-circle-check text-success';
                        }
                    } else {
                        el.classList.remove('completed');
                        if (!el.classList.contains('active')) {
                            icon.className = 'fa-regular fa-circle';
                        }
                    }
                });

                // Update counts
                const count = completedLessons.length;
                const percent = totalLessons > 0 ? Math.round((count / totalLessons) * 100) : 0;

                if (completedCountText) completedCountText.innerText = count;
                if (completedPercentText) completedPercentText.innerText = percent + '%';
                if (progressFill) progressFill.style.width = percent + '%';

                // Update Current Button State
                if (markBtn && completeBtnText) {
                    if (completedLessons.includes(currentLessonId)) {
                        markBtn.classList.add('is-completed');
                        markBtn.querySelector('i').className = 'fa-solid fa-circle-check';
                        completeBtnText.innerText = 'Selesai';
                    } else {
                        markBtn.classList.remove('is-completed');
                        markBtn.querySelector('i').className = 'fa-regular fa-circle-check';
                        completeBtnText.innerText = 'Tandai Selesai';
                    }
                }
            }

            // Handle Mark Complete Click
            if (markBtn) {
                markBtn.addEventListener('click', function() {
                    const idx = completedLessons.indexOf(currentLessonId);
                    if (idx > -1) {
                        completedLessons.splice(idx, 1);
                    } else {
                        completedLessons.push(currentLessonId);
                    }
                    localStorage.setItem(storageKey, JSON.stringify(completedLessons));
                    updateProgressUI();
                });
            }

            // Initial UI sync
            updateProgressUI();
        });
    </script>
</body>

</html>
