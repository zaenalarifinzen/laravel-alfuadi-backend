@extends('layouts.auth')

@section('title', 'Verify email')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header d-flex flex-column align-items-center justify-content-center text-center">
            <img src="{{ asset('img/envelope-checkmark.svg') }}" alt="logo" height="70" class="mb-3">
            <h4 class="text-center">Verifikasi email</h4>

        </div>

        <div class="card-body">
            <p>Kami telah mengirim link untuk memverifikasi email anda. Silahkan buka email anda dan klik link yang ada</p>
        </div>

        <div class="card-footer bg-whitesmoke">
            <p class="text-muted">Belum menerima email? <a href="{{ route('verification.notice') }}"
                    class="text-primary">Klik
                    disini</a> untuk mengirim ulang email verifikasi</p>
        </div>

    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
