@extends('layouts.dashboard')

@section('title', 'Materi baru')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Materi baru</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.courses.index') }}">Kelas</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.courses.index') }}">{{ $course->title }}</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.lessons.index', $course->id ) }}">Materi</a></div>
                    <div class="breadcrumb-item">Create</div>
                </div>
            </div>

            <div class="section-body">

                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $course->title }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        @include('layouts.alert')
                                    </div>
                                </div>

                                <form action="{{ route('dashboard.lessons.store', $course->id) }}" method="POST" novalidate>
                                    @csrf
                                    <div class="form-group">
                                        <label>Judul Materi</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Isi Materi</label>
                                        <textarea class="summernote @error('content_markdown') is-invalid @enderror" name="content_markdown" >{{ old('content_markdown') }}</textarea>
                                        @error('content_markdown')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Video URL</label>
                                        <input type="text" class="form-control @error('video_url') is-invalid @enderror"
                                            name="video_url" value="{{ old('video_url') }}" required>
                                        @error('video_url')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Urutan</label>
                                        <input type="text" class="form-control @error('display_order') is-invalid @enderror"
                                            name="display_order" value="{{ old('display_order') }}" required>
                                        @error('display_order')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                            </div>
                            <div class="card-footer text-left">
                                <button class="btn btn-primary">Simpan</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>

    <!-- Page Specific JS File -->
@endpush
