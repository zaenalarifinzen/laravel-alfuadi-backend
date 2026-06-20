@extends('layouts.auth')

@section('title', 'Verify email')

@section('main')
    <div class="card card-primary">
        <div class="card-header d-flex flex-column align-items-center justify-content-center text-center">
            <img src="{{ asset('img/envelope-checkmark.svg') }}" alt="logo" height="70" class="mb-3">
            <h4 class="text-center">Verifikasi email</h4>
        </div>

        <div class="card-body">
            <div id="alert-box" class="d-none"></div>
            <p id="info-text">Kami telah mengirim link untuk memverifikasi email anda. Silahkan buka email anda dan klik link yang ada.</p>
        </div>

        <div class="card-footer bg-whitesmoke" id="resend-footer">
            <p class="text-muted">Belum menerima email?
                <a href="#" class="text-primary" id="resend-btn" onclick="event.preventDefault(); handleResend();">
                    Klik disini
                </a>
                untuk mengirim ulang email verifikasi
            </p>

            <form id="resend-form" action="{{ route('verification.send') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function handleResend() {
            const btn = document.getElementById('resend-btn');
            const alertBox = document.getElementById('alert-box');
            const infoText = document.getElementById('info-text');
            const footer = document.getElementById('resend-footer');
            const token = document.querySelector('input[name="_token"]').value;

            // Disable tombol dulu agar tidak dobel klik
            btn.classList.add('text-muted', 'pe-none');
            btn.classList.remove('text-primary');

            fetch("{{ route('verification.send') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (response.status === 429) {
                    alertBox.innerHTML = `
                        <div class="alert alert-warning">
                            Terlalu sering mengirim. Silahkan tunggu beberapa menit.
                        </div>`;
                    alertBox.classList.remove('d-none');
                    infoText.remove();
                    footer.remove();

                    // Re-enable tombol jika kena rate limit
                    btn.classList.remove('text-muted', 'pe-none');
                    btn.classList.add('text-primary');
                    return;
                }

                // Sukses - hapus footer, tampilkan notifikasi
                infoText.remove();
                footer.remove();
                alertBox.innerHTML = `
                    <div class="alert alert-success">
                        Email verifikasi berhasil dikirim ulang! Silahkan cek inbox Anda.
                    </div>`;
                alertBox.classList.remove('d-none');
            })
            .catch(error => {
                alertBox.innerHTML = `
                    <div class="alert alert-danger">
                        Gagal mengirim email. Silahkan coba lagi.
                    </div>`;
                alertBox.classList.remove('d-none');

                // Re-enable tombol jika gagal
                btn.classList.remove('text-muted', 'pe-none');
                btn.classList.add('text-primary');
            });
        }
    </script>
@endpush