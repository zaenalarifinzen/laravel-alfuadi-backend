@extends('layouts.app')

@section('title', 'Skema Nahwu')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kalimat</h1>
                <div class="section-header-button">
                    <a href="{{ route('kalimat.create') }}" class="btn btn-primary">Tambah</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Skema-Nahwu</a></div>
                    <div class="breadcrumb-item">Kalimat</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Kalimat</h2>
                <p class="section-lead">
                    You can manage all kalimat, such as editing, deleting and more.
                </p>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Kalimat</h4>
                            </div>
                            <div class="card-body">
                                <div class="float-left">
                                    <select class="form-control selectric">
                                        <option>Kalimat</option>
                                        <option>Kategori & Tanda</option>
                                        <option>Kedudukan & Irob</option>
                                    </select>
                                </div>
                                <div class="float-right">
                                    <form method="GET" action="{{ route('users.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search" name="name">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="clearfix mb-3"></div>

                                <div class="table-responsive">
                                    <table class="table-striped table">
                                        <tr>

                                            <th>Id</th>
                                            <th>Kalimat Arabic</th>
                                            <th>Kalimat Arabic Musyakal</th>
                                            <th>Kalimat Indonesia</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($kalimats as $kalimat)
                                            <tr>
                                                <td>{{ $kalimat->id }}
                                                </td>
                                                <td class="align-middle">
                                                    <div class="text-center arabic-text">{{ $kalimat->kalimat_ar }}</div>
                                                </td>
                                                <td class="text-center align-middle arabic-text">
                                                    {{ $kalimat->kalimat_ar_musyakal ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $kalimat->kalimat_in }}
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-left">
                                                        <a href='{{ route('kalimat.edit', $kalimat->id) }}'
                                                            class="btn btn-sm btn-info btn-icon">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>

                                                        <form action="{{ route('kalimat.destroy', $kalimat->id) }}"
                                                            method="POST" class="ml-2">
                                                            <input type="hidden" name="_method" value="DELETE" />
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}" />
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger btn-icon confirm-delete">
                                                                <i class="fas fa-times"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
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
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>


    <!-- Page Specific JS File -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".confirm-delete").forEach(btn => {
                btn.addEventListener("click", function(e) {
                    let form = this.closest("form");

                    swal({
                            title: "Hapus kalimat?",
                            text: "Data user akan dihapus dan mereka tidak bisa mengakses akunnya kembali",
                            icon: "warning",
                            buttons: {
                                cancel: {
                                    text: 'Batal',
                                    visible: true,
                                },
                                confirm: {
                                    text: 'Ya, hapus',
                                    visible: true,
                                    className: 'btn-danger'
                                }
                            },
                            dangerMode: true,
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                form.submit();
                            }
                        });
                });
            })
        });
    </script>
@endpush
