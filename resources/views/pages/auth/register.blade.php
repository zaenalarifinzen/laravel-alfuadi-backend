@extends('layouts.auth')

@section('title', 'Register')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Daftar</h4>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action={{ route('register') }}>
                @csrf

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input id="name" type="text" class="form-control" name="name" autofocus>
                    <div class="invalid-feedback">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email">
                    <div class="invalid-feedback">
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Handphone</label>
                    <input id="phone" type="number" class="form-control" name="phone">
                    <div class="invalid-feedback">
                    </div>
                </div>

                <div class="form-group" style="position: relative">
                    <div class="d-block">
                        <label for="password" class="control-label">Password</label>
                    </div>
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror" name="password"
                        tabindex="2" autocomplete="current-password" required>
                    <span class="toggle-password-icon"
                        style="
                        position: absolute;
                        right: 15px;
                        top: 48px;
                        transform: translateY(-50%);
                        cursor: pointer;
                        color: #888
                    ">
                        <i class="fas fa-eye"></i>
                    </span>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group" style="position: relative">
                    <div class="d-block">
                        <label for="password_confirmation" class="control-label">Konfirmasi Password</label>
                    </div>
                    <input id="password_confirmation" type="password"
                        class="form-control @error('password') is-invalid @enderror" name="password_confirmation"
                        tabindex="2" autocomplete="current-password" required>
                    <span class="toggle-password-icon"
                        style="
                        position: absolute;
                        right: 15px;
                        top: 48px;
                        transform: translateY(-50%);
                        cursor: pointer;
                        color: #888
                    ">
                        <i class="fas fa-eye"></i>
                    </span>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="agree" class="custom-control-input" id="agree">
                        <label class="custom-control-label" for="agree">Saya setuju dengan syarat dan ketentuan</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Daftar
                    </button>
                </div>
            </form>
            <div class="text-muted mt-5 text-center">
                Sudah memiliki akun? <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth-register.js') }}"></script>
    <script src="{{ asset('js/page/auth/auth-form.js') }}"></script>
@endpush
