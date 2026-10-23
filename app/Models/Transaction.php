<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'invoice_code',
        'base_price',
        'discount_amount',
        'discount_percent',
        'voucher_code',
        'unique_code',
        'total_amount',
        'payment_method',
        'bank_name',
        'bank_account',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'status',
        'paid_at',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'base_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Setujui transaksi dan otomatis daftarkan user ke kelas
     */
    public function markAsPaid($adminId = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'approved_by' => $adminId,
        ]);

        Enrollment::firstOrCreate([
            'user_id' => $this->user_id,
            'course_id' => $this->course_id,
        ], [
            'enrolled_at' => now(),
        ]);
    }

    /**
     * Batalkan transaksi
     */
    public function markAsCancelled(?string $notes = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $notes ?? $this->notes,
        ]);
    }
}
