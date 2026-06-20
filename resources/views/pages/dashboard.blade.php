@extends('layouts.app')

@section('title', 'Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>

            @auth
                <div class="section-body">
                    @if (request()->query('verified') == 1)
                    <div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                            Akun anda berhasil diverifikasi.
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="hero bg-primary text-white">
                                <div class="hero-inner">
                                    <h2>Selamat datang, {{ auth()->user()->name }}!</h2>
                                    <p class="lead">Senang melihat Anda kembali.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-light">
                                    <h4 class="mb-0">Ayat pilihan hari ini</h4>
                                </div>
                                <div class="card-body align-items-center d-flex flex-column justify-content-center">
                                    @if ($randomVerse)
                                        <p class="text-right text-center arabic-text words mb-5">{{ $randomVerse->text }}</p>
                                        <p class="text-muted text-center mb-2">{{ $randomVerse->translation_indo }}</p>
                                        <p class="mb-0 text-primary text-center">QS.
                                            {{ $randomVerse->surah->name ?? 'Al-Qur\'an' }} ayat {{ $randomVerse->number }}</p>
                                    @else
                                        <p class="mb-0 text-muted">Belum ada data ayat untuk ditampilkan.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (auth()->user()->roles === 'administrator' || auth()->user()->roles === 'operator')
                            <div class="col-12 col-lg-6 mb-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-header bg-light">
                                        <h4 class="mb-0">Aktivitas terakhir</h4>
                                    </div>
                                    <div class="card-body">
                                        @if ($latestTask)
                                            <ul class="list-unstyled list-unstyled-border">
                                                <li class="media">
                                                    <img class="mr-3 rounded-circle" width="50"
                                                        src="{{ asset('img/avatar/avatar-3.png') }}" alt="avatar">
                                                    <div class="media-body">
                                                        <h6 class="media-title"><a
                                                                href="{{ route('words.create') }}">{{ $latestTask }}</a>
                                                        </h6>
                                                        <div class="text-small text-muted">
                                                            {{ $updated_at ? $updated_at->diffForHumans() : 'Baru saja' }}
                                                            <div class="bullet"></div>
                                                            <span class="text-primary">Input i'rob</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center"
                                                style="min-height: 150px;">
                                                <span class="text-muted text-center">Belum ada aktivitas</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (auth()->user()->roles === 'user' && $latestExercise)
                            <div class="col-12 col-lg-6 mb-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-header bg-light">
                                        <h4 class="mb-0">Aktivitas terakhir</h4>
                                    </div>
                                    <div class="card-body">
                                        @if ($latestExercise)
                                            <ul class="list-unstyled list-unstyled-border">
                                                <li class="media">
                                                    <img class="mr-3 rounded-circle" width="50"
                                                        src="{{ asset('img/avatar/avatar-3.png') }}" alt="avatar">
                                                    <div class="media-body">
                                                        <h6 class="media-title"><a
                                                                href="{{ route('exercise.alquran') }}">{{ $latestExercise }}</a>
                                                        </h6>
                                                        <div class="text-small text-muted">
                                                            {{ $latestExercise->updated_at ? $latestExercise->updated_at->diffForHumans() : 'Baru saja' }}
                                                            <div class="bullet"></div>
                                                            <span class="text-primary">Latihan</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center"
                                                style="min-height: 150px;">
                                                <span class="text-muted text-center">Belum ada aktivitas</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endauth


        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
