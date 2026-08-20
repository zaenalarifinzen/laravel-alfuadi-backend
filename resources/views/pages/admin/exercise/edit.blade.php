@extends('layouts.app')

@section('title', 'Edit soal')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Soal</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('exercise-level.index') }}">Latihan</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">

                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Soal baru</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        @include('layouts.alert')
                                    </div>
                                </div>

                                <form action="{{ route('admin.exercises.update', $exercise->id) }}" method="POST" novalidate>
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label>Level</label>
                                        <select class="form-control @error('level') is-invalid @enderror" name="level" required>
                                            <option value="">Pilih Level</option>
                                            @foreach ($levels as $level)
                                                <option value="{{ $level->level_number }}" {{ $exercise->level == $level->level_number ? 'selected' : '' }}>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('level')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Judul</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" value="{{ $exercise->title }}" required>
                                        @error('title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Isi</label>
                                        {{-- required description --}} 
                                        <textarea class="form-control arabic-text @error('description') is-invalid @enderror"
                                        name="description" data-height="100" required >{{ $exercise->description }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Penjelasan</label>
                                        <textarea class="form-control @error('explanation') is-invalid @enderror"
                                        name="explanation" data-height="100">{{ $exercise->explanation }}</textarea>
                                        @error('explanation')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary">Perbarui</button>
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

    <!-- Page Specific JS File -->
@endpush
