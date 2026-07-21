@extends('layouts.app')

@section('title', 'Latihan analisa')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <style>
        /* correction */
        tr.is-wrong {
            background-color: #ffe6e6 !important;
        }

        tr.is-correct {
            background-color: #e6ffe6 !important;
        }

        td.is-wrong {
            background-color: #ffcccc !important;
            font-weight: bold;
            outline: 1px solid #ff4a4a;
        }
    </style>
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Latihan analisa</h1>

                <div class="float-right">
                    <form method="GET" action="{{ route('wordgroups.grouping') }}" id="search-verse-form" class="mb-0">
                        <div class="input-group">
                            <select class="form-control selectric form-control-sm" name="surah-option" id="surah-option"
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

            <div class="section-body exercise">
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

                        <div class="swiper slider" id="slider-rtl">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <h4 class="arabic-text ar-title word-group text-center" wg-id="#">
                                        Pilih soal terlebih dahulu
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <button id="btn-prev-slide" class="slider-nav-btn next">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>

                </div>

                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="input-tab" data-toggle="tab" href="#input-table"
                                    role="tab" aria-controls="input" aria-selected="true">Jawaban</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="detail-tab" data-toggle="tab" href="#detail-table" role="tab"
                                    aria-controls="detail" aria-selected="false">Kunci</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="input-table" role="tabpanel"
                                aria-labelledby="input-tab">
                                <div class="card-header" id="input-table-header">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <h4 class="mb-0">Input Jawaban</h4>
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
                            <div class="tab-pane fade" id="detail-table" role="tabpanel" aria-labelledby="detail-tab">
                                <div class="card">
                                    <div class="card-header" id="detail-table-header">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <h4 class="mb-0">Kunci Jawaban</h4>
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
                                            {{-- @php
                                                $firstGroup = $wordgroups->first();
                                                $words =
                                                    $firstGroup && isset($firstGroup->words)
                                                        ? $firstGroup->words
                                                        : collect();
                                            @endphp --}}
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
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    <div hidden>
                        <button type="button" class="btn btn-outline-primary btn-lg" id="btn-next-verse">
                            <i class="ion-chevron-left" data-pack="default" data-tags="arrow, right"></i></button>
                        <button type="button" class="btn btn-outline-primary btn-lg mr-2" id="btn-prev-verse"><i
                                class="ion-chevron-right" data-pack="default" data-tags="arrow, left"></i></button>
                    </div>
                    <div>
                        <button class="btn btn-icon icon-left btn-primary btn-lg" name="btn-submit"
                            id="btn-submit-answer">Submit</button>
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

                <form id="form-add-word" data-nahwu-autofill="false">
                    <div class="modal-body">
                        <input type="hidden" id="input-id">
                        <input type="hidden" id="input-order-number">
                        <div class="form-row">
                            <div class="form-group col-12">
                                {{-- <label for="input-lafadz">Lafadz</label> --}}
                                <input type="text" class="form-control arabic-text ar-title input-big text-center"
                                    id="input-lafadz" placeholder="لفظ" disabled>
                            </div>
                            <div class="form-group col-12">
                                {{-- <label for="input-translation">Terjemah</label> --}}
                                <input type="text" class="form-control text-center" id="input-translation"
                                    placeholder="terjemah" disabled>
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
                                    <label for="input-hukum">Hukum</label>
                                    <select id="input-hukum" class="custom-dropdown" name="hukum">
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="input-kategori">Kategori / Alasan mabni</label>
                                    <select id="input-kategori" class="custom-dropdown" name="kategori">
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="input-kedudukan">Kedudukan</label>
                                    <select id="input-kedudukan" class="custom-dropdown" name="kedudukan">
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
                            <div class="form-row" hidden>
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

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script type="application/json" id="page-config">
        @php
            $allowedSurahConfig = \App\Models\Setting::getJson('analysis_allowed_surah_config', []);
            $allowedSurahIds = [];
            $allowedVerseNumbersBySurah = [];

            foreach ($allowedSurahConfig as $surahId => $config) {
                if (!empty($config['enabled']) && !empty($config['max_verse'])) {
                    $allowedSurahIds[] = (int) $surahId;
                    $allowedVerseNumbersBySurah[(string) $surahId] = range(1, (int) $config['max_verse']);
                }
            }
        @endphp

        {!! json_encode([
            'pageType' => 'exercise',
            'wordgroupGetUrl' => route('exercise.analysis', ['verseId' => ':id']),
            'csrfToken' => csrf_token(),
            'allowedSurahIds' => $allowedSurahIds,
            'allowedVerseNumbersBySurah' => $allowedVerseNumbersBySurah,
            'allowedSurahConfig' => $allowedSurahConfig,
        ]) !!}
    </script>

    <!-- Page Specific JS File -->
    @vite(['resources/js/page/exercise/exercise.js'])
@endpush
