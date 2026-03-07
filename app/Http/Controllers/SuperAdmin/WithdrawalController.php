<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $requests = WithdrawalRequest::with('seller')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.withdrawal.index', compact('requests'));
    }

    public function show(WithdrawalRequest $withdrawalRequest)
    {
        return view('superadmin.withdrawal.show', compact('withdrawalRequest'));
    }

    public function approve(WithdrawalRequest $withdrawalRequest)
    {
        if ($withdrawalRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $withdrawalRequest->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', "Permintaan pencairan disetujui. Silakan lakukan transfer dan upload bukti.");
    }

    public function transfer(Request $request, WithdrawalRequest $withdrawalRequest)
    {
        $request->validate([
            'bukti_transfer' => ['required', 'image', 'max:2048'],
        ]);

        if ($withdrawalRequest->status !== 'approved') {
            return back()->with('error', 'Permintaan harus disetujui terlebih dahulu.');
        }

        DB::transaction(function () use ($request, $withdrawalRequest) {
            $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');

            $withdrawalRequest->update([
                'status'         => 'transferred',
                'bukti_transfer' => $path,
                'transferred_at' => now(),
            ]);

            $withdrawalRequest->seller->kurangiSaldo(
                $withdrawalRequest->jumlah,
                'withdrawal',
                "Pencairan dana diproses - #{$withdrawalRequest->id}",
                $withdrawalRequest
            );
        });

        return back()->with('success', "Transfer berhasil dikonfirmasi.");
    }

    public function reject(Request $request, WithdrawalRequest $withdrawalRequest)
    {
        $request->validate(['catatan_admin' => ['required', 'string']]);

        $withdrawalRequest->update([
            'status'        => 'rejected',
            'approved_by'   => auth()->id(),
            'catatan_admin' => $request->catatan_admin,
            'approved_at'   => now(),
        ]);

        return back()->with('success', 'Permintaan pencairan ditolak.');
    }
}
