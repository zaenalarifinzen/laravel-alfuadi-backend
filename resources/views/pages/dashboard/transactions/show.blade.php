@extends('layouts.dashboard')

@section('title', 'Detail Transaksi ' . $transaction->invoice_code)

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Transaksi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('dashboard.transactions.index') }}">Transaksi</a></div>
                    <div class="breadcrumb-item">{{ $transaction->invoice_code }}</div>
                </div>
            </div>

            <div class="section-body">
                @include('layouts.alert')

                <div class="row">
                    <!-- Rincian Transaksi & Kursus -->
                    <div class="col-12 col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>{{ $transaction->invoice_code }}</h4>
                                <div>
                                    @if ($transaction->isPending())
                                        <span class="badge badge-warning">Tertunda</span>
                                    @elseif ($transaction->isPaid())
                                        <span class="badge badge-success"><i
                                                class="fas fa-check-circle mr-1"></i>Lunas</span>
                                    @else
                                        <span class="badge badge-danger">Dibatalkan</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Detail Kursus -->
                                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                                    @if ($transaction->course && $transaction->course->thumbnail)
                                        <img src="{{ asset('storage/' . $transaction->course->thumbnail) }}"
                                            alt="{{ $transaction->course->title }}" class="mr-3 rounded"
                                            style="width: 100px; height: 70px; object-fit: cover;">
                                    @else
                                        <div class="mr-3 rounded bg-light d-flex align-items-center justify-content-center text-muted"
                                            style="width: 100px; height: 70px;">
                                            <i class="fas fa-book-open fa-2x"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-1 text-dark">
                                            {{ $transaction->course->title ?? 'Kursus Telah Dihapus' }}</h5>
                                        <div class="text-muted small">
                                            <span><i class="fas fa-tag mr-1"></i>
                                                {{ $transaction->course->category->name ?? 'Kategori' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rincian Biaya -->
                                <h6 class="font-weight-bold text-dark mb-3">Rincian Pembayaran</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted">Harga Asli Kursus</td>
                                        <td class="text-right font-weight-600">Rp
                                            {{ number_format($transaction->base_price, 0, ',', '.') }}</td>
                                    </tr>
                                    @if ($transaction->discount_amount > 0)
                                        <tr class="text-success">
                                            <td>
                                                Potongan Voucher ({{ $transaction->voucher_code }})
                                                <span
                                                    class="badge badge-success small ml-1">-{{ (int) $transaction->discount_percent }}%</span>
                                            </td>
                                            <td class="text-right font-weight-600">-Rp
                                                {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                    @if ($transaction->unique_code > 0)
                                        <tr>
                                            <td class="text-muted">Kode Unik Transfer</td>
                                            <td class="text-right font-weight-600">+Rp
                                                {{ number_format($transaction->unique_code, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                    <tr class="border-top">
                                        <td class="font-weight-bold text-dark h6 pt-3">Total Pembayaran</td>
                                        <td class="text-right font-weight-bold text-primary h5 pt-3">
                                            @if ($transaction->total_amount == 0)
                                                <span class="text-success">GRATIS (Rp 0)</span>
                                            @else
                                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>

                                @if ($transaction->notes)
                                    <div class="alert alert-light border mt-3">
                                        <strong>Catatan:</strong> {{ $transaction->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Data Peserta</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="text-muted mb-0">Nama Lengkap</label>
                                            <div class="font-weight-bold">{{ $transaction->buyer_name }}</div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label class="text-muted mb-0">Email</label>
                                            <div>{{ $transaction->buyer_email }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="text-muted small mb-0">No. WhatsApp</label>
                                            <div>
                                                @php
                                                    $cleanPhone = preg_replace(
                                                        '/[^0-9]/',
                                                        '',
                                                        $transaction->buyer_phone,
                                                    );
                                                    if (str_starts_with($cleanPhone, '0')) {
                                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                                    }
                                                @endphp
                                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank"
                                                    class="btn btn-sm btn-outline-success font-weight-bold mt-1">
                                                    <i class="fab fa-whatsapp mr-1"></i>{{ $cleanPhone }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label class="text-muted small mb-0">Waktu Pemesanan</label>
                                            <div>{{ $transaction->created_at->format('d F Y, H:i:s') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Data Pembeli & Verifikasi Admin -->
                    <div class="col-12 col-md-4">
                        <!-- Rekening Bank Tujuan -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Rekening Tujuan</h4>
                            </div>
                            <div class="card-body">
                                @if ($transaction->payment_method === 'free_voucher' || $transaction->total_amount == 0)
                                    <div class="alert alert-success py-2 px-3 small mb-0">
                                        <i class="fas fa-gift mr-1"></i> Bebas Biaya (Voucher 100%)
                                    </div>
                                @else
                                    <div class="font-weight-bold text-dark">
                                        {{ $transaction->bank_name ?? 'Transfer Bank' }}</div>
                                    <div class="font-weight-600 text-muted">{{ $transaction->bank_account }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Aksi Verifikasi Admin -->
                        @if ($transaction->isPending())
                            <div class="card border border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h4 class="text-dark"><i class="fas fa-circle-check mr-1"></i> Verifikasi Pembayaran
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-3">
                                        Jika Anda telah memastikan mutasi rekening bank masuk sesuai nominal, klik tombol
                                        setujui di bawah untuk mengaktifkan kelas siswa.
                                    </p>
                                    <div class="row">
                                        <div class="col-6">
                                            <form action="{{ route('dashboard.transactions.approve', $transaction->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menyetujui transaksi ini?')">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-block btn-md mb-2">
                                                    <i class="fas fa-check-circle mr-1"></i> Setujui
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-outline-danger btn-block btn-md"
                                                data-toggle="modal" data-target="#rejectModal">
                                                <i class="fas fa-times-circle mr-1"></i> Tolak
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Tolak -->
                            <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('dashboard.transactions.reject', $transaction->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">Batalkan Transaksi</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin ingin membatalkan transaksi
                                                    <strong>{{ $transaction->invoice_code }}</strong>?
                                                </p>
                                                <div class="form-group mb-0">
                                                    <label>Alasan Pembatalan:</label>
                                                    <textarea name="notes" class="form-control" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-danger">Batalkan Transaksi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @elseif ($transaction->isPaid())
                            <div class="card border border-success">
                                <div class="card-header bg-success text-white">
                                    <h4 class="text-white"><i class="fas fa-circle-check mr-1"></i> Terverifikasi</h4>
                                </div>
                                <div class="card-body">
                                    <div class="small text-muted mb-1">Disetujui Oleh:</div>
                                    <div class="font-weight-bold">
                                        {{ $transaction->approver->name ?? 'Sistem Otomatis (Gratis)' }}</div>
                                    <div class="small text-muted mt-2 mb-1">Tanggal Verifikasi:</div>
                                    <div class="font-weight-600">
                                        {{ $transaction->paid_at ? $transaction->paid_at->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
