<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'saldo',
        'phone',
        'identifier',
        'kelas',
        'nama_toko',
        'nomor_rekening',
        'nama_bank',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'saldo'             => 'decimal:2',
            'is_active'         => 'boolean',
        ];
    }

    // --- Role helpers ---
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Admin',
            'admin'       => $this->nama_toko ? "Penjual · {$this->nama_toko}" : 'Penjual',
            'user'        => $this->kelas ? "Jurusan {$this->kelas}" : 'Siswa/Guru',
            default       => $this->role,
        };
    }

    // --- Relationships ---
    public function menus()
    {
        return $this->hasMany(Menu::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function sellerOrders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function topupRequests()
    {
        return $this->hasMany(TopupRequest::class, 'user_id');
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class, 'seller_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    // --- Saldo helpers ---
    public function tambahSaldo(float $jumlah, string $keterangan, $reference = null, string $tipe = 'topup'): void
    {
        $sebelum = (float) $this->saldo;
        $this->increment('saldo', $jumlah);
        $this->refresh();
        Transaction::create([
            'user_id'        => $this->id,
            'tipe'           => $tipe,
            'jumlah'         => $jumlah,
            'saldo_sebelum'  => $sebelum,
            'saldo_sesudah'  => (float) $this->saldo,
            'keterangan'     => $keterangan,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id'   => $reference?->id,
        ]);
    }

    public function kurangiSaldo(float $jumlah, string $tipe, string $keterangan, $reference = null): void
    {
        $sebelum = (float) $this->saldo;
        $this->decrement('saldo', $jumlah);
        $this->refresh();
        Transaction::create([
            'user_id'        => $this->id,
            'tipe'           => $tipe,
            'jumlah'         => $jumlah,
            'saldo_sebelum'  => $sebelum,
            'saldo_sesudah'  => (float) $this->saldo,
            'keterangan'     => $keterangan,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id'   => $reference?->id,
        ]);
    }
}
