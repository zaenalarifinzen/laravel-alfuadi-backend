@extends('layouts.app')

@section('title', 'Latihan analisa')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <h1>Latihan analisa</h1>
                @if (auth()->user()->roles === 'administrator')
                    <div class="section-header-button">
                        <a href="{{ route('exercise-level.create') }}" class="btn btn-primary">
                            Tambah
                        </a>
                    </div>
                @endif
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Level</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h2 class="section-title">Pilih level</h2>
                        <p class="section-lead mb-0">
                            Latih kemampuan nahwumu dengan analisa soal-soal secara langsung.
                        </p>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">
                        @foreach ($questionLevel as $level)
                            <a href="{{ route('exercise.quran') }}" class="text-decoration-none">
                                <div class="card">
                                    <div class="card-header">
                                        <figure class="avatar bg-secondary mr-2 text-white"
                                            data-initial="{{ $level->level_number }}"></figure>
                                        <h4>{{ $level->name }}</h4>
                                        {{-- <div class="card-header-action">
                                        <a href="{{ route('exercise.quran') }}" class="btn btn-primary">
                                            Mulai
                                        </a>
                                    </div> --}}
                                    </div>
                                    <div class="collapse show" id="mycard-collapse">
                                        <div class="card-body">
                                            <div class="progress mb-3" data-height="5">
                                                <div class="progress-bar bg-success" role="progressbar" data-width="25%"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>5 dari 15 soal selesai</span>
                                                <span>100%</span>
                                            </div>
                                        </div>

                                        {{-- <div class="card-footer">
                                        Card Footer
                                    </div> --}}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- <div class="row">
                    @foreach ($questionLevel as $level)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <a href="{{ route('exercise.quran') }}">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-primary d-flex align-items-center justify-content-center">
                                        <p class="display-4 text-light mb-0">{{ $level->level_number }}</p>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Level</h4>
                                        </div>
                                        <div class="card-body">
                                            {{ $level->name }}
                                        </div>
                                    </div>
                                    <div class="progress mb-1" data-height="5">
                                        <div class="progress-bar bg-success" role="progressbar" data-width="25%"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>5 dari 15 soal selesai</span>
                                        <span>100%</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div> --}}
            </div>
        </section>
    </div>


@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
@endpush
