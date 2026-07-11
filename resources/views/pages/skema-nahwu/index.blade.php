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
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Skema-Nahwu</a></div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Skema Nahwu</h2>
                <p class="section-lead">
                    You can manage all nahwu scheme, such as editing, deleting and more.
                </p>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{ session('activeTab') == 'kalimat' || !session('activeTab') ? 'active' : '' }}"
                                            id="kalimat-tab" data-toggle="tab" href="#kalimat" role="tab"
                                            aria-controls="kalimat" aria-selected="true">Kalimat</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ session('activeTab') == 'kategori' ? 'active' : '' }}"
                                            id="kategori-tab" data-toggle="tab" href="#kategori" role="tab"
                                            aria-controls="kategori" aria-selected="false">Kategori</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ session('activeTab') == 'kedudukan' ? 'active' : '' }}"
                                            id="kedudukan-tab" data-toggle="tab" href="#kedudukan" role="tab"
                                            aria-controls="kedudukan" aria-selected="false">Kedudukan</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade {{ session('activeTab') == 'kalimat' || !session('activeTab') ? 'show active' : '' }}"
                                        id="kalimat" role="tabpanel" aria-labelledby="kalimat-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>
                                                    <a href="{{ route('skema-nahwu.kalimat.create') }}"
                                                        class="btn btn-primary">Tambah</a>
                                                </h4>
                                                <div class="card-header-form">
                                                    <form>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="Cari"
                                                                id="search-input">
                                                            <div class="input-group-btn">
                                                                {{-- make this button in not clickable --}}
                                                                <button class="btn btn-primary"><i
                                                                        class="fas fa-search"></i></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table-striped table">
                                                        <thead>
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>Kalimat Arabic</th>
                                                                <th>Kalimat Arabic Musyakal</th>
                                                                <th>Kalimat Indonesia</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="myTable">
                                                            @foreach ($kalimats as $kalimat)
                                                                <tr>
                                                                    <td>{{ $kalimat->id }}
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <div class="text-center arabic-text">
                                                                            {{ $kalimat->kalimat_ar }}
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center align-middle arabic-text">
                                                                        {{ $kalimat->kalimat_ar_musyakal ?? '' }}
                                                                    </td>
                                                                    <td class="label_in">
                                                                        {{ $kalimat->kalimat_in }}
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex justify-content-left">
                                                                            <a href='{{ route('skema-nahwu.kalimat.edit', $kalimat->id) }}'
                                                                                class="btn btn-sm btn-info btn-icon">
                                                                                <i class="fas fa-edit"></i>
                                                                                Edit
                                                                            </a>

                                                                            <form
                                                                                action="{{ route('skema-nahwu.kalimat.destroy', $kalimat->id) }}"
                                                                                method="POST" class="ml-2">
                                                                                <input type="hidden" name="_method"
                                                                                    value="DELETE" />
                                                                                <input type="hidden" name="_token"
                                                                                    value="{{ csrf_token() }}" />
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-danger btn-icon confirm-delete"
                                                                                    data-delete-title="Kalimat">
                                                                                    <i class="fas fa-times"></i> Hapus
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade {{ session('activeTab') == 'kategori' ? 'show active' : '' }}"
                                        id="kategori" role="tabpanel" aria-labelledby="kategori-tab">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="float-left">
                                                    <h4>Kategori</h4>
                                                </div>
                                                <div class="float-right">
                                                    <a href="{{ route('skema-nahwu.kategori.create') }}"
                                                        class="btn btn-primary">Tambah</a>
                                                </div>

                                                <div class="clearfix mb-3"></div>

                                                <div class="table-responsive">
                                                    <table class="table-striped table">
                                                        <tr>

                                                            <th>Id</th>
                                                            <th>Simbol</th>
                                                            <th>Kategori Arabic</th>
                                                            <th>Kategori Arabic Musyakal</th>
                                                            <th>Kategori Indonesia</th>
                                                            <th>Hukum</th>
                                                            <th>Rofa</th>
                                                            <th>Nashob</th>
                                                            <th>Jar</th>
                                                            <th>Jazm</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        @foreach ($kategoris as $kategori)
                                                            <tr>
                                                                <td>{{ $kategori->id }}
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="text-center arabic-text">
                                                                        {{ $kategori->simbol }}
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="text-center arabic-text">
                                                                        {{ $kategori->kategori_ar }}</div>
                                                                </td>
                                                                <td class="text-center align-middle arabic-text">
                                                                    {{ $kategori->kategori_ar_musyakal ?? '' }}
                                                                </td>
                                                                <td class="text-center align-middle arabic-text">
                                                                    {{ $kategori->hukum ?? '' }}
                                                                </td>
                                                                <td class="text-center align-middle arabic-text">
                                                                    {{ $kategori->rofa ?? '' }}
                                                                </td>
                                                                <td class="text-center align-middle arabic-text">
                                                                    {{ $kategori->nashob ?? '' }}
                                                                </td>
                                                                <td class="text-center align-middle arabic-text">
                                                                    {{ $kategori->jar ?? '' }}
                                                                </td>
                                                                <td class="text-center align-middle arabic-text">
                                                                    {{ $kategori->jazm ?? '' }}
                                                                </td>
                                                                <td class="label_in">
                                                                    {{ $kategori->kategori_in }}
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex justify-content-left">
                                                                        <a href='{{ route('skema-nahwu.kategori.edit', $kategori->id) }}'
                                                                            class="btn btn-sm btn-info btn-icon">
                                                                            <i class="fas fa-edit"></i>
                                                                            Edit
                                                                        </a>

                                                                        <form
                                                                            action="{{ route('skema-nahwu.kategori.destroy', $kategori->id) }}"
                                                                            method="POST" class="ml-2">
                                                                            <input type="hidden" name="_method"
                                                                                value="DELETE" />
                                                                            <input type="hidden" name="_token"
                                                                                value="{{ csrf_token() }}" />
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-danger btn-icon confirm-delete"
                                                                                data-delete-title="Kategori">
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
                                    <div class="tab-pane fade {{ session('activeTab') == 'kedudukan' ? 'show active' : '' }}"
                                        id="kedudukan" role="tabpanel" aria-labelledby="kedudukan-tab">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="float-left">
                                                    <h4>Kedudukan</h4>
                                                </div>
                                                <div class="float-right">
                                                    <a href="{{ route('skema-nahwu.kedudukan.create') }}"
                                                        class="btn btn-primary">Tambah</a>
                                                </div>

                                                <div class="clearfix mb-3"></div>
                                                <div class="table-responsive">
                                                    <table class="table-striped table">
                                                        <tr>

                                                            <th>Id</th>
                                                            <th>Simbol</th>
                                                            <th>Kedudukan Arabic</th>
                                                            <th>Kedudukan Arabic Musyakal</th>
                                                            <th>Kedudukan Indonesia</th>
                                                            <th>Irob</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        @foreach ($kedudukans as $kedudukan)
                                                            <tr>
                                                                <td>{{ $kedudukan->id }}
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="text-center arabic-text">
                                                                        {{ $kedudukan->simbol }}
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="text-center arabic-text">
                                                                        {{ $kedudukan->kedudukan_ar }}</div>
                                                                </td>
                                                                <td class="text-center align-middle arabic-text">
                                                                    {{ $kedudukan->kedudukan_ar_musyakal ?? '' }}
                                                                </td>
                                                                <td class="align-middle">
                                                                    <div class="text-center arabic-text  label_in">
                                                                        {{ $kedudukan->kedudukan_in }}</div>
                                                                </td>
                                                                <td class="text-center align-middle arabic-text">
                                                                    {{ $kedudukan->irob ?? '' }}
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex justify-content-left">
                                                                        <a href='{{ route('skema-nahwu.kedudukan.edit', $kedudukan->id) }}'
                                                                            class="btn btn-sm btn-info btn-icon">
                                                                            <i class="fas fa-edit"></i>
                                                                            Edit
                                                                        </a>

                                                                        <form
                                                                            action="{{ route('skema-nahwu.kedudukan.destroy', $kedudukan->id) }}"
                                                                            method="POST" class="ml-2">
                                                                            <input type="hidden" name="_method"
                                                                                value="DELETE" />
                                                                            <input type="hidden" name="_token"
                                                                                value="{{ csrf_token() }}" />
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-danger btn-icon confirm-delete"
                                                                                data-delete-title="Kedudukan">
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
        $(document).ready(function() {
            $("#search-input").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
        
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".confirm-delete").forEach(btn => {
                btn.addEventListener("click", function(e) {
                    let form = this.closest("form");
                    let deleteTitle = this.dataset.deleteTitle.toLowerCase() || 'data';
                    let label = this.closest('tr').querySelector('.label_in').textContent.trim() ||
                        'data';

                    swal({
                            title: `Hapus ${deleteTitle}?`,
                            text: `${label} akan dihapus dan tidak dapat dikembalikan.`,
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
