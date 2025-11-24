@extends('layouts.app')

@section('title', 'Grouping Kalimat')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <style>
        /* Font Arab */
        @import url('https://fonts.googleapis.com/css2?family=Scheherazade+New:wght@400;700&family=Amiri&display=swap');

        .arabic-container {
            direction: rtl;
            text-align: right;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        /* Pastikan form tetap LTR */
        .ltr-container {
            direction: ltr !important;
            text-align: left !important;
        }

        /* Reset untuk tampilan select agar tidak terpengaruh selectric.css */
        .ltr-container select.form-control {
            border-radius: 0 !important;
            height: calc(2.25rem + 2px) !important;
        }

        /* Agar input-group tidak ada overflow tersembunyi */
        .ltr-container .input-group {
            overflow: visible !important;
            flex-wrap: nowrap !important;
        }

        /* Supaya tombol tidak menumpuk di atas border input */
        .ltr-container .btn {
            z-index: 1;
        }


        /* Sesuaikan chip agar proporsional dengan tinggi huruf Arab */
        .selectgroup.selectgroup-pills .selectgroup-item .selectgroup-button {
            border-radius: 8px !important;
            padding: 0.4rem 0.8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: auto;
            min-height: 2.4rem;
            transition: all 0.2s ease-in-out;
        }

        /* Saat terpilih */
        .selectgroup-input:checked+.selectgroup-button {
            background-color: #d6f7f0 !important;
            color: #1a1a1a !important;
            border-color: #259980;
            border-width: 1px;
        }

        /* Supaya chip rapi dalam arah kanan ke kiri */
        .selectgroup.selectgroup-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            justify-content: flex-start;
        }

        /* Batasi efek RTL hanya untuk teks Arab */
        .arabic-container {
            direction: rtl;
            text-align: right;
        }

        /* Wrapper untuk form agar tidak ikut RTL */
        .ltr-container {
            direction: ltr !important;
            text-align: left !important;
        }

        /* Pastikan input group tetap gaya Bootstrap */
        .ltr-container .input-group {
            display: flex;
            align-items: stretch;
            gap: 0;
            /* biarkan Bootstrap handle */
        }

        .card-header .input-group {
            display: flex !important;
            justify-content: flex-start !important;
            align-items: center !important;
        }

        #button-bar {
            transition: opacity 0.25s ease, box-shadow 0.25s ease;
        }

        #button-bar:not(.floating) {
            opacity: 1;
        }

        #button-bar.floating {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0.95;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: auto;
            max-width: fit-content;
            padding: 8px 10px;
            display: flex;
            flex-wrap: nowrap;
            z-index: 999;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Grup Kalimat</h1>

                <div class="float-right">
                    <form method="GET" action="{{ route('wordgroups.grouping') }}" id="filter-form" class="mb-0">
                        <div class="input-group">
                            <select class="form-control form-control-sm" name="surah-option" id="surah-option"
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
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <input type="hidden" id="surah-id" value="{{ $currentSurah->id }}">
                                <input type="hidden" id="verse-number" value="{{ $currentVerse->number }}">
                                <input type="hidden" id="verse-id" value="{{ $currentVerse->id }}">
                                <input type="hidden" id="is-persisted" value="{{ $isPersisted }}">
                                <h4 id="result-verse" class="mb-0">{{ $currentSurah->name ?? 'Al-Fatihah' }} - Ayat
                                    {{ $currentVerse->number ?? 1 }}</h4>


                            </div>


                            <div class="card-body">
                                <div class="selectgroup selectgroup-pills arabic-container " dir="rtl"
                                    id="wordgroup-list" data-is-persisted="{{ $isPersisted ? '1' : '0' }}"
                                    data-surah-name="{{ $currentSurah->name }}"
                                    data-verse-count="{{ $currentSurah->verse_count }}"
                                    data-verse-number="{{ $currentVerse->number }}">
                                    @foreach ($words as $index => $word)
                                        <label class="selectgroup-item arabic-pill">
                                            <input type="checkbox" name="ids[]" value="{{ $word->id }}"
                                                class="selectgroup-input row-checkbox">
                                            <span
                                                class="selectgroup-button arabic-text ar-title">{{ $word->text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="editor" style="padding-top: 20px">
                                    <span>Editor : </span>
                                    <a href="#"
                                        class="font-weight-600">{{ $words->first()->editorInfo->name ?? '-' }}</a>
                                </div>

                                <div class="clearfix mb-3"></div>
                                <div id="button-bar-sentinel"></div>
                                <small id="merge-error" class="text-danger d-block mt-2" style="display: none;"></small>

                            </div>
                            <div class="card-footer">
                                <div id="button-bar"
                                    class="d-flex gap-2 mb-3 justify-content-center align-items-center flex-nowrap">
                                    <button type="submit" id="btn-unselect" class="btn btn-icon btn-lg btn-secondary"
                                        data-toggle="tooltip" data-placement="top" title="Bersihkan Pilihan"><i
                                            class="fa-regular fa-circle-xmark"></i></button>
                                    <button type="submit" id="btn-edit" class="btn btn-icon btn-lg btn-info disabled"
                                        data-toggle="tooltip" data-placement="top" title="Edit"><i
                                            class="fa-solid fa-pencil"></i></button>
                                    <form id="split-form" action="{{ route('wordgroups.split') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" id="split-id">
                                        <button type="submit" class="btn btn-icon btn-lg btn-warning disabled"
                                            id="btn-split" data-toggle="tooltip" data-placement="top"
                                            title="Pisahkan"><i class="fa-solid fa-scissors"></i>
                                        </button>
                                    </form>
                                    <form id="merge-form" action="{{ route('wordgroups.merge') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="ids" id="selected-ids">
                                        <button type="submit" class="btn btn-icon btn-lg btn-success disabled"
                                            id="btn-merge" data-toggle="tooltip" data-placement="top"
                                            title="Gabungkan"><i class="fa-solid fa-magnet"></i>
                                        </button>
                                    </form>
                                </div>
                                <div id="button-bar-sentinel"></div>
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
                        {{-- <button id="btn-test-save" class="btn btn-warning">Test Save JSON</button> --}}
                        <div>
                            <form id="complete-form" action="{{ route('wordgroups.save') }}" method="POST"
                                class="ml-auto">
                                @csrf
                                <input type="hidden" name="surah_id" value="{{ request('surah_id') }}">
                                <input type="hidden" name="verse_number" value="{{ request('verse_number') }}">
                                <button type="submit" class="btn btn-primary btn-lg" id="btn-complete">
                                    {{ $isPersisted ? 'Update' : 'Simpan & lanjutkan' }}
                                </button>
                            </form>
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>

    <script>
        window.WORDGROUP_GET_URL = "{{ route('wordgroups.get', ['id' => ':id']) }}";
        window.CSRF_TOKEN = "{{ csrf_token() }}";
    </script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>
    <script src="{{ asset('js/page/wordgroups/grouping-page.js') }}"></script>

@endpush
