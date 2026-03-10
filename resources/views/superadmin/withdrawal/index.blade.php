@extends('layouts.superadmin')

@section('title', 'Permintaan Pencairan')
@section('page-title', 'Permintaan Pencairan Dana')

@section('content-inner')
<div class="flex gap-2 mb-6">
    @foreach(['' => 'Semua', 'pending' => 'Pending', 'approved' => 'Disetujui', 'transferred' => 'Ditransfer', 'rejected' => 'Ditolak'] as $val => $label)
    <a href="{{ route('superadmin.withdrawal.index', ['status' => $val]) }}"
        class="px-4 py-1.5 rounded-full text-sm font-medium border {{ request('status', '') === $val ? 'bg-rose-600 text-white border-rose-600' : 'border-gray-300 text-gray-600 hover:bg-gray-50' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Penjual</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tujuan Transfer</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                <th class="text-center px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($requests as $req)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3.5">
                    <p class="font-medium text-gray-800">{{ $req->seller->nama_toko ?? $req->seller->name }}</p>
                    <p class="text-xs text-gray-500">{{ $req->seller->email }}</p>
                </td>
                <td class="px-6 py-3.5 font-semibold text-rose-700">Rp {{ number_format($req->jumlah, 0, ',', '.') }}</td>
                <td class="px-6 py-3.5">
                    <p class="text-gray-800">{{ $req->metode_pembayaran }}</p>
                    <p class="text-xs text-gray-500">{{ $req->nomor_rekening }} — {{ $req->atas_nama }}</p>
                </td>
                <td class="px-6 py-3.5 text-gray-500 text-xs">{{ $req->created_at->format('d M Y H:i') }}</td>
                <td class="px-6 py-3.5 text-center">
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium
                        {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           ($req->status === 'approved' ? 'bg-blue-100 text-blue-700' :
                           ($req->status === 'transferred' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')) }}">
                        {{ ucfirst($req->status) }}
                    </span>
                </td>
                <td class="px-6 py-3.5">
                    <a href="{{ route('superadmin.withdrawal.show', $req) }}" class="text-xs text-rose-600 hover:underline">Detail</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Tidak ada permintaan pencairan.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $requests->links() }}</div>
</div>
@endsection
