<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TopupRequest;
use App\Models\User;
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

    // ─── Cash Top-Up (Tunai via Bendahara) ────────────────────────────────────

    public function cashIndex(Request $request)
    {
        $search = $request->search;
        $users  = User::where('role', 'user')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('identifier', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.topup.cash', compact('users', 'search'));
    }

    public function cashTopup(Request $request, User $user)
    {
        $data = $request->validate([
            'jumlah'  => ['required', 'integer', 'min:1000', 'max:10000000'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        abort_unless($user->role === 'user', 403, 'Hanya akun siswa/guru yang bisa menerima top-up tunai.');

        DB::transaction(function () use ($user, $data) {
            // Create a record for traceability
            $topup = TopupRequest::create([
                'user_id'        => $user->id,
                'jumlah'         => $data['jumlah'],
                'metode'         => 'tunai',
                'nama_pengirim'  => $user->name,
                'status'         => 'approved',
                'approved_by'    => auth()->id(),
                'catatan_admin'  => $data['catatan'] ?? 'Top-up tunai oleh bendahara',
                'approved_at'    => now(),
            ]);

            $user->tambahSaldo(
                $data['jumlah'],
                ($data['catatan'] ?? 'Top-up tunai') . " - #{$topup->id}",
                $topup
            );
        });

        return back()->with('success', "Saldo Rp " . number_format($data['jumlah'], 0, ',', '.') . " berhasil ditambahkan ke akun {$user->name}.");
    }
}
