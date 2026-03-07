<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipe',
        'jumlah',
        'saldo_sebelum',
        'saldo_sesudah',
        'keterangan',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'jumlah'        => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe) {
            'topup'      => 'Top Up',
            'debit'      => 'Pembayaran Order',
            'refund'     => 'Refund',
            'income'     => 'Pendapatan',
            'withdrawal' => 'Pencairan Dana',
            default      => $this->tipe,
        };
    }
}
