@extends('layouts.app')

@section('title', 'Profile')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">

                
                    
                        <div class="card-body">
                            <ul class="list-unstyled user-progress list-unstyled-border list-unstyled-noborder">
                                <li class="media">
                                    <img alt="image" class="rounded-circle mr-3" width="50"
                                        src="{{ asset('img/avatar/avatar-1.png') }}">
                                    <div class="media-body">
                                        <div class="media-title">Rizal Fakhri</div>
                                        <div class="text-job text-muted">Web Developer</div>
                                    </div>
                                    <div class="media-progressbar">
                                        <div class="progress-text">30%</div>
                                        <div class="progress" data-height="6">
                                            <div class="progress-bar bg-primary" data-width="30%"></div>
                                        </div>
                                    </div>
                                    <div class="media-cta">
                                        <a href="#" class="btn btn-outline-primary">Detail</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/cleave.js/dist/cleave.min.js') }}"></script>
    <script src="{{ asset('library/cleave.js/dist/addons/cleave-phone.us.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/forms-advanced-forms.js') }}"></script>
@endpush
