<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::where('seller_id', auth()->id())->latest()->paginate(12);

        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'kategori'  => ['required', 'in:makanan,minuman,snack'],
            'stok'      => ['required', 'integer', 'min:0'],
            'foto'      => ['nullable', 'url', 'max:500'],
        ]);

        $data['seller_id'] = auth()->id();
        $data['status']    = $data['stok'] > 0 ? 'tersedia' : 'habis';

        Menu::create($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        abort_if($menu->seller_id !== auth()->id(), 403);

        return view('admin.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        abort_if($menu->seller_id !== auth()->id(), 403);

        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'kategori'  => ['required', 'in:makanan,minuman,snack'],
            'stok'      => ['required', 'integer', 'min:0'],
            'foto'      => ['nullable', 'url', 'max:500'],
        ]);

        $data['status'] = $data['stok'] > 0 ? 'tersedia' : 'habis';

        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        abort_if($menu->seller_id !== auth()->id(), 403);

        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus.');
    }

    public function toggleStatus(Menu $menu)
    {
        abort_if($menu->seller_id !== auth()->id(), 403);

        // Toggle: jika tersedia → set stok 0 (habis), jika habis → tidak ada aksi dari sini
        $menu->update([
            'stok'   => 0,
            'status' => 'habis',
        ]);

        return back()->with('success', "Menu '{$menu->nama}' ditandai habis.");
    }

    public function updateStok(Request $request, Menu $menu)
    {
        abort_if($menu->seller_id !== auth()->id(), 403);

        $data = $request->validate([
            'stok' => ['required', 'integer', 'min:0'],
        ]);

        $menu->update([
            'stok'   => $data['stok'],
            'status' => $data['stok'] > 0 ? 'tersedia' : 'habis',
        ]);

        return back()->with('success', "Stok '{$menu->nama}' diperbarui menjadi {$data['stok']}.");
    }
}
