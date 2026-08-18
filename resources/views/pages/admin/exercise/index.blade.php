@extends('layouts.app')

@section('title', 'Soal Latihan')

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
                <h1>Soal Latihan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('exercise-level.index') }}">Admin</a></div>
                    <div class="breadcrumb-item">Soal Latihan</div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">Soal Latihan</h2>
                <a href="{{ route('admin.exercises.create') }}" class="btn btn-icon icon-left btn-primary">
                    <i class="fas fa-plus"></i>
                    Tambah Soal
                </a>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Level Latihan</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table-md table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Soal</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                @foreach ($exercises as $exercise)
                                    <tr>
                                        <td>{{ $exercise->display_order }}</td>
                                        <td>{{ $exercise->title }}</td>
                                        <td>{{ $exercise->description }}</td>
                                        <td>{{ $exercise->exerciseLevel->name }}</td>
                                        <td>
                                            @if ($exercise->is_active)
                                                <div class="badge badge-success">Aktif</div>
                                            @else
                                                <div class="badge badge-warning">Tidak Aktif</div>
                                            @endif
                                        </td>
                                        <td><a href="{{ route('admin.exercises.edit', $exercise->id) }}"
                                                class="btn btn-primary">Edit</a></td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="">
                            {{-- <div class="p">Menampilkan {{ $levels->count() }} dari {{ $levels->count() }} hasil</div> --}}
                        </div>
                        {{-- <nav class="d-inline-block">
                            <ul class="pagination mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1"><i
                                            class="fas fa-chevron-left"></i></a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1<span
                                            class="sr-only">(current)</span></a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                                </li>
                            </ul>
                        </nav> --}}
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
