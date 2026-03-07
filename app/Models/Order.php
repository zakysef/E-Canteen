<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_order',
        'user_id',
        'seller_id',
        'total_harga',
        'status',
        'waktu_pengambilan',
        'catatan',
        'paid_at',
        'ready_at',
        'completed_at',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'paid_at'     => 'datetime',
        'ready_at'    => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->kode_order = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Dibayar',
            'preparing'  => 'Sedang Disiapkan',
            'ready'      => 'Siap Diambil',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'yellow',
            'paid'       => 'blue',
            'preparing'  => 'indigo',
            'ready'      => 'green',
            'completed'  => 'gray',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }
}
