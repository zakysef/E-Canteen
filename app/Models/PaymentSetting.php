<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'label',
        'account_name',
        'account_number',
        'qr_code',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getQrCodeUrlAttribute(): ?string
    {
        return $this->qr_code ? asset('storage/' . $this->qr_code) : null;
    }

    public static function activeList()
    {
        return static::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
    }
}
