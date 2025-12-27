@extends('layouts.auth')

@section('title', 'Reset Password')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Reset Password</h4>
        </div>

        <div class="card-body">
            {{-- Form for resetting password --}}
            <form method="POST" action="{{ route('reset-password') }}" class="needs-validation" novalidate="">
                @csrf
                <div class="form-group" style="position: relative">
                    <div class="form-group" style="position: relative">
                        <div class="d-block">
                            <label for="password" class="control-label">Password</label>
                        </div>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" tabindex="2" autocomplete="current-password" required>
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
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/auth/auth-form.js') }}"></script>
@endpush
