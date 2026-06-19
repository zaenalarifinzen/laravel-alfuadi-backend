@extends('layouts.app')

@section('title', 'Nahwu Mudah Jilid 1')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Nahwu Mudah Jilid 1</h1>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <h2 class="section-title">Latihan Analisa</h2>
                <p class="section-lead">
                    Lembar latihan analisa kalimat
                </p>


                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>


    <!-- Page Specific JS File -->
@endpush
