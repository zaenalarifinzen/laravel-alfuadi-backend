@extends('layouts.dashboard')

@section('title', 'Grouping')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Grouping</h1>
            </div>

            <div class="section-body">
                <div class="card grouping">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 id="grouping-title">{{ $exercise->title }}</h4>
                    </div>

                    <div class="card-body">
                        <div class="selectgroup selectgroup-pills arabic-container" dir="rtl" id="wordgroup-list"
                            data-is-persisted="">
                            @foreach ($exercise->content['wordGroups'] as $index => $wordGroup)
                                <label class="selectgroup-item arabic-pill">
                                    <input type="checkbox" name="ids[]" value="{{ $wordGroup['id'] }}"
                                        class="selectgroup-input row-checkbox">
                                    <span class="selectgroup-button arabic-text ar-title">{{ $wordGroup['text'] }}</span>
                                </label>
                            @endforeach
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
                                <button type="submit" class="btn btn-icon btn-lg btn-warning disabled" id="btn-split"
                                    data-toggle="tooltip" data-placement="top" title="Pisahkan"><i
                                        class="fa-solid fa-scissors"></i>
                                </button>
                            </form>
                            <form id="merge-form" action="{{ route('wordgroups.merge') }}" method="POST">
                                @csrf
                                <input type="hidden" name="ids" id="selected-ids">
                                <button type="submit" class="btn btn-icon btn-lg btn-success disabled" id="btn-merge"
                                    data-toggle="tooltip" data-placement="top" title="Gabungkan"><i
                                        class="fa-solid fa-magnet"></i>
                                </button>
                            </form>
                        </div>
                        <div id="button-bar-sentinel"></div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end align-items-center">
                <div>
                    <form id="complete-form" action="{{ route('dashboard.exercises.grouping.update', $exercise->id) }}" method="POST" class="ml-auto">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-complete">
                            Simpan
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script type="application/json" id="page-config">
        {!! json_encode([
            'pageType' => 'words',
            'wordsSyncUrl' => route('words.sync'),
            'wordgroupGetUrl' => route('wordgroups.get', ['id' => ':id']),
            'csrfToken' => csrf_token(),
        ]) !!}
    </script>

    <!-- Page Specific JS File -->
    @vite(['resources/js/page/admin/exercise/grouping.js'])
@endpush
