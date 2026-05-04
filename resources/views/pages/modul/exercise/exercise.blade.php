@extends('layouts.app')

@section('title', 'Latihan analisa')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Latihan analisa</h1>

                <div class="float-right">
                    <form method="GET" action="{{ route('wordgroups.grouping') }}" id="filter-form" class="mb-0">
                        <div class="input-group">
                            <select class="form-control {{-- select2 --}} form-control-sm" name="surah-option"
                                id="surah-option"
                                style="flex: 3; border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem;"
                                required>
                                <option value="">Pilih Soal</option>
                            </select>
                            <input type="number" class="form-control" placeholder="Ayat" name="verse-option"
                                id="verse-option" value="" style="flex: 1;" required>

                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Buka</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <input type="hidden" id="surah-id" value="">
                    <input type="hidden" id="verse-number" value="">
                    <input type="hidden" id="verse-id" value="">

                    <div class="card-header" id="word">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h4 id="current-verse-label">Nama Soal</h4>
                        </div>
                    </div>

                    <div class="card-body position-relative">
                        <button id="btn-next-slide" class="slider-nav-btn prev">
                            <i class="fa fa-chevron-left"></i>
                        </button>

                        <div class="owl-carousel owl-theme slider" id="slider-rtl">
                            <div>
                                <h4 class="arabic-text ar-title word-group text-center" wg-id="#">
                                    Pilih soal terlebih dahulu
                                </h4>
                            </div>
                        </div>

                        <button id="btn-prev-slide" class="slider-nav-btn next">
                            <i class="fa fa-chevron-right"></i>
                        </button>

                        <div class="editor-wordgroup" style="padding-top: 20px" hidden>
                            <span>Editor grouping : </span>
                            <a href="#" class="font-weight-600">-</a>
                        </div>
                    </div>

                </div>



                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Input</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false">Detail</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <h4 class="mb-0">Tabel analisa</h4>
                                            <button class="btn btn-icon icon-left btn-primary btn-lg" id="btn-add-word" hidden>
                                                <i class="fa-solid fa-plus"></i> Tambah
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-responsive" style="direction: rtl;">
                                        <div class="table-sm">
                                            <div class="">
                                                <table class="table-striped table" id="sortable-table">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Opsi</th>
                                                            <th>Lafadz</th>
                                                            <th>Kalimat</th>
                                                            <th>Hukum</th>
                                                            <th>Kategori</th>
                                                            <th>Kedudukan</th>
                                                            <th>I'rob</th>
                                                            <th>Tanda</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <h4 class="mb-0">Detail Kalimat</h4>
                                        </div>
                                    </div>
                                    <div class="table-sm">
                                        <table class="table-striped table" id="detail-kalimat-table">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Irob</th>
                                                    <th style="width:110px;">Lafadz</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="editor-kalimat" style="padding-top: 20px" hidden>
                            <span>Editor kalimat : </span>
                            {{-- show editor name based on this words --}}
                            <a href="#" class="font-weight-600" id="word-editor-info">-</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-lg" id="btn-next-verse">
                            <i class="ion-chevron-left" data-pack="default" data-tags="arrow, right"></i></button>
                        <button type="button" class="btn btn-outline-primary btn-lg mr-2" id="btn-prev-verse"><i
                                class="ion-chevron-right" data-pack="default" data-tags="arrow, left"></i></button>
                    </div>
                    <div hidden>
                        <button class="btn btn-icon icon-left btn-success btn-lg" id="btn-save-all"
                            style="display: none;">Simpan</button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Add Word-->
    <div class="modal fade" id="modal-add-word" tabindex="-1" role="dialog" aria-labelledby="modalAddWordLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title " id="form-add-word-label">Tambah Kalimat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="form-add-word">
                    <div class="modal-body">
                        <input type="hidden" id="input-id">
                        <input type="hidden" id="input-order-number">
                        <div class="form-row">
                            <div class="form-group col-12">
                                {{-- <label for="input-lafadz">Lafadz</label> --}}
                                <input type="text" class="form-control arabic-text ar-title input-big text-center"
                                    id="input-lafadz" placeholder="لفظ">
                            </div>
                            <div class="form-group col-12">
                                {{-- <label for="input-translation">Terjemah</label> --}}
                                <input type="text" class="form-control text-center" id="input-translation"
                                    placeholder="terjemah">
                            </div>
                        </div>
                        {{-- additional fields --}}
                        <div id="additional-fields" style="display: none;">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="input-kalimat">Kalimat</label>
                                    <select id="input-kalimat" class="custom-dropdown" name="kalimat" required></select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="input-kategori">Kategori</label>
                                    <select id="input-kategori" class="custom-dropdown" name="kategori">
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="input-kedudukan">Kedudukan</label>
                                    <select id="input-kedudukan" class="custom-dropdown" name="kedudukan">
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="input-hukum">Hukum</label>
                                    <select id="input-hukum" class="custom-dropdown" name="hukum">
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="input-irob">I'rob</label>
                                    <select id="input-irob" class="custom-dropdown" name="irob">
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="input-tanda">Tanda i'rob</label>
                                    <select id="input-tanda" class="custom-dropdown" name="tanda">
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="input-simbol">Simbol</label>
                                    <select id="input-simbol" class="custom-dropdown" name="simbol">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-submit">Tambahkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Restore Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-restore">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lanjutkan progres sebelumnya?</h5>
                </div>
                <div class="modal-body">
                    <p>Terakhir di edit : <span class="text-primary" id="last-location-label"></span></p>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" id="btn-restore-cancel">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btn-restore-continue">Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script>
        window.WORDS_SYNC_URL = "{{ route('words.sync') }}";
        window.WORDGROUP_GET_URL = "{{ route('wordgroups.get', ['id' => ':id']) }}";
        window.CSRF_TOKEN = "{{ csrf_token() }}";
        window.PAGE_TYPE = "exercise";
    </script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>
    <script src="{{ asset('js/page/words/storage-helper.js') }}?v=1.1.7"></script>
    <script src="{{ asset('js/page/words/word-crud.js') }}?v=1.1.7"></script>
    <script src="{{ asset('js/page/words/words-page.js') }}?v=1.1.7"></script>
    <script src="{{ asset('js/page/words/nahwu-form.js') }}?v=1.1.7"></script>
@endpush
