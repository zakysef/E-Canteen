<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'nama',
        'deskripsi',
        'harga',
        'foto',
        'kategori',
        'status',
        'stok',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia')->where('stok', '>', 0);
    }

    public function getFotoUrlAttribute(): string
    {
        if (!$this->foto) {
            return asset('images/menu-default.png');
        }

        if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
            return $this->foto;
        }

        return asset('storage/' . $this->foto);
    }
}
