@extends('layouts.dashboard')

@section('title', 'Transaksi Pesanan Kursus')

@push('style')
<style>
    .stat-card-custom {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    .stat-card-custom:hover {
        transform: translateY(-2px);
    }
    .badge-status-pending {
        background-color: #fff8e1;
        color: #b78103;
        border: 1px solid #ffe082;
        font-weight: 700;
        padding: 5px 10px;
    }
    .badge-status-paid {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #a5d6a7;
        font-weight: 700;
        padding: 5px 10px;
    }
    .badge-status-cancelled {
        background-color: #ffebee;
        color: #c62828;
        border: 1px solid #ef9a9a;
        font-weight: 700;
        padding: 5px 10px;
    }
    .btn-action-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }
</style>
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Transaksi Pesanan Kursus</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Transaksi Kursus</div>
            </div>
        </div>

        <div class="section-body">
            @include('layouts.alert')

            <!-- Statistik Singkat -->
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 stat-card-custom">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-hourglass-half text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Menunggu Verifikasi</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalPending }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 stat-card-custom">
                        <div class="card-icon bg-success">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pembayaran Berhasil</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalPaid }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 stat-card-custom">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-money-bill-wave text-white"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Pendapatan</h4>
                            </div>
                            <div class="card-body">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data Transaksi -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <!-- Filter Status -->
                    <div class="btn-group mb-2 mb-md-0" role="group">
                        <a href="{{ route('dashboard.transactions.index') }}" class="btn btn-sm {{ !$status ? 'btn-primary' : 'btn-outline-primary' }}">
                            Semua ({{ $transactions->total() }})
                        </a>
                        <a href="{{ route('dashboard.transactions.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-warning text-dark font-weight-bold' : 'btn-outline-warning' }}">
                            Menunggu Verifikasi ({{ $totalPending }})
                        </a>
                        <a href="{{ route('dashboard.transactions.index', ['status' => 'paid']) }}" class="btn btn-sm {{ $status === 'paid' ? 'btn-success' : 'btn-outline-success' }}">
                            Lunas ({{ $totalPaid }})
                        </a>
                        <a href="{{ route('dashboard.transactions.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ $status === 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
                            Dibatalkan
                        </a>
                    </div>

                    <!-- Pencarian -->
                    <form action="{{ route('dashboard.transactions.index') }}" method="GET" class="form-inline">
                        @if($status)
                            <input type="hidden" name="status" value="{{ $status }}">
                        @endif
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="No. Invoice / Siswa / Kelas..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary btn-sm" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-md">
                            <thead>
                                <tr>
                                    <th>No. Invoice</th>
                                    <th>Siswa</th>
                                    <th>Kursus</th>
                                    <th>Pembayaran</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('dashboard.transactions.show', $item->id) }}" class="font-weight-600 text-primary">
                                                {{ $item->invoice_code }}
                                            </a>
                                            <div class="text-muted small">
                                                {{ $item->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="font-weight-600 text-dark">{{ $item->buyer_name }}</div>
                                            @if($item->buyer_phone && $item->buyer_phone !== '-')
                                                @php
                                                    $cleanPhone = preg_replace('/[^0-9]/', '', $item->buyer_phone);
                                                    if (str_starts_with($cleanPhone, '0')) {
                                                        $cleanPhone = '62' . substr($cleanPhone, 1);
                                                    }
                                                @endphp
                                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="badge badge-success small py-1 px-2 mt-1">
                                                    <i class="fab fa-whatsapp mr-1"></i> {{ $item->buyer_phone }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="font-weight-600">
                                                {{ Str::limit($item->course->title ?? 'Kursus Terhapus', 35) }}
                                            </div>
                                            @if($item->voucher_code)
                                                <span class="badge badge-info small mt-1">
                                                    <i class="fas fa-tag mr-1"></i> {{ $item->voucher_code }} (-{{ (int)$item->discount_percent }}%)
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->payment_method === 'free_voucher' || $item->total_amount == 0)
                                                <span class="badge badge-light border text-muted">Gratis / Voucher</span>
                                            @else
                                                <div class="font-weight-600 small">{{ $item->bank_name ?? 'Transfer Bank' }}</div>
                                                <div class="text-muted small">{{ $item->bank_account }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->total_amount == 0)
                                                <span class="text-success font-weight-bold">GRATIS</span>
                                            @else
                                                <div class="font-weight-bold text-dark">
                                                    Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                                                </div>
                                                @if($item->unique_code > 0)
                                                    <div class="small text-muted">Kode Unik: +{{ $item->unique_code }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->isPending())
                                                <span class="badge badge-status-pending">Menunggu Transfer</span>
                                            @elseif ($item->isPaid())
                                                <span class="badge badge-status-paid">
                                                    <i class="fas fa-check-circle mr-1"></i> Lunas
                                                </span>
                                                @if($item->paid_at)
                                                    <div class="small text-muted">{{ $item->paid_at->format('d/m/y H:i') }}</div>
                                                @endif
                                            @else
                                                <span class="badge badge-status-cancelled">Dibatalkan</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <!-- Detail -->
                                                <a href="{{ route('dashboard.transactions.show', $item->id) }}" class="btn btn-sm btn-info btn-action-icon" title="Lihat Rincian">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if ($item->isPending())
                                                    <!-- Tombol Approve Pembayaran -->
                                                    <form action="{{ route('dashboard.transactions.approve', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui transaksi {{ $item->invoice_code }}? Siswa akan otomatis didaftarkan ke kelas.')" style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success btn-action-icon" title="Verifikasi / Setujui Pembayaran">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Tombol Tolak / Batalkan -->
                                                    <button type="button" class="btn btn-sm btn-danger btn-action-icon" title="Batalkan Pesanan" data-toggle="modal" data-target="#rejectModal{{ $item->id }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <!-- Modal Tolak -->
                                                    <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('dashboard.transactions.reject', $item->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title text-danger">Batalkan Transaksi</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-left">
                                                                        <p>Yakin ingin membatalkan transaksi <strong>{{ $item->invoice_code }}</strong> a.n. <strong>{{ $item->buyer_name }}</strong>?</p>
                                                                        <div class="form-group mb-0">
                                                                            <label>Alasan Pembatalan (Opsional):</label>
                                                                            <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Bukti transfer palsu / tidak ada transfer masuk"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                        <button type="submit" class="btn btn-danger">Batalkan Transaksi</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-3x mb-2 text-secondary"></i>
                                            <div>Belum ada data transaksi yang sesuai filter.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($transactions->hasPages())
                    <div class="card-footer text-right">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
