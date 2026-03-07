<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'jumlah',
        'metode_pembayaran',
        'nomor_rekening',
        'atas_nama',
        'status',
        'approved_by',
        'catatan_admin',
        'bukti_transfer',
        'approved_at',
        'transferred_at',
    ];

    protected $casts = [
        'jumlah'         => 'decimal:2',
        'approved_at'    => 'datetime',
        'transferred_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
