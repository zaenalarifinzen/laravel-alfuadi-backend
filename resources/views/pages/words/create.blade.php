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

        .arabic-text {
            line-height: 2.2;
            padding: 10px 0;
            display: block;
        }

        .ar-title {
            font-size: 48px;
        }

        .ar-subtitle {
            font-size: 21px !important;
        }

        .input-big {
            height: 80px !important;
            padding-top: 10px;
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

                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            {{-- <button type="button" class="btn btn-outline-primary btn-lg" id="btn-next-verse">
                                <i class="ion-chevron-left" data-pack="default" data-tags="arrow, right"></i></button> --}}

                            <h4 id="current-verse-label">{{ $surahId }}. {{ $surahName }} - Ayat
                                {{ $verseNumber }}</h4>

                            {{-- <button type="button" class="btn btn-outline-primary btn-lg mr-2" id="btn-prev-verse"><i
                                    class="ion-chevron-right" data-pack="default" data-tags="arrow, left"></i></button> --}}
                        </div>
                    </div>

                    <div class="card-body d-flex justify-content-between align-items-center" style="gap: 1rem;">
                        <button id="btn-next-slide" class="btn btn-outline-primary btn-lg flex-shrink-0">
                            <i class="fa fa-chevron-left"></i>
                        </button>

                        <div class="flex-grow-1" style="max-width: 80%; overflow: hidden;">
                            <div class="owl-carousel owl-theme slider" id="slider-rtl">
                                @foreach ($wordgroups as $wordgroup)
                                    <div>
                                        <h4 class="arabic-text word-group" wg-id="{{ $wordgroup->id }}">
                                            {{ $wordgroup->text }}
                                        </h4>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button id="btn-prev-slide" class="btn btn-outline-primary btn-lg flex-shrink-0">
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
            </div>

            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <button type="button" class="btn btn-outline-primary btn-lg" id="btn-next-verse">
                        <i class="ion-chevron-left" data-pack="default" data-tags="arrow, right"></i></button>
                    <button type="button" class="btn btn-outline-primary btn-lg mr-2" id="btn-prev-verse"><i
                            class="ion-chevron-right" data-pack="default" data-tags="arrow, left"></i></button>
                </div>
                <div>
                    <form id="complete-form" action="{{ route('wordgroups.save') }}" method="POST" class="ml-auto">
                        @csrf
                        <input type="hidden" name="surah_id" value="{{ request('surah_id') }}">
                        <input type="hidden" name="verse_number" value="{{ request('verse_number') }}">
                        <button class="btn btn-icon icon-left btn-success btn-lg" id="btn-save-all"><i
                                class="fa-solid fa-floppy-disk"></i> Simpan & lanjutkan</button>
                    </form>
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
                    <h5 class="modal-title" id="form-add-word-label">Tambah Kalimat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="form-add-word">
                    <div class="modal-body">
                        <input type="hidden" id="input-id">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-lafadz">Lafadz</label>
                                <input type="text" class="form-control arabic-text ar-subtitle input-big text-center"
                                    id="input-lafadz" placeholder="لفظ" required="">

                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-translation">Terjemah</label>
                                <input type="text" class="form-control text-center" id="input-translation"
                                    placeholder="Terjemah">
                            </div>
                        </div>
                        {{-- <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-kalimat">Kalimat</label>
                                <select id="input-kalimat" class="form-control" disabled>
                                    <option selected>اسم</option>
                                    <option>فعل</option>
                                    <option>حرف</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-variation">Jenis</label>
                                <select id="input-variation" class="form-control" disabled>
                                    <option selected>Pilih</option>
                                    <option>...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-hukum">Hukum</label>
                                <select id="input-hukum" class="form-control" disabled>
                                    <option selected>مبني</option>
                                    <option>معرب</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-mabni-detail">Detail Mabni</label>
                                <select id="input-mabni-detail" class="form-control" disabled>
                                    <option selected>Fathah</option>
                                    <option>Dhommah</option>
                                    <option>Kasroh</option>
                                    <option>Sukun</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-category">Kategori</label>
                                <select id="input-category" class="form-control" disabled>
                                    <option selected>Pilih...</option>
                                    <option>...</option>
                                    <option>...</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-mahal">Kedudukan</label>
                                <select id="input-mahal" class="form-control" disabled>
                                    <option selected>فاعل</option>
                                    <option>مفعول</option>
                                    <option>...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-irob">Irob</label>
                                <select id="input-irob" class="form-control" disabled>
                                    <option selected>رفع</option>
                                    <option>نصب</option>
                                    <option>جر</option>
                                    <option>جزم</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-alamat">Tanda I'rob</label>
                                <select id="input-alamat" class="form-control" disabled>
                                    <option selected>Fathah</option>
                                    <option>Dhommah</option>
                                    <option>Kasroh</option>
                                    <option>Sukun</option>
                                    <option>Wawu</option>
                                    <option>Ya</option>
                                    <option>Fi Mahal</option>
                                    <option>...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="input-condition">Kondisi</label>
                                <select id="input-condition" class="form-control" disabled>
                                    <option selected>Fi Mahal</option>
                                    <option>Dzohiroh</option>
                                    <option>Muqoddaroh</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="input-matbu">Yang di ikuti</label>
                                <input type="text" class="form-control arabic-text" id="input-matbu"
                                    placeholder="لفظ" disabled>
                            </div>
                        </div> --}}
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-submit">Tambahkan</button>
                    </div>
                </form>
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
    <script src="{{ asset('js/page/words/word-crud.js') }}"></script>
    <script src="{{ asset('js/page/words/words-page.js') }}"></script>
@endpush
