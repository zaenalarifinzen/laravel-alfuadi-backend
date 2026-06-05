@extends('layouts.app')

@section('title', 'Latihan analisa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Latihan analisa</h1>
                @if (auth()->user()->roles === 'administrator')
                    <div class="section-header-button">
                        <a href="{{ route('exercise-level.create') }}" class="btn btn-primary">
                            Tambah
                        </a>
                    </div>
                @endif
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
                        @foreach ($questionLevel as $level)
                            @if ($level->slug === 'alquran')
                                @continue
                            @endif
                            <a href="#" class="text-decoration-none">
                                <div class="card">
                                    <div class="card-header">
                                        <figure class="avatar bg-secondary mr-2 text-white"
                                            data-initial="{{ $level->level_number }}"></figure>
                                        <h4>{{ $level->name }}</h4>
                                    </div>
                                    <div class="collapse show" id="mycard-collapse">
                                        <div class="card-body">
                                            <div class="progress mb-3" data-height="5">
                                                <div class="progress-bar bg-success" role="progressbar" data-width="0%"
                                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div class="card-description">0 dari 0 soal selesai</div>
                                                <div class="card-description">0%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('exercise.alquran') }}" class="text-decoration-none">
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
                                <a href="{{ route('exercise.alquran') }}" class="ticket-item">
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
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/utils/storage-helper.js') }}"></script>
@endpush

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const STORAGE_PREFIX = "answer_user_";

            const lastOpenedEl = document.getElementById("last-opened-info");
            const labelEl = document.getElementById("info-label");

            function getLastExerciseData() {
                try {
                    const key = getActiveStorageKey(STORAGE_PREFIX);
                    if (!key) return null;

                    const raw = localStorage.getItem(key);
                    return raw ? JSON.parse(raw) : null;
                } catch (e) {
                    return null;
                }
            }

            function renderLastOpened(data) {
                const isVisible = !!(data?.surah.name && data?.verse?.number);

                lastOpenedEl.style.display = isVisible ? "inline-block" : "none";

                if (isVisible) {
                    labelEl.textContent = `${cachedData.surah.name} ayat ${cachedData.verse.number}`;
                }
            }

            const cachedData = getLastExerciseData();
            renderLastOpened(cachedData);
        });
    </script>
@endpush
