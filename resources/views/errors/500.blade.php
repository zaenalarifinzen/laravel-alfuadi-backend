@extends('layouts.error')

@section('title', '500')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="page-error">
        <div class="page-inner">
            <img src="{{ asset('img/server-error.png') }}" alt="logo" width="300" class="mb-4">
            <h1>500</h1>
            <div class="page-description">
                Terjadi kesalahan pada server.
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
