@extends('layouts.app')

@section('title', 'Tambah Kedudukan')

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
                <h1>Tambah Kedudukan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Kedudukan</a></div>
                    <div class="breadcrumb-item">Create</div>
                </div>
            </div>

            <div class="section-body">

                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Kedudukan baru</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        @include('layouts.alert')
                                    </div>
                                </div>

                                <form action="{{ route('skema-nahwu.kedudukan.store') }}" method="POST" novalidate>
                                    @csrf
                                    <div class="form-group">
                                        <label>Id Kedudukan</label>
                                        <input type="text"
                                            class="form-control @error('id') is-invalid @enderror"
                                            name="id" value="{{ old('id') }}" required>
                                        @error('id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Order</label>
                                        <input type="number"
                                            class="form-control @error('order') is-invalid @enderror"
                                            name="order" value="{{ old('order') }}" required>
                                        @error('order')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Id Kalimat</label>
                                        <input type="number"
                                            class="form-control @error('id_kalimat') is-invalid @enderror"
                                            name="id_kalimat" value="{{ old('id_kalimat') }}" required>
                                        @error('id_kalimat')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Simbol</label>
                                        <input type="text" class="form-control arabic-text @error('simbol') is-invalid @enderror"
                                            name="simbol" value="{{ old('simbol') }}" required>
                                        @error('simbol')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Kedudukan Arabic</label>
                                        <input type="text" class="form-control arabic-text @error('kedudukan_ar') is-invalid @enderror"
                                            name="kedudukan_ar" value="{{ old('kedudukan_ar') }}" required>
                                        @error('kedudukan_ar')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Kedudukan Arabic Musyakal</label>
                                        <input type="text" class="form-control arabic-text @error('kedudukan_ar_musyakal') is-invalid @enderror"
                                            name="kedudukan_ar_musyakal" value="{{ old('kedudukan_ar_musyakal') }}" required>
                                        @error('kedudukan_ar_musyakal')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Kedudukan Indonesia</label>
                                        <input type="text" class="form-control @error('kedudukan_in') is-invalid @enderror"
                                            name="kedudukan_in" value="{{ old('kedudukan_in') }}" required>
                                        @error('kedudukan_in')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Irob</label>
                                        <input type="text" class="form-control arabic-text @error('irob') is-invalid @enderror"
                                            name="irob" value="{{ old('irob') }}" required>
                                        @error('irob')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                            </div>
                            <div class="card-footer text-left">
                                <button class="btn btn-primary">Submit</button>
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
