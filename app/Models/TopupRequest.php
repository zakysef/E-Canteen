<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jumlah',
        'metode',
        'bukti_transfer',
        'nama_pengirim',
        'status',
        'approved_by',
        'catatan_admin',
        'approved_at',
    ];

    protected $casts = [
        'jumlah'      => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getBuktiUrlAttribute(): ?string
    {
        return $this->bukti_transfer ? asset('storage/' . $this->bukti_transfer) : null;
    }
}
