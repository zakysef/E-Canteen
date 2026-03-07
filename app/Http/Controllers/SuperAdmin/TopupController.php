<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TopupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopupController extends Controller
{
    public function index(Request $request)
    {
        $requests = TopupRequest::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.topup.index', compact('requests'));
    }

    public function show(TopupRequest $topupRequest)
    {
        return view('superadmin.topup.show', compact('topupRequest'));
    }

    public function approve(Request $request, TopupRequest $topupRequest)
    {
        if ($topupRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        DB::transaction(function () use ($topupRequest) {
            $topupRequest->update([
                'status'      => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $topupRequest->user->tambahSaldo(
                $topupRequest->jumlah,
                "Top up disetujui - #{$topupRequest->id}",
                $topupRequest
            );
        });

        return back()->with('success', "Top up Rp " . number_format($topupRequest->jumlah, 0, ',', '.') . " berhasil disetujui.");
    }

    public function reject(Request $request, TopupRequest $topupRequest)
    {
        $request->validate(['catatan_admin' => ['required', 'string']]);

        if ($topupRequest->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses.');
        }

        $topupRequest->update([
            'status'        => 'rejected',
            'approved_by'   => auth()->id(),
            'catatan_admin' => $request->catatan_admin,
            'approved_at'   => now(),
        ]);

        return back()->with('success', 'Permintaan top up ditolak.');
    }
}
