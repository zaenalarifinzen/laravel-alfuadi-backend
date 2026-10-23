@extends('layouts.app')

@section('title', $course->title ?? 'Detail Kelas')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Kelas</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('dashboard.courses.index') }}">Kelas</a></div>
                    <div class="breadcrumb-item">{{ $course->title }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>
                                {{ $course->title }}
                            </h2>
                        </div>

                        <div class="mb-4">
                            @if ($course->description)
                                <p class="text-dark mb-0">
                                    {!! nl2br(e($course->description)) !!}
                                </p>
                            @else
                                <p class="text-muted mb-0 font-italic">Belum ada deskripsi untuk kelas ini.</p>
                            @endif
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5>
                                    Materi ({{ $course->lessons_count ?? $course->lessons->count() }})
                                </h5>
                            </div>
                            <div class="card-body">
                                @forelse ($course->lessons as $lesson)
                                    <div
                                        class="d-flex align-items-center py-2">
                                        <span class="text-muted mr-4 font-weight-600">
                                            {{ $loop->iteration }}
                                        </span>
                                        <span class="font-weight-600">
                                            {{ $lesson->title }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="px-4 py-4 text-center text-muted">
                                        <span>Belum ada materi untuk kelas ini.</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    Review ({{ $course->reviews_count ?? $course->reviews->count() }})
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                @forelse ($course->reviews as $review)
                                    <div class="px-4 py-3 border-bottom {{ $loop->last ? 'border-bottom-0' : '' }}">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="font-weight-bold text-dark mr-2">
                                                {{ $review->user->name ?? 'Siswa Demo' }}
                                            </span>
                                            <div class="d-inline-flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-dark mb-0">{{ $review->comment }}</p>
                                    </div>
                                @empty
                                    <div class="px-4 py-4 text-center text-muted">
                                        <span>Belum ada review untuk kelas ini.</span>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                @if ($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                                        class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                                @else
                                    <div class="bg-light text-center py-5 d-flex flex-column align-items-center justify-content-center my-2 w-100"
                                        style="min-height: 250px;">
                                        <i class="fas fa-image fa-3x text-secondary mb-2"></i>
                                        <p class="text-muted mb-0 font-weight-600">Tidak ada thumbnail</p>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h3 class="text-primary">Rp {{ number_format($course->price, 0, ',', '.') }}</h3>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <p class="mb-0"><i class="fas fa-infinity mr-3"></i> Akses selamanya</p>
                                    <p class="mb-0"><i class="fas fa-circle-check mr-3"></i> Materi lengkap & terstruktur</p>
                                    <p class="mb-0"><i class="fa-brands fa-youtube mr-3"></i> Video penjelasan</p>
                                    <p class="mb-0"><i class="fas fa-video mr-3"></i> Sesi Live</p>
                                    <p class="mb-0"><i class="fas fa-medal mr-3"></i> Sertifikat</p>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex flex-column mb-4">
                                    @guest
                                        <a href="{{ route('courses.checkout', $course->slug) }}" class="btn btn-primary btn-lg mb-2">
                                            <i class="fas fa-shopping-cart mr-1"></i> Beli Kelas
                                        </a>
                                    @else
                                        @if(auth()->user()->isEnrolled($course))
                                            <a href="{{ route('learn', $course->slug) }}" class="btn btn-success btn-lg mb-2">
                                                <i class="fas fa-play-circle mr-1"></i> Lanjut Belajar
                                            </a>
                                        @else
                                            <a href="{{ route('courses.checkout', $course->slug) }}" class="btn btn-primary btn-lg mb-2">
                                                <i class="fas fa-shopping-cart mr-1"></i> Beli Kelas
                                            </a>
                                        @endif
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection