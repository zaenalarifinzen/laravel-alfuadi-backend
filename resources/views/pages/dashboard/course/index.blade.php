@extends('layouts.dashboard')

@section('title', 'Kelas Online')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kelas Online</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Kelas</div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title">Kelas Online</h2>
                <a href="{{ route('dashboard.courses.create') }}" class="btn btn-icon icon-left btn-primary">
                    <i class="fas fa-plus"></i>Kelas baru</a>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Semua kelas</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table-md table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Harga</th>
                                        <th>Materi</th>
                                        <th>Siswa</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                @foreach ($courses as $course)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('dashboard.courses.show', $course->id) }}" class="font-weight-600">{{ $course->title }}</a>
                                            <div class="table-links">
                                                <a href="{{ route('dashboard.courses.edit', $course->id) }}">Edit</a>
                                                <div class="bullet"></div>
                                                <a href="{{ route('dashboard.lessons.index', $course->id) }}">Kelola Materi</a>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($course->price, 0, ',', '.') }}</td>
                                        <td>{{ $course->lessons_count ? $course->lessons_count : 0 }}</td>
                                        <td>{{ $course->students_count ? $course->students_count : 0 }}</td>
                                        <td>
                                            @if ($course->status === 'published')
                                                <div class="badge badge-success">Published</div>
                                            @else
                                                <div class="badge badge-warning">Draft</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div class="">
                            <div class="p">Menampilkan {{ $courses->count() }} dari {{ $courses->count() }} hasil
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
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
                    <p class="text-danger mt-2 mb-0"><small><i class="fas fa-exclamation-triangle mr-1"></i> Data yang
                            dihapus tidak dapat dikembalikan.</small></p>
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
