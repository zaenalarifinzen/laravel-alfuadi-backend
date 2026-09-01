@extends('layouts.app')

@section('title', 'Latihan analisa')

@push('style')
    <!-- CSS Libraries -->
    <style>
        .preview-title {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .preview-subtitle {
            color: #6c757d;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .avatar::before {
            content: attr(data-initial);
        }

        .card {
            border-radius: 10px;
            border: 1px solid #e9ecef;
            transition: box-shadow 0.15s ease, transform 0.15s ease;
            overflow: hidden;
        }

        a .card:hover {
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .card-header {
            background: #fff;
            border-bottom: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem 0.5rem;
        }

        .card-header h4 {
            font-size: 1.05rem;
            font-weight: 600;
            margin: 0 0 0 10px;
            color: #212529;
        }

        .card-header-left {
            display: flex;
            align-items: center;
        }

        .card-body {
            padding: 0.5rem 1.25rem 1.25rem;
        }

        .card-description {
            font-size: 0.82rem;
        }

        .progress {
            border-radius: 10px;
            background-color: #eef0f2;
        }

        /* Kartu terkunci */
        .card-locked {
            opacity: 0.65;
            cursor: not-allowed;
            filter: grayscale(55%);
            user-select: none;
        }

        .card-locked:hover {
            box-shadow: none !important;
            transform: none !important;
        }

        .lock-badge {
            font-size: 0.72rem;
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 20px;
        }

        .card-locked .card-description i {
            color: #adb5bd;
        }
    </style>
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Latihan analisa</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Level</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h2 class="section-title">Pilih level</h2>
                        <p class="section-lead mb-0">
                            Latih kemampuan nahwumu dengan analisa soal-soal secara langsung.
                        </p>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">
                        @foreach ($exerciseLevel as $level)
                            @if ($level->slug === 'alquran')
                                @continue
                            @endif

                            @unless ($level->is_active)
                                <div class="card card-locked" aria-disabled="true">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <figure class="avatar bg-secondary mr-2 text-white"
                                                data-initial="{{ $level->level_number }}"></figure>
                                            <h4 class="mb-0">{{ $level->name }}</h4>
                                        </div>
                                        <span class="badge badge-secondary lock-badge">
                                            <i class="fa fa-lock mr-1"></i> Terkunci
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="card-description text-muted">
                                                <i class="fa fa-lock-alt mr-1"></i> Selesaikan level sebelumnya untuk
                                                membuka
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('exercise.analyze', $level->slug) }}" class="text-decoration-none">
                                    <div class="card">
                                        <div class="card-header d-flex align-items-center justify-content-start">
                                            <figure class="avatar bg-secondary mr-2 text-white"
                                                data-initial="{{ $level->level_number }}"></figure>
                                            <h4>{{ $level->name }}</h4>
                                        </div>
                                        <div class="collapse show" id="mycard-collapse-{{ $level->id }}">
                                            <div class="card-body">
                                                <div class="progress mb-3" data-height="5">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        data-width="{{ $level->progress_percentage ?? 0 }}%"
                                                        aria-valuenow="{{ $level->progress_percentage ?? 0 }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <div class="card-description">
                                                        {{ $level->completed_count ?? 0 }} dari
                                                        {{ $level->total_count ?? 0 }} soal selesai
                                                    </div>
                                                    <div class="card-description">{{ $level->progress_percentage ?? 0 }}%
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endunless
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('exercise.analyze', 'alquran') }}" class="text-decoration-none">
                    <div class="card card-hero">
                        <div class="card-header">
                            <div class="card-icon">
                                <i class="fas fa-book-quran"></i>
                            </div>
                            <h4>Al-Quran</h4>
                            <div class="card-description">Analisa langsung dari ayat Al-Quran</div>
                        </div>
                        <div class="card-body p-0" id="last-opened-info" style="display: none">
                            <div class="tickets-list">
                                <a href="{{ route('exercise.analyze', 'alquran') }}" class="ticket-item">
                                    {{-- <div class="ticket-title">
                                        <h4>My order hasn't arrived yet</h4>
                                    </div> --}}
                                    <div class="ticket-info">
                                        <div id="info-label"></div>
                                        <div class="bullet"></div>
                                        <div class="text-primary">Lanjutkan</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </section>
    </div>


@endsection


@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    @vite(['resources/js/page/exercise/level-index.js'])
@endpush
