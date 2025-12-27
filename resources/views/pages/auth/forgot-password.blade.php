@extends('layouts.auth')

@section('title', 'Forgot Password')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Lupa Password</h4>
        </div>

        <div class="card-body">
            <p class="text-muted">Kami akan mengirim link untuk mengatur ulang password</p>
            <form method="POST" action="{{ route('forgot-password') }}" class="needs-validation" novalidate="">
                <div class="form-group">
                    <label for="email">Masukkan email</label>
                    <input id="email"
                        type="email"
                        class="form-control"
                        name="email"
                        tabindex="1"
                        required
                        autofocus>
                </div>

                <div class="form-group">
                    <button type="submit"
                        class="btn btn-primary btn-lg btn-block"
                        tabindex="4">
                        Kirim link
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
