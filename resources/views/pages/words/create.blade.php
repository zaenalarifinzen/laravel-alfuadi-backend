@extends('layouts.app')

@section('title', 'Input Irob')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">


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

        @media (max-width: 1080px) {
            .word-group {
                font-size: 1.5rem !important;
                text-align: center;
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
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h4 class="mb-0">Data Kalimat</h4>
                            <button class="btn btn-icon icon-left btn-primary btn-lg" id="btn-add-word">
                                <i class="fa-solid fa-plus"></i> Tambah
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-sm">
                            <div class="table-responsive">
                                <table class="table-striped table" id="sortable-table">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="width:28px;">
                                                <i class="fa-solid fa-sort"></i>
                                            </th>
                                            <th>Lafadz</th>
                                            <th>Simbol</th>
                                            <th>Terjemah</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $firstGroup = $wordgroups->first();
                                        $words =
                                            $firstGroup && isset($firstGroup->words) ? $firstGroup->words : collect();
                                    @endphp
                                    <tbody>
                                        @forelse ($words as $word)
                                            <tr class="text-center">
                                                <td style="width: 28px;">
                                                    <div class="sort-handler">
                                                        <i class="fa-solid fa-grip"></i>
                                                    </div>
                                                </td>
                                                <td class="text-center align-middle word" id="{{ $word->id }}">
                                                    <div class="dropdown
                                                            @if ($word->color == 'red') text-huruf
                                                            @elseif($word->color == 'green') text-fiil
                                                            @elseif($word->color == 'blue') text-isim 
                                                            @else text-dark @endif arabic-text words"
                                                        type="button" id="dropdownMenuButton2" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        {{ $word->text }}</div>
                                                    <div class="dropdown-menu">
                                                        <a href="#" class="dropdown-item has-icon word-edit"><i
                                                                class="far fa-edit"></i> Edit</a>
                                                        <a href="#"
                                                            class="dropdown-item has-icon text-danger word-delete"
                                                            id="btl-delete"><i class="far fa-trash-can"></i> Hapus</a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        class="text-center @if ($word->color == 'red') text-huruf
                                                @elseif($word->color == 'green') text-fiil
                                                @elseif($word->color == 'blue') text-isim
                                                @else text-dark mb-2 @endif mb-2 arabic-text ar-symbol">
                                                        {{ $word->simbol }}</div>
                                                </td>
                                                <td class="translation">
                                                    {{ $word->translation }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
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
                        <button class="btn btn-icon icon-left btn-success btn-lg" id="btn-save-all">Simpan &
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
                                    id="input-lafadz" placeholder="لفظ" required="">
                            </div>
                            <div class="form-group col-12">
                                {{-- <label for="input-translation">Terjemah</label> --}}
                                <input type="text" class="form-control text-center" id="input-translation"
                                    placeholder="Terjemah" required="">
                            </div>
                        </div>
                        {{-- additional fields --}}
                        <div id="additional-fields" style="display: none;">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-kalimat">Kalimat</label>
                                <select id="input-kalimat" class="form-control form-control-ar arabic-text">
                                    <option selected disabled>Pilih Kalimat</option>
                                    <option value="10">اِسْمٌ</option>
                                    <option value="21">فِعْلٌ مَاضٍ</option>
                                    <option value="22">فِعْلٌ مُضَارِعٌ</option>
                                    <option value="23">فِعْلٌ أَمْرٍ</option>
                                    <option value="30">حَرْفٌ</option>
                                    <option value="41">جُمْلَةٌ</option>
                                    <option value="42">شِبْهُ الْجُمْلَةِ</option>
                                    <option value="11">اِسْمٌ مُؤَوَّلٌ</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-kategori">Kategori</label>
                                <select id="input-kategori" class="form-control form-control-ar arabic-text">
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-kedudukan">Kedudukan</label>
                                <select id="input-kedudukan" class="form-control form-control-ar arabic-text">
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-hukum">Hukum</label>
                                <select id="input-hukum" class="form-control form-control-ar arabic-text">
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-irob">I'rob</label>
                                <select id="input-irob" class="form-control form-control-ar arabic-text">
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-tanda">Tanda i'rob</label>
                                <select id="input-tanda" class="form-control form-control-ar arabic-text">
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-simbol">Simbol</label>
                                <select id="input-simbol" class="form-control form-control-ar arabic-text">
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        window.WORDS_SYNC_URL = "{{ route('words.sync') }}";
        window.WORDGROUP_GET_URL = "{{ route('wordgroups.get', ['id' => ':id']) }}";
        window.CSRF_TOKEN = "{{ csrf_token() }}";
    </script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>
    <script src="{{ asset('js/page/words/storage-helper.js') }}?v=1.1.0"></script>
    <script src="{{ asset('js/page/words/word-crud.js') }}?v=1.1.0"></script>
    <script src="{{ asset('js/page/words/words-page.js') }}?v=1.1.0"></script>
    <script src="{{ asset('js/page/words/autocomplete.js') }}?v=1.1.0"></script>
@endpush
