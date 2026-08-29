@extends('layouts.app')

@section('title', 'Soal Latihan')

@push('style')
    <!-- CSS Libraries -->
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
                        <div class="selectric-wrapper selectric-form-control selectric-selectric selectric-below">
                            <div class="selectric-hide-select">
                                <select class="form-control selectric" tabindex="-1">
                                    <option>Semua Level</option>
                                    <option>Pemula</option>
                                    <option>Menengah</option>
                                    <option>Lanjutan</option>
                                    <option>Al-Quran</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table-md table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Level</th>
                                        <th>Urutan</th>
                                        <th>Judul</th>
                                        <th>Soal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @foreach ($exercises as $exercise)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $exercise->exerciseLevel->name }}</td>
                                        <td>{{ $exercise->display_order }}</td>
                                        <td>
                                            <div class="btn-group mb-2">
                                                <a href="#" class="font-weight-600" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    {{ $exercise->title }}</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.exercises.edit', $exercise->id) }}">Edit</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.exercises.grouping', $exercise->id) }}">Grouping</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.exercises.irob', $exercise->id) }}">Input
                                                        I'rob</a>
                                                    @if ($exercise->is_active)
                                                        <a href="#" class="dropdown-item"
                                                            onclick="event.preventDefault(); document.getElementById('deactivate-form-{{ $exercise->id }}').submit();">
                                                            Nonaktifkan
                                                        </a>

                                                        <form id="deactivate-form-{{ $exercise->id }}"
                                                            action="{{ route('admin.exercises.deactivate', $exercise->id) }}"
                                                            method="POST" class="d-none">
                                                            @csrf
                                                        </form>
                                                    @else
                                                        <a href="#" class="dropdown-item"
                                                            onclick="event.preventDefault(); document.getElementById('activate-form-{{ $exercise->id }}').submit();">
                                                            Aktifkan
                                                        </a>

                                                        <form id="activate-form-{{ $exercise->id }}"
                                                            action="{{ route('admin.exercises.activate', $exercise->id) }}"
                                                            method="POST" class="d-none">
                                                            @csrf
                                                        </form>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                    <a href="#" class="dropdown-item text-danger"
                                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{ $exercise->id }}').submit();">
                                                        Hapus
                                                    </a>

                                                    <form id="delete-form-{{ $exercise->id }}"
                                                        action="{{ route('admin.exercises.destroy', $exercise->id) }}"
                                                        method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="arabic-text ar-symbol">{{ $exercise->description }}</td>
                                        <td>
                                            @if ($exercise->is_active)
                                                <div class="badge badge-success">Aktif</div>
                                            @else
                                                <div class="badge badge-warning">Nonaktif</div>
                                            @endif
                                        </td>
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
