<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $requests = WithdrawalRequest::where('seller_id', auth()->id())
            ->latest()->paginate(15);

        return view('admin.withdrawal.index', compact('requests'));
    }

    public function store(Request $request)
    {
        $seller = auth()->user();

        $data = $request->validate([
            'jumlah'           => ['required', 'numeric', 'min:10000', "max:{$seller->saldo}"],
            'metode_pembayaran' => ['required', 'string', 'max:50'],
            'nomor_rekening'   => ['required', 'string', 'max:30'],
            'atas_nama'        => ['required', 'string', 'max:100'],
        ]);

        // Cek tidak ada request pending
        if ($seller->withdrawalRequests()->where('status', 'pending')->exists()) {
            return back()->with('error', 'Anda masih memiliki permintaan pencairan yang sedang diproses.');
        }

        $data['seller_id'] = $seller->id;
        WithdrawalRequest::create($data);

        return back()->with('success', 'Permintaan pencairan berhasil diajukan.');
    }
}
