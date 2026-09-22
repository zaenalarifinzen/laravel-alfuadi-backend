@extends('layouts.dashboard')

@section('title', 'Kelola Level')

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
                <h1>Kelola Level</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Level</div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">Level Latihan</h2>
                <a href="{{ route('dashboard.exercise-levels.create') }}" class="btn btn-icon icon-left btn-primary">
                    <i class="fas fa-plus"></i>Tambah Level</a>
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
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Jumlah Soal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @foreach ($levels as $level)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="btn-group mb-2">
                                                <a href="#" class="font-weight-600" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    {{ $level->name }}</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('dashboard.exercise-levels.edit', $level->id) }}">Edit</a>
                                                    <div class="dropdown-divider"></div>
                                                    @if ($level->is_active)
                                                        <a href="#" class="dropdown-item"
                                                            onclick="event.preventDefault(); document.getElementById('deactivate-form-{{ $level->id }}').submit();">
                                                            Nonaktifkan
                                                        </a>

                                                        <form id="deactivate-form-{{ $level->id }}"
                                                            action="{{ route('dashboard.exercise-level.deactivate', $level->id) }}"
                                                            method="POST" class="d-none">
                                                            @csrf
                                                        </form>
                                                    @else
                                                        <a href="#" class="dropdown-item"
                                                            onclick="event.preventDefault(); document.getElementById('activate-form-{{ $level->id }}').submit();">
                                                            Aktifkan
                                                        </a>

                                                        <form id="activate-form-{{ $level->id }}"
                                                            action="{{ route('dashboard.exercise-level.activate', $level->id) }}"
                                                            method="POST" class="d-none">
                                                            @csrf
                                                        </form>
                                                    @endif
                                                    <a href="#" class="dropdown-item text-danger"
                                                        data-toggle="modal"
                                                        data-target="#deleteModal"
                                                        data-action="{{ route('dashboard.exercise-levels.destroy', $level->id) }}"
                                                        data-title="{{ $level->name }}">
                                                        Hapus
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $level->description }}</td>
                                        <td>{{ $level->exercises_count }}</td>
                                        <td>
                                            @if ($level->is_active)
                                                <div class="badge badge-success">Aktif</div>
                                            @else
                                                <div class="badge badge-warning">Tidak Aktif</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="">
                            <div class="p">Menampilkan {{ $levels->count() }} dari {{ $levels->count() }} hasil</div>
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

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Apakah Anda yakin ingin menghapus level <strong id="delete-level-title"></strong>?</p>
                    <p class="text-danger mt-2 mb-0"><small><i class="fas fa-exclamation-triangle mr-1"></i> Data yang dihapus tidak dapat dikembalikan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form id="delete-modal-form" action="" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-shadow">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var action = button.data('action');
                var title = button.data('title');
                var modal = $(this);

                modal.find('#delete-modal-form').attr('action', action);
                modal.find('#delete-level-title').text(title ? `"${title}"` : 'data ini');
            });
        });
    </script>
@endpush
