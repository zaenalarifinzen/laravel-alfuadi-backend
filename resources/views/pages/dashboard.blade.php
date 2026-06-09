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

                    @if(auth()->user()->roles === 'administrator' || auth()->user()->roles === 'operator')
                        <h2 class="section-title">Tugas</h2>
                        <p class="section-lead">
                            Lihat pekerjaan terakhir anda
                        </p>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <a href="">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-warning">
                                            <i class="far fa-newspaper"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Input i'rob</h4>
                                            </div>
                                            <div class="card-body">
                                                Al-Baqarah ayat 55
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif

                    <h2 class="section-title">Latihan</h2>
                    <p class="section-lead">
                        Lihat latihan terakhir anda
                    </p>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <a href="">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-primary">
                                        <i class="far fa-newspaper"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Input i'rob</h4>
                                        </div>
                                        <div class="card-body">
                                            Al-Baqarah ayat 55
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endauth


        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index-0.js') }}"></script>
@endpush
