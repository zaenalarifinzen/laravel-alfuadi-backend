@extends('layouts.app')

@section('title', 'Input Irob Test')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}"> --}}


    <style>
        #slider-rtl {
            direction: rtl;
        }

        #slider-rtl .owl-item .owl-stage-outer {
            direction: rtl;
            overflow: visible !important;
        }

        .td {
            align-items: center;
        }

        .ar-subtitle {
            font-size: 28px !important;
        }

        .input-big {
            height: 80px !important;
            padding-top: 10px;
        }

        #input-lafadz::placeholder {
            color: #dddddd;
        }

        #input-translation::placeholder {
            color: #dddddd;
        }

        .slider-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 50;

            background: rgba(255, 255, 255, 0.85);
            color: #008f85;
            border: none;
            outline: none;

            width: 50px;
            height: 50px;

            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 22px;
            cursor: pointer;
        }

        .slider-nav-btn:hover {
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        }

        .slider-nav-btn.prev {
            left: 10px;
        }

        .slider-nav-btn.next {
            right: 10px;
        }

        .slider-nav-btn:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .owl-item {
            padding: 25px 0 !important;
        }

        .action-buttons button {
            margin: 0 2px;
        }

        /* Custom Dropdown */
        .custom-dropdown {
            position: relative;
        }

        .select-btn,
        .options li {
            display: flex;
            cursor: pointer;
            align-items: center;
        }

        .select-btn {
            height: 50px;
            padding: 0 20px;
            border-radius: 7px;
            border: 1px solid #EAECFC;
            background: #ffffff;
            justify-content: space-between;
        }

        .select-btn .ar {
            font-size: 18px;
        }

        .select-btn i {
            transition: transform 0.2s linear;
        }

        .custom-dropdown.active .select-btn {
            border-color: #b7d8d5;
        }

        .custom-dropdown.active .select-btn i {
            transform: rotate(-180deg);
        }

        .content {
            display: none;
            background: #fff;
            margin-top: 5px;
            padding: 20px;
            border-radius: 7px;
            z-index: 1;
        }

        .custom-dropdown .content {
            width: 100%;
            box-sizing: border-box;
        }

        .custom-dropdown.active .select-btn {
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .custom-dropdown.active .content {
            display: block;
            position: absolute;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .custom-dropdown.disabled .select-btn {
            background: #f5f5f5;
            border: 1px solid #fff;
            color: #f5f5f5;
            cursor: not-allowed;
        }

        .custom-dropdown.invalid .select-btn {
            border: 1px solid #e74c3c;
        }

        .custom-dropdown.invalid .select-btn span {
            color: #e74c3c;
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 4px;
        }

        .content .search {
            position: relative;
        }

        .search i {
            position: absolute;
            left: 10px;
            font-size: 15px;
            color: #999;
            line-height: 40px;
        }

        .search input {
            height: 40px;
            width: 100%;
            outline: none;
            font-size: 15px;
            padding: 0 15px 0 32px;
            border: 1px solid #f1f1f1;
            border-radius: 5px;
        }

        .content .options {
            margin-top: 10px;
            max-height: 200px;
            overflow-y: auto;
            padding-left: 0;
            padding-right: 7px;
        }

        .options::-webkit-scrollbar {
            width: 7px;
        }

        .options::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 25px;
        }

        .options::-webkit-scrollbar-thumb {
            background: #ccc;
            width: 25px;
        }

        .options li {
            height: 40px;
            padding: 0 13px;
            font-size: 18px;
            border-radius: 5px;
        }

        .options li.add-new-option {
            color: #008f85;
            font-size: 14px;
            border: 1px dashed #008f85;
        }

        .options li:hover,
        .options li.selected {
            background: #f2f2f2;
        }

        .ar {
            direction: rtl;
            font-family: "Scheherazade New", "Amiri Quran", serif;
            /* font-size: 12px; */
        }

        input.invalid,
        textarea.invalid {
            border-color: #e74c3c !important;
        }


        @media (max-width: 1080px) {
            .word-group {
                font-size: 1.5rem !important;
                text-align: center;
            }
        }

        @media (max-width: 768px) {

            .col-symbol,
            .col-translation {
                display: none;
            }

            td:last-child {
                white-space: nowrap;
            }

            #sortable-table th:last-child,
            #sortable-table td:last-child {
                max-width: 100px;
                padding: 0 0;
            }
        }
    </style>
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Input I'rob</h1>

                <div class="float-right">
                    <form method="GET" action="{{ route('wordgroups.grouping') }}" id="filter-form" class="mb-0">
                        <div class="input-group">
                            <select class="form-control {{-- select2 --}} form-control-sm" name="surah-option"
                                id="surah-option"
                                style="flex: 3; border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem;"
                                required>
                                <option value="">Pilih Surah</option>
                                @foreach ($surahs as $surah)
                                    <option value="{{ $surah->id }}" data-verse-count="{{ $surah->verse_count }}">
                                        {{ $surah->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" class="form-control" placeholder="Ayat" name="verse-option"
                                id="verse-option" value="" style="flex: 1;" required>

                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <input type="hidden" id="surah-id" value="{{ $surahId }}">
                    <input type="hidden" id="verse-number" value="{{ $verseNumber }}">
                    <input type="hidden" id="verse-id" value="{{ $verseId }}">

                    <div class="card-header" id="word">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h4 id="current-verse-label">{{ $surahId }}. {{ $surahName }} - Ayat
                                {{ $verseNumber }}</h4>
                            {{-- refresh button akan tampil disini setelah fetch dari lokal --}}
                        </div>
                    </div>

                    <div class="card-body position-relative">
                        <button id="btn-next-slide" class="slider-nav-btn prev">
                            <i class="fa fa-chevron-left"></i>
                        </button>

                        <div class="owl-carousel owl-theme slider" id="slider-rtl">
                            @foreach ($wordgroups as $wordgroup)
                                <div>
                                    <h4 class="arabic-text ar-title word-group text-center" wg-id="{{ $wordgroup->id }}">
                                        {{ $wordgroup->text }}
                                    </h4>
                                </div>
                            @endforeach
                        </div>

                        <button id="btn-prev-slide" class="slider-nav-btn next">
                            <i class="fa fa-chevron-right"></i>
                        </button>

                        <div class="editor-wordgroup" style="padding-top: 20px">
                            <span>Editor grouping : </span>
                            <a href="#"
                                class="font-weight-600">{{ $wordgroups->first()->editorInfo->name ?? '-' }}</a>
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
                                            <h4 class="mb-0">Input Kalimat</h4>
                                            {{-- hide btn-add-word when editor is blank --}}
                                            <button class="btn btn-icon icon-left btn-primary btn-lg" id="btn-add-word">
                                                <i class="fa-solid fa-plus"></i> Tambah
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-sm">
                                        <div class="">
                                            <table class="table-striped table" id="sortable-table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th class="col-word">Lafadz</th>
                                                        <th class="col-symbol">Simbol</th>
                                                        <th class="col-translation">Terjemah</th>
                                                        <th class="col-action">Opsi</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $firstGroup = $wordgroups->first();
                                                    $words =
                                                        $firstGroup && isset($firstGroup->words)
                                                            ? $firstGroup->words
                                                            : collect();
                                                @endphp
                                                <tbody>
                                                    @forelse ($words as $word)
                                                        <tr class="text-center">
                                                            <td class="text-center align-middle col-word"
                                                                id="{{ $word->id }}">
                                                                <div class="dropdown
                                                            @if ($word->color == 'red') text-huruf
                                                            @elseif($word->color == 'green') text-fiil
                                                            @elseif($word->color == 'blue') text-isim 
                                                            @else text-dark @endif arabic-text words"
                                                                    type="button" id="dropdownMenuButton2"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    {{ $word->text }}</div>
                                                                <div class="dropdown-menu">
                                                                    <a href="#"
                                                                        class="dropdown-item has-icon word-edit"><i
                                                                            class="far fa-edit"></i> Edit</a>
                                                                    <a href="#"
                                                                        class="dropdown-item has-icon text-danger word-delete"
                                                                        id="btn-delete"><i class="far fa-trash-can"></i>
                                                                        Hapus</a>
                                                                </div>
                                                            </td>
                                                            <td class="col-symbol">
                                                                <div
                                                                    class="text-center @if ($word->color == 'red') text-huruf
                                                @elseif($word->color == 'green') text-fiil
                                                @elseif($word->color == 'blue') text-isim
                                                @else text-dark mb-2 @endif mb-2 arabic-text ar-symbol">
                                                                    {{ $word->simbol }}</div>
                                                            </td>
                                                            <td class="col-translation">
                                                                {{ $word->translation }}
                                                            </td>
                                                            <td class="align-middle col-actions">
                                                                <div class="d-flex justify-content-center action-buttons">
                                                                    <button class="btn btn-sm btn-icon btn-warning"
                                                                        title="Edit">
                                                                        <i class="fa-solid fa-edit"></i>
                                                                    </button>
                                                                    <button class="btn btn-sm btn-icon btn-danger"
                                                                        title="Hapus" id="btn-delete">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>
                                                                    <button
                                                                        class="btn btn-sm btn-icon btn-primary btn-move-up"
                                                                        title="Naikkan">
                                                                        <i class="fa-solid fa-arrow-up"></i>
                                                                    </button>
                                                                    <button
                                                                        class="btn btn-sm btn-icon btn-primary btn-move-down"
                                                                        title="Turunkan">
                                                                        <i class="fa-solid fa-arrow-down"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">Tidak
                                                                ada
                                                                data</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
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
                                            @php
                                                $firstGroup = $wordgroups->first();
                                                $words =
                                                    $firstGroup && isset($firstGroup->words)
                                                        ? $firstGroup->words
                                                        : collect();
                                            @endphp
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
                        <div class="editor-kalimat" style="padding-top: 20px">
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
                    <div>
                        <button class="btn btn-icon icon-left btn-success btn-lg" id="btn-save-all"
                            style="display: none;">Simpan &
                            lanjutkan</button>
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
    {{-- <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script> --}}

    <script>
        window.WORDS_SYNC_URL = "{{ route('words.sync') }}";
        window.WORDGROUP_GET_URL = "{{ route('wordgroups.get', ['id' => ':id']) }}";
        window.CSRF_TOKEN = "{{ csrf_token() }}";
    </script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>
    <script src="{{ asset('js/page/words/storage-helper.js') }}?v=1.1.6"></script>
    <script src="{{ asset('js/page/words/word-crud.js') }}?v=1.1.6"></script>
    <script src="{{ asset('js/page/words/words-page.js') }}?v=1.1.6"></script>
    <script src="{{ asset('js/page/words/nahwu-form.js') }}?v=1.1.6"></script>
@endpush
