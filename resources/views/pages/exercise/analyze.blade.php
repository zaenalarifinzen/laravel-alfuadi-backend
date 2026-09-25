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

        [data-theme="dark"] tr.is-wrong {
            background-color: #4d2626 !important;
        }

        tr.is-correct {
            background-color: #e6ffe6 !important;
        }

        [data-theme="dark"] tr.is-correct {
            background-color: #173b17 !important;
        }

        td.is-wrong {
            position: relative;
            background-color: #ffcccc !important;
            font-weight: bold;
            outline: 1px solid #ff4a4a;
        }

        html[data-theme="dark"] td.is-wrong {
            background-color: #4d1d1d !important;
            font-weight: bold;
            outline: 1px solid #ff4a4a;
        }

        td.is-hinted {
            background-color: #fff9e6 !important;
            outline: 1px solid #f6c23e;
        }

        html[data-theme="dark"] td.is-hinted {
            background-color: #453818 !important;
            outline: 1px solid #f6c23e;
        }

        td.text-center.align-middle.col-kalimat.is-wrong {
            position: relative;
        }

        .btn-cell-hint {
            position: absolute;
            top: 3px;
            right: 3px;
            margin: 0;
            padding: 2px 4px;
            background: transparent;
            color: #ff9f2e;
            border: none;
            line-height: 1;
            cursor: pointer;
            z-index: 2;
        }

        .btn-cell-hint:hover {
            background-color: transparent;
            color: #ff9f2e;
            transform: translateY(-1px);
            /* box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15); */
        }

        .badge-hint {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 10px;
            background-color: #ffeeba;
            color: #ffffffff;
            border: 1px solid #ffeeba;
            font-weight: 600;
            margin-top: 3px;
        }

        /* Dynamic layout transition */
        .transition-all {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Question list styling */
        .question-list-wrapper {
            scrollbar-width: thin;
            scrollbar-color: #1d948e #f1f1f1;
        }

        .question-list-wrapper::-webkit-scrollbar {
            width: 6px;
        }

        .question-list-wrapper::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .question-list-wrapper::-webkit-scrollbar-thumb {
            background: #1d948e;
            border-radius: 3px;
        }

        .question-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-left: 4px solid transparent;
            text-decoration: none !important;
            color: #495057;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .question-item:hover {
            background-color: rgba(29, 148, 142, 0.06);
            color: #1d948e;
        }

        .question-item.active {
            background-color: rgba(29, 148, 142, 0.12);
            border-left-color: #1d948e;
            font-weight: 600;
            color: #138a84;
        }

        .question-item .question-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            margin-right: 12px;
            flex-shrink: 0;
            background-color: #eef2f5;
            color: #6c757d;
            transition: all 0.2s ease;
        }

        .question-item.active .question-number {
            background-color: #1d948e;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(29, 148, 142, 0.4);
        }

        .question-item.passed .question-number {
            background-color: #47c363;
            color: #ffffff;
        }

        .question-item .question-info {
            flex-grow: 1;
            min-width: 0;
        }

        .question-item .question-title {
            font-size: 0.9rem;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .question-item .question-subtitle {
            font-size: 0.75rem;
            color: #868e96;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .question-item .question-status {
            margin-left: 8px;
            flex-shrink: 0;
        }

        /* Custom exercise layout width & narrowed body margins */
        @media (min-width: 992px) {
            .main-wrapper.container {
                max-width: 95% !important;
                width: 95% !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
        }

        @media (min-width: 1600px) {
            .main-wrapper.container {
                max-width: 1560px !important;
            }
        }

        .main-content {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        #btn-toggle-sidebar.active {
            background-color: #1d948e !important;
            color: #ffffff !important;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h1 class="mb-0">Latihan analisa</h1>

                </div>

                @if (request()->segment(2) === 'alquran')
                    <div class="float-right">
                        <form method="GET" action="{{ route('wordgroups.grouping') }}" id="search-verse-form"
                            class="mb-0">
                            <div class="input-group">
                                <select class="form-control form-control-sm" name="surah-option" id="surah-option"
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
                @else
                    <a href="#" id="btn-toggle-sidebar" title="Toggle Daftar Soal" class="btn btn-outline-primary">
                        <i class="fas fa-bars"></i></a>
                @endif
            </div>

            <div class="section-body exercise">
                <div class="row" id="exercise-layout-row">
                    <div class="col-lg-9 col-md-12 transition-all" id="exercise-main-content">
                        <div class="card">
                            <input type="hidden" id="surah-id" value="">
                            <input type="hidden" id="verse-number" value="">
                            <input type="hidden" id="verse-id" value="">
                            <input type="hidden" id="exercise-id" value="">

                            <div class="card-header" id="word">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <h4 id="current-wordgroup-label">Nama Soal</h4>
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
                                        <a class="nav-link" id="detail-tab" data-toggle="tab" href="#detail-table"
                                            role="tab" aria-controls="detail" aria-selected="false">Kunci</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="input-table" role="tabpanel"
                                        aria-labelledby="input-tab">
                                        <div class="card-header" id="input-table-header">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <h4 class="mb-0">Lembar Jawaban</h4>
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
                                    <div class="tab-pane fade" id="detail-table" role="tabpanel"
                                        aria-labelledby="detail-tab">
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
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">Tidak ada
                                                                data</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mb-4">
                            <div>
                                <button class="btn btn-icon icon-left btn-primary btn-lg" name="btn-submit"
                                    id="btn-submit-answer">Submit</button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar: Question List -->
                    <div class="col-lg-3 col-md-12 transition-all" id="exercise-sidebar">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center py-3">
                                <h4 class="mb-0 d-flex align-items-center" id="question-list-header">
                                    </i> Daftar Soal
                                </h4>
                                <span class="badge badge-light badge-pill font-weight-bold" id="question-count-badge">0
                                    Soal</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="question-list-wrapper" style="max-height: 520px; overflow-y: auto;">
                                    <div class="list-group list-group-flush" id="question-list">
                                        <div class="p-4 text-center text-muted spinner-container">
                                            <div class="spinner-border spinner-border-sm text-primary mr-2"
                                                role="status"></div>
                                            <span>Memuat daftar soal...</span>
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
            'exerciseGetUrl' => route('exercise.get', ['level' => ':level', 'exerciseId' => ':id']),
            'exerciseListUrl' => route('exercise.list', ['level' => ':level']),
            'csrfToken' => csrf_token(),
            'allowedSurahIds' => $allowedSurahIds,
            'allowedVerseNumbersBySurah' => $allowedVerseNumbersBySurah,
            'allowedSurahConfig' => $allowedSurahConfig,
        ]) !!}
    </script>

    <!-- Page Specific JS File -->
    @vite(['resources/js/page/exercise/exercise.js'])
@endpush
