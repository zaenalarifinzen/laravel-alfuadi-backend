@extends('layouts.dashboard')

@section('title', $course->title ?? 'Detail Kursus')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Kursus</h1>
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>
                        {{ $course->title }}
                    </h2>
                    @if (strtolower($course->status) === 'published')
                        <span class="badge badge-success">Published</span>
                    @else
                        <span class="badge badge-warning">Draft</span>
                    @endif
                </div>

                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="card mb-4 overflow-hidden">
                            @if ($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                                    class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                            @else
                                <div class="bg-light text-center py-5 d-flex flex-column align-items-center justify-content-center"
                                    style="min-height: 250px;">
                                    <i class="fas fa-image fa-3x text-secondary mb-2"></i>
                                    <p class="text-muted mb-0 font-weight-600">Tidak ada thumbnail</p>
                                </div>
                            @endif
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>
                                    Deskripsi
                                </h5>
                            </div>
                            <div class="card-body">
                                @if ($course->description)
                                    <p class="text-dark mb-0">
                                        {!! nl2br(e($course->description)) !!}
                                    </p>
                                @else
                                    <p class="text-muted mb-0 font-italic">Belum ada deskripsi untuk kursus ini.</p>
                                @endif
                            </div>
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
                                        class="d-flex align-items-center py-2 border-bottom {{ $loop->last ? 'border-bottom-0' : '' }}">
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
                                <h5 class="card-title">
                                    Statistik
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row mb-4">
                                    <div class="col-6 pr-2 pb-2">
                                        <div class="rounded p-3 text-center h-100">
                                            <h3>
                                                {{ $course->enrollments_count ?? 0 }}
                                            </h3>
                                            <span class="text-muted small">Siswa</span>
                                        </div>
                                    </div>
                                    <div class="col-6 pl-2 pb-2">
                                        <div class="rounded p-3 text-center h-100">
                                            <h3>
                                                {{ $course->lessons_count ?? 0 }}
                                            </h3>
                                            <span class="text-muted small">Materi</span>
                                        </div>
                                    </div>
                                    <div class="col-6 pr-2 pt-2">
                                        <div class="bounded p-3 text-center h-100">
                                            <h3>
                                                {{ $course->quizzes_count ?? 0 }}
                                            </h3>
                                            <span class="text-muted small">Quiz</span>
                                        </div>
                                    </div>
                                    <div class="col-6 pl-2 pt-2">
                                        <div class="rounded p-3 text-center h-100">
                                            <h3>
                                                {{ number_format($course->reviews_avg_rating ?? 0, 1) }}
                                            </h3>
                                            <span class="text-muted small">Rating</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="d-flex flex-column">
                                    <a href="{{ route('dashboard.courses.edit', $course->id) }}"
                                        class="btn btn-outline-primary mb-2">Edit Kursus
                                    </a>
                                    <a href="{{ route('dashboard.lessons.index', $course->id) }}"
                                        class="btn btn-outline-primary mb-2">Kelola Materi
                                    </a>
                                    <a href="{{ route('dashboard.courses.students', $course->id) }}"
                                        class="btn btn-outline-primary mb-2">Lihat Siswa
                                    </a>
                                    <a href="{{ route('dashboard.courses.reviews', $course->id) }}"
                                        class="btn btn-outline-primary mb-2">Lihat Review
                                    </a>
                                    <a href="{{ route('dashboard.courses.destroy', $course->id) }}"
                                        class="btn btn-outline-danger mt-2">Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
