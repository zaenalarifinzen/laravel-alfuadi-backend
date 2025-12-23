@extends('layouts.error')

@section('title', '503')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="page-error">
        <div class="page-inner">
            <img src="{{ asset('img/alfuadi_splash_icon.svg') }}" alt="logo" height="70">
            <h1>503</h1>
            <div class="page-description">
                Aplikasi sedang dalam pemeliharaan.
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
