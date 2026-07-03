@extends('layouts.app')

@section('title', 'Edit Kategori')

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
                <h1>Edit Kategori</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Kategori</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">

                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit Kategori</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        @include('layouts.alert')
                                    </div>
                                </div>

                                <form action="{{ route('kategori.update', $kategori) }}" method="POST" novalidate>
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label>Id Kategori</label>
                                        <input type="text"
                                            class="form-control @error('id') is-invalid @enderror"
                                            name="id" value="{{ $kategori->id }}" required readonly>
                                        @error('id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Order</label>
                                        <input type="number"
                                            class="form-control @error('order') is-invalid @enderror"
                                            name="order" value="{{ $kategori->order }}" required>
                                        @error('order')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Id Kalimat</label>
                                        <input type="number"
                                            class="form-control @error('id_kalimat') is-invalid @enderror"
                                            name="id_kalimat" value="{{ $kategori->id_kalimat }}" required>
                                        @error('id_kalimat')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Simbol</label>
                                        <input type="text" class="form-control arabic-text @error('simbol') is-invalid @enderror"
                                            name="simbol" value="{{ $kategori->simbol }}" required>
                                        @error('simbol')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Kategori Arabic</label>
                                        <input type="text" class="form-control arabic-text @error('kategori_ar') is-invalid @enderror"
                                            name="kategori_ar" value="{{ $kategori->kategori_ar }}" required>
                                        @error('kategori_ar')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Kategori Arabic Musyakal</label>
                                        <input type="text" class="form-control arabic-text @error('kategori_ar_musyakal') is-invalid @enderror"
                                            name="kategori_ar_musyakal" value="{{ $kategori->kategori_ar_musyakal }}" required>
                                        @error('kategori_ar_musyakal')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Kategori Indonesia</label>
                                        <input type="text" class="form-control @error('kategori_in') is-invalid @enderror"
                                            name="kategori_in" value="{{ $kategori->kategori_in }}" required>
                                        @error('kategori_in')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Hukum</label>
                                        <input type="text" class="form-control arabic-text @error('hukum') is-invalid @enderror"
                                            name="hukum" value="{{ $kategori->hukum }}" required>
                                        @error('hukum')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tanda Rofa</label>
                                        <input type="text" class="form-control arabic-text @error('rofa') is-invalid @enderror"
                                            name="rofa" value="{{ $kategori->rofa }}" required>
                                        @error('rofa')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tanda Nashob</label>
                                        <input type="text" class="form-control arabic-text @error('nashob') is-invalid @enderror"
                                            name="nashob" value="{{ $kategori->nashob }}" required>
                                        @error('nashob')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tanda Jar</label>
                                        <input type="text" class="form-control arabic-text @error('jar') is-invalid @enderror"
                                            name="jar" value="{{ $kategori->jar }}" required>
                                        @error('jar')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Tanda Jazm</label>
                                        <input type="text" class="form-control arabic-text @error('jazm') is-invalid @enderror"
                                            name="jazm" value="{{ $kategori->jazm }}" required>
                                        @error('jazm')
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
