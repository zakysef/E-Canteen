<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TopupRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $transactions = Transaction::where('user_id', $user->id)
            ->latest()->paginate(20);

        $topup_requests = TopupRequest::where('user_id', $user->id)
            ->latest()->take(5)->get();

        return view('user.saldo.index', compact('user', 'transactions', 'topup_requests'));
    }

    public function topupForm()
    {
        return view('user.saldo.topup');
    }

    public function topupStore(Request $request)
    {
        $data = $request->validate([
            'jumlah'        => ['required', 'numeric', 'min:10000', 'max:5000000'],
            'metode'        => ['required', 'in:transfer_bank,e_wallet,tunai'],
            'nama_pengirim' => ['required', 'string', 'max:100'],
            'bukti_transfer' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('bukti_transfer')->store('bukti-topup', 'public');

        TopupRequest::create([
            'user_id'        => auth()->id(),
            'jumlah'         => $data['jumlah'],
            'metode'         => $data['metode'],
            'nama_pengirim'  => $data['nama_pengirim'],
            'bukti_transfer' => $path,
        ]);

        return redirect()->route('user.saldo')->with('success', 'Permintaan top up berhasil dikirim. Menunggu konfirmasi admin.');
    }
}
