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
                <h1>Skema Nahwu</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Skema Nahwu</h2>
                <p class="section-lead">
                    Data skema nahwu
                </p>


                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            
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
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".confirm-delete").forEach(btn => {
                btn.addEventListener("click", function(e) {
                    let form = this.closest("form");

                    swal({
                            title: "Hapus user?",
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
