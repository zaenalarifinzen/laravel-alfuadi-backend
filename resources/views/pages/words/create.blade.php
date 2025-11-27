@extends('layouts.app')

@section('title', 'Input Irob')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">


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
                        <div class="table-responsive">
                            <table class="table-striped table" id="sortable-table">
                                <thead>
                                    <tr class="text-center">
                                        <th>
                                            <i class="fa-solid fa-sort"></i>
                                        </th>
                                        <th>Lafadz</th>
                                        <th>Terjemah</th>
                                        <th>Kalimat</th>
                                        <th>Kedudukan</th>
                                    </tr>
                                </thead>
                                @php
                                    $firstGroup = $wordgroups->first();
                                    $words = $firstGroup && isset($firstGroup->words) ? $firstGroup->words : collect();
                                @endphp
                                <tbody>
                                    @forelse ($words as $word)
                                        <tr class="text-center">
                                            <td>
                                                <div class="sort-handler">
                                                    <i class="fa-solid fa-grip"></i>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle word" id="{{ $word->id }}">
                                                <div class="arabic-text words">{{ $word->text }}</div>
                                                <div class="table-links">
                                                    <a href="#" class="word-detail">Detail</a>
                                                    <div class="bullet"></div>
                                                    <a href="#" class="word-edit">Edit</a>
                                                    <div class="bullet"></div>
                                                    <a href="#" class="text-danger word-delete">Hapus</a>
                                                </div>
                                            </td>
                                            <td class="translation">
                                                {{ $word->translation }}
                                            </td>
                                            <td>
                                                <div
                                                    class="badge @if ($word->kalimat == 'فعل') badge-success
                                                @elseif($word->kalimat == 'اسم') badge-info
                                                @elseif($word->kalimat == 'حرف') badge-danger
                                                @else badge-light @endif">
                                                    {{ $word->kalimat }}</div>
                                            </td>
                                            <td class="arabic-text">
                                                {{ $word->jenis }}
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
                                    placeholder="Terjemah">
                            </div>
                        </div>
                        @if (auth()->check() && auth()->user()->roles === 'administrator')
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="input-kalimat">Kalimat</label>
                                    <select id="input-kalimat" class="form-control form-control-ar arabic-text">
                                        <option selected>اسم</option>
                                        <option>فعل</option>
                                        <option>حرف</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="input-variation">Jenis</label>
                                    <select id="input-variation" class="form-control form-control-ar arabic-text">
                                        <option selected></option>
                                        <option>اسم ضمير</option>
                                        <option>اسم اشارة</option>
                                        <option>اسم موصول</option>
                                        <option>اسم استفهام</option>
                                        <option>اسم شرط</option>
                                        <option>اسم فعل</option>
                                        <option>منادى مفرد</option>
                                        <option>مركب عدديّ</option>
                                        <option>اسم لا لنفي الجنس</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="input-hukum">Hukum</label>
                                    <select id="input-hukum" class="form-control form-control-ar arabic-text">
                                        <option selected>مبني</option>
                                        <option>معرب</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="input-mabni-detail">Detail mabni</label>
                                    <select id="input-mabni-detail" class="form-control form-control-ar arabic-text">
                                        <option selected></option>
                                        <option>على الفتح</option>
                                        <option>على الضمّ</option>
                                        <option>على السكون</option>
                                        <option>حذف النون</option>
                                        <option>حذف حرف العلة</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="input-mahal">Kedudukan</label>
                                    <select id="input-mahal" class="form-control form-control-ar arabic-text">
                                        <option selected>فاعل</option>
                                        <option>مفعول</option>
                                        <option>...</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="input-matbu">Matbu</label>
                                    <input type="text" class="form-control form-control-ar arabic-text"
                                        id="input-matbu" placeholder="لفظ">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="input-irob">I'rob</label>
                                    <select id="input-irob" class="form-control form-control-ar arabic-text">
                                        <option selected>رفع</option>
                                        <option>نصب</option>
                                        <option>جر</option>
                                        <option>جزم</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="input-alamat">Tanda i'rob</label>
                                    <select id="input-alamat" class="form-control form-control-ar arabic-text">
                                        <option selected>فتحة</option>
                                        <option>كسرة</option>
                                        <option>ضمة</option>
                                        <option>سكون</option>
                                        <option>واو</option>
                                        <option>يا</option>
                                        <option>فى محل</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="input-condition">Kondisi tanda i'rob</label>
                                    <select id="input-condition" class="form-control form-control-ar arabic-text">
                                        <option selected></option>
                                        <option selected>ظاهرة</option>
                                        <option>مقدرة</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="input-category">Simbol</label>
                                    <select id="input-category" class="form-control form-control-ar arabic-text" disabled>
                                        <option selected></option>
                                        <option></option>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        @endif
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
    </script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/components-table.js') }}"></script>
    <script src="{{ asset('js/page/words/storage-helper.js') }}?v=1.0.1+3"></script>
    <script src="{{ asset('js/page/words/word-crud.js') }}?v=1.0.1+3"></script>
    <script src="{{ asset('js/page/words/words-page.js') }}?v=1.0.1+3"></script>
@endpush
