@extends('layouts.user')

@section('title', 'Katalog Menu')

@section('content-inner')
<div x-data="catalog()" x-init="init()">
    <h2 class="text-xl font-bold text-gray-800 mb-5">Pilih Menu Pre-Order</h2>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <select name="seller" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
            <option value="">Semua Penjual</option>
            @foreach($sellers as $seller)
            <option value="{{ $seller->id }}" {{ request('seller') == $seller->id ? 'selected' : '' }}>
                {{ $seller->nama_toko ?? $seller->name }}
            </option>
            @endforeach
        </select>
        <select name="kategori" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
            <option value="">Semua Kategori</option>
            <option value="makanan"  {{ request('kategori') === 'makanan'  ? 'selected' : '' }}>Makanan</option>
            <option value="minuman"  {{ request('kategori') === 'minuman'  ? 'selected' : '' }}>Minuman</option>
            <option value="snack"    {{ request('kategori') === 'snack'    ? 'selected' : '' }}>Snack</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu..."
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1 min-w-40 focus:outline-none focus:ring-2 focus:ring-pink-300">
        <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-pink-700 transition-colors">Cari</button>
    </form>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-6">
        @forelse($menus as $menu)
        @php $sisa = $menu->stok; @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="relative h-40 bg-pink-50 flex items-center justify-center overflow-hidden">
                @if($menu->foto)
                    <img src="{{ $menu->foto_url }}" alt="{{ $menu->nama }}" class="w-full h-full object-cover">
                @else
                    <i class="{{ $menu->kategori === 'minuman' ? 'ph ph-cup' : ($menu->kategori === 'snack' ? 'ph ph-cookie' : 'ph ph-bowl-food') }} text-4xl text-rose-300"></i>
                @endif

                {{-- Stok badge overlay --}}
                @if($sisa <= 0)
                    <div class="absolute inset-0 bg-gray-900/60 flex items-center justify-center">
                        <span class="text-white font-bold text-sm bg-red-500 px-3 py-1 rounded-full">Habis</span>
                    </div>
                @elseif($sisa <= 5)
                    <span class="absolute top-2 right-2 text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                        Sisa {{ $sisa }}
                    </span>
                @else
                    <span class="absolute top-2 right-2 text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700 border border-green-200">
                        Sisa {{ $sisa }}
                    </span>
                @endif
            </div>
            <div class="p-4">
                <span class="text-xs text-pink-500 font-medium uppercase">{{ $menu->kategori }}</span>
                <h3 class="font-semibold text-gray-800 mt-0.5">{{ $menu->nama }}</h3>
                <p class="text-xs text-gray-400">{{ $menu->seller->nama_toko ?? $menu->seller->name }}</p>
                @if($menu->deskripsi)
                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $menu->deskripsi }}</p>
                @endif
                <div class="flex items-center justify-between mt-3">
                    <span class="font-bold text-pink-600">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                    @if($sisa > 0)
                    <div class="flex items-center gap-2">
                        <button @click="decrement({{ $menu->id }})"
                                class="w-7 h-7 bg-gray-100 rounded-full text-gray-600 hover:bg-gray-200 font-bold text-lg leading-none flex items-center justify-center">-</button>
                        <span x-text="getQty({{ $menu->id }})" class="w-5 text-center text-sm font-semibold">0</span>
                        <button @click="increment({{ $menu->id }}, {{ $menu->seller_id }}, '{{ addslashes($menu->nama) }}', {{ $menu->harga }}, {{ $sisa }})"
                                :disabled="getQty({{ $menu->id }}) >= {{ $sisa }}"
                                :class="getQty({{ $menu->id }}) >= {{ $sisa }} ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-pink-500 text-white hover:bg-pink-600'"
                                class="w-7 h-7 rounded-full font-bold text-lg leading-none flex items-center justify-center transition-colors">+</button>
                    </div>
                    @else
                    <span class="text-xs text-red-400 font-medium">Tidak tersedia</span>
                    @endif
                </div>
                @if($sisa > 0 && $sisa <= 5)
                <p class="text-[10px] text-amber-600 font-medium mt-1.5 flex items-center gap-1">
                    <i class="ph ph-warning text-amber-500 text-xs"></i> Stok hampir habis!
                </p>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-4 py-16 text-center">
            <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-magnifying-glass text-pink-400 text-3xl"></i>
            </div>
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
                <button type="submit" class="bg-pink-500 text-white px-5 py-2 rounded-xl font-semibold hover:bg-pink-600 text-sm transition-colors">
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
        increment(menuId, sellerId, nama, harga, stok) {
            if (this.cart.length > 0 && this.cart[0].seller_id !== sellerId) {
                if (!confirm('Menambah dari penjual berbeda akan menghapus keranjang. Lanjutkan?')) return;
                this.cart = [];
            }
            const i = this.cart.find(c => c.menu_id === menuId);
            const currentQty = i ? i.qty : 0;
            if (currentQty >= stok) return; // jangan melebihi stok
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
