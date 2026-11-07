@extends('layouts.app')

@section('title', 'Kelas Saya')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kelas Saya</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Semua Kelas</h2>
                <p class="section-lead">
                    Daftar semua kelas yang telah anda miliki.
                </p>


                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">

                        </div>
                    </div>
                </div>
        </section>

        @isset($enrolledCourses)
            <div class="row">
                @foreach ($enrolledCourses as $course)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="course-card">
                            <h3>{{ $course->title }}</h3>
                            <p>{!! nl2br(e(Str::limit($course->description, 150, ' ...', true))) !!}</p>
                            <ul class="course-features">
                                @foreach ($course->lessons->take(5) as $lesson)
                                    <li><i class="fas fa-circle-check"></i>{{ $lesson->title }}</li>
                                @endforeach
                            </ul>
                            <a href="{{ route('courses.show', $course->slug) }}"class="btn btn-primary btn-block">Lanjutkan Belajar</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <section id="kelas-kursus" class="py-5">
                <div class="text-center homepage-section-header">
                    <h2 class="homepage-section-title">Kelas masih kosong</h2>
                    <p class="homepage-section-lead">
                        Pilih program belajar yang sesuai dengan minat Anda.
                    </p>
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">Cari kelas</a>
                </div>
            </section>
        @endisset
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>


    <!-- Page Specific JS File -->
@endpush
