<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'status'    => ['required', 'in:tersedia,habis'],
            'stok'      => ['nullable', 'integer', 'min:0'],
            'foto'      => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menus', 'public');
        }

        $data['seller_id'] = auth()->id();
        $data['stok']      = $data['stok'] ?? 0;

        Menu::create($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $this->authorize('update', $menu);

        return view('admin.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('update', $menu);

        $data = $request->validate([
            'nama'      => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'kategori'  => ['required', 'in:makanan,minuman,snack'],
            'status'    => ['required', 'in:tersedia,habis'],
            'stok'      => ['nullable', 'integer', 'min:0'],
            'foto'      => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            if ($menu->foto) Storage::disk('public')->delete($menu->foto);
            $data['foto'] = $request->file('foto')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('delete', $menu);

        if ($menu->foto) Storage::disk('public')->delete($menu->foto);
        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus.');
    }

    public function toggleStatus(Menu $menu)
    {
        $this->authorize('update', $menu);

        $menu->update(['status' => $menu->status === 'tersedia' ? 'habis' : 'tersedia']);

        return back()->with('success', "Status menu '{$menu->nama}' diperbarui.");
    }
}
