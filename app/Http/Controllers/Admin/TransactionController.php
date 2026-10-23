<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Tampilkan daftar transaksi kursus
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Transaction::with(['user', 'course', 'approver'])->latest();

        if ($status && in_array($status, ['pending', 'paid', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_code', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_email', 'like', "%{$search}%")
                  ->orWhere('buyer_phone', 'like', "%{$search}%")
                  ->orWhereHas('course', function ($cq) use ($search) {
                      $cq->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->paginate(15)->withQueryString();

        // Hitung statistik
        $totalPending = Transaction::where('status', 'pending')->count();
        $totalPaid = Transaction::where('status', 'paid')->count();
        $totalRevenue = Transaction::where('status', 'paid')->sum('total_amount');

        $type_menu = 'transactions';

        return view('pages.dashboard.transactions.index', compact(
            'transactions',
            'totalPending',
            'totalPaid',
            'totalRevenue',
            'type_menu',
            'status',
            'search'
        ));
    }

    /**
     * Tampilkan detail transaksi
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'course', 'approver']);
        $type_menu = 'transactions';

        return view('pages.dashboard.transactions.show', compact('transaction', 'type_menu'));
    }

    /**
     * Setujui pembayaran (Approve) & otomatis enroll user ke kursus
     */
    public function approve(Request $request, Transaction $transaction)
    {
        if ($transaction->isPaid()) {
            return redirect()->back()->with('info', 'Transaksi ini sudah disetujui sebelumnya.');
        }

        $transaction->markAsPaid(auth()->id());

        return redirect()->back()->with('success', "Transaksi {$transaction->invoice_code} berhasil diverifikasi! Peserta {$transaction->buyer_name} otomatis terdaftar di kelas {$transaction->course->title}.");
    }

    /**
     * Tolak/Batalkan transaksi
     */
    public function reject(Request $request, Transaction $transaction)
    {
        $request->validate([
            'notes' => 'nullable|string|max:255',
        ]);

        $transaction->markAsCancelled($request->input('notes', 'Pembayaran tidak valid atau dibatalkan oleh admin.'));

        return redirect()->back()->with('warning', "Transaksi {$transaction->invoice_code} telah dibatalkan.");
    }
}
