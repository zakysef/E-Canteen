@extends('layouts.user')

@section('title', 'Katalog Menu')

@section('content-inner')
<div x-data="catalog()" x-init="init()">
    <h2 class="text-xl font-bold text-gray-800 mb-5">Pilih Menu Pre-Order</h2>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <select name="seller" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            <option value="">Semua Penjual</option>
            @foreach($sellers as $seller)
            <option value="{{ $seller->id }}" {{ request('seller') == $seller->id ? 'selected' : '' }}>
                {{ $seller->nama_toko ?? $seller->name }}
            </option>
            @endforeach
        </select>
        <select name="kategori" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            <option value="">Semua Kategori</option>
            <option value="makanan"  {{ request('kategori') === 'makanan'  ? 'selected' : '' }}>🍱 Makanan</option>
            <option value="minuman"  {{ request('kategori') === 'minuman'  ? 'selected' : '' }}>🥤 Minuman</option>
            <option value="snack"    {{ request('kategori') === 'snack'    ? 'selected' : '' }}>🍿 Snack</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu..."
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1 min-w-40 focus:outline-none focus:ring-2 focus:ring-orange-300">
        <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium">Cari</button>
    </form>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-6">
        @forelse($menus as $menu)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-40 bg-orange-50 flex items-center justify-center overflow-hidden">
                @if($menu->foto)
                    <img src="{{ $menu->foto_url }}" alt="{{ $menu->nama }}" class="w-full h-full object-cover">
                @else
                    <span class="text-4xl">{{ $menu->kategori === 'minuman' ? '🥤' : ($menu->kategori === 'snack' ? '🍿' : '🍱') }}</span>
                @endif
            </div>
            <div class="p-4">
                <span class="text-xs text-orange-500 font-medium uppercase">{{ $menu->kategori }}</span>
                <h3 class="font-semibold text-gray-800 mt-0.5">{{ $menu->nama }}</h3>
                <p class="text-xs text-gray-400">{{ $menu->seller->nama_toko ?? $menu->seller->name }}</p>
                @if($menu->deskripsi)
                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $menu->deskripsi }}</p>
                @endif
                <div class="flex items-center justify-between mt-3">
                    <span class="font-bold text-orange-600">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                    <div class="flex items-center gap-2">
                        <button @click="decrement({{ $menu->id }})" class="w-7 h-7 bg-gray-100 rounded-full text-gray-600 hover:bg-gray-200 font-bold text-lg leading-none flex items-center justify-center">-</button>
                        <span x-text="getQty({{ $menu->id }})" class="w-5 text-center text-sm font-semibold">0</span>
                        <button @click="increment({{ $menu->id }}, {{ $menu->seller_id }}, '{{ addslashes($menu->nama) }}', {{ $menu->harga }})" class="w-7 h-7 bg-orange-500 rounded-full text-white hover:bg-orange-600 font-bold text-lg leading-none flex items-center justify-center">+</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-4 py-16 text-center">
            <p class="text-5xl mb-4">😔</p>
            <p class="text-gray-500">Tidak ada menu tersedia saat ini.</p>
        </div>
        @endforelse
    </div>

    {{-- Cart Summary (sticky bottom) --}}
    <div x-show="totalItems > 0" x-cloak
        class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white rounded-2xl shadow-2xl px-6 py-4 flex items-center gap-6 z-50">
        <div>
            <p class="text-xs text-gray-400">Total Pesanan</p>
            <p class="font-bold"><span x-text="totalItems"></span> item · Rp <span x-text="formatRupiah(totalPrice)"></span></p>
        </div>
        <form method="POST" action="{{ route('user.checkout') }}" x-ref="cartForm">
            @csrf
            <template x-for="item in cart" :key="item.menu_id">
                <div>
                    <input type="hidden" :name="'items[' + item.menu_id + '][menu_id]'" :value="item.menu_id">
                    <input type="hidden" :name="'items[' + item.menu_id + '][qty]'" :value="item.qty">
                </div>
            </template>
            <div class="flex items-center gap-3">
                <select name="waktu_pengambilan" required
                    class="bg-gray-800 text-white border border-gray-600 rounded-lg px-3 py-1.5 text-sm focus:outline-none">
                    <option value="istirahat_1">Istirahat 1</option>
                    <option value="istirahat_2">Istirahat 2</option>
                </select>
                <button type="submit" class="bg-orange-500 text-white px-5 py-2 rounded-xl font-semibold hover:bg-orange-600 text-sm">
                    Checkout →
                </button>
            </div>
        </form>
        <button @click="clearCart()" class="text-gray-400 hover:text-white text-sm">Batal</button>
    </div>
</div>
@endsection

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
<script>
function catalog() {
    return {
        cart: [],
        get totalItems() { return this.cart.reduce((s, i) => s + i.qty, 0); },
        get totalPrice() { return this.cart.reduce((s, i) => s + (i.harga * i.qty), 0); },
        init() { const s = localStorage.getItem('cart'); if (s) this.cart = JSON.parse(s); },
        save() { localStorage.setItem('cart', JSON.stringify(this.cart)); },
        getQty(menuId) { const i = this.cart.find(c => c.menu_id === menuId); return i ? i.qty : 0; },
        increment(menuId, sellerId, nama, harga) {
            if (this.cart.length > 0 && this.cart[0].seller_id !== sellerId) {
                if (!confirm('Menambah dari penjual berbeda akan menghapus keranjang. Lanjutkan?')) return;
                this.cart = [];
            }
            const i = this.cart.find(c => c.menu_id === menuId);
            if (i) { i.qty++; } else { this.cart.push({ menu_id: menuId, seller_id: sellerId, nama, harga, qty: 1 }); }
            this.save();
        },
        decrement(menuId) {
            const idx = this.cart.findIndex(c => c.menu_id === menuId);
            if (idx === -1) return;
            if (this.cart[idx].qty > 1) { this.cart[idx].qty--; } else { this.cart.splice(idx, 1); }
            this.save();
        },
        clearCart() { this.cart = []; localStorage.removeItem('cart'); },
        formatRupiah(n) { return n.toLocaleString('id-ID'); },
    };
}
</script>
@endpush
