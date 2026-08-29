@extends('layouts.app')

@section('title', 'Input Irob')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/ionicons201/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">

    <style>
        .custom-dropdown.autofill-highlight .select-btn {
            animation: autofill-outline 1.8s ease forwards;
        }

        /* Autofill Effect */
        @keyframes autofill-outline {
            0% {
                border-color: #10b981;
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.45);
            }

            20% {
                border-color: #10b981;
                box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.18);

            }

            45% {
                border-color: #10b981;
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }

            100% {
                border-color: #EAECFC;
                box-shadow: none;
            }
        }
    </style>
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Input I'rob</h1>
            </div>

            <div class="section-body">
                {{-- WordGroup Swiper Component--}}
                <x-wordgroup-swiper
                    :title="$exercise->title"
                    :wordgroups="$exercise->content['wordGroups'] ?? []"
                    id="irob-wordgroup-swiper"
                />

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
                                {{-- Input Word Table Component --}}
                                <x-word-table-input/>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel"
                                aria-labelledby="profile-tab">
                                <x-word-table-detail/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    <div>
                        <button class="btn btn-icon icon-left btn-success btn-lg" name="btn-submit" id="btn-save-all"
                            style="display: none;">Simpan</button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Word Form Modal --}}
    <x-word-form-modal />

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <script type="application/json" id="page-config">
        {!! json_encode([
            'pageType' => 'words',
            'wordsSyncUrl' => route('admin.exercises.irob.update', ['id' => $exercise->id]),
            'initialData' => $exercise,
            'csrfToken' => csrf_token(),
        ]) !!}
    </script>

    <!-- Page Specific JS File -->
    @vite(['resources/js/page/admin/exercise/irob-init.js'])
@endpush
