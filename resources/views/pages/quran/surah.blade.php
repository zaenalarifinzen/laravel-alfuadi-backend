@extends('layouts.quran')

@section('title', 'Surah Name')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">

            <div class="section-header ">
                <h1>{{ $surah->name }}</h1>
            </div>


            <div class="section-body">
                <div class="row ">
                    <div class="d-flex flex-column gap-3">
                        @foreach ($verses as $verse)
                            <div class="d-flex align-items-start border-bottom py-4 px-3 px-md-4" id="{{ $verse->id }}">
                                <!-- Tombol titik tiga (dropdown) -->
                                <div class="dropdown me-2 me-sm-3">
                                    <button
                                        class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center text-primary"
                                        style="width: 32px; height: 32px;" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false" title="More">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Bookmark</a></li>
                                        <li><a class="dropdown-item" href="#">Bagikan</a></li>
                                        <li><a class="dropdown-item" href="#">Salin teks</a></li>
                                    </ul>
                                </div>

                                <!-- Konten ayat -->
                                <div class="flex-grow-1 d-flex flex-column gap-3">
                                    <p class="arabic-text ar-title fs-1 text-end lh-lg mb-0" dir="rtl"
                                        style="text-align: right;">
                                        {{ $verse->text }}
                                        <span
                                            class="text-primary"> ﴿ {{ $verse->number_arabic ?? $loop->iteration }} ﴾ </span>
                                    </p>
                                    <span class="d-block fs-6">
                                        {{ $verse->translation_indo }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
@endpush
