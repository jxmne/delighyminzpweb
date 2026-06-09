<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        // $menu = \App\Models\Menu::all();
        $menu = Menu::paginate(10); 
        return view('menu', compact('menu'));
    }

    // Form Tambah Menu (Hanya Admin)
    public function create()
    {
        return view('create');
    }

    // Simpan Menu Baru (Hanya Admin)
    public function store(Request $request)
    {
        // Validasi + upload foto
        $request->validate([
            'nama_menu' => 'required|min:3',
            'harga' => 'required|numeric',
            'kategori' => 'required|in:Menu Harian,Menu Diet',
            'gambar' => 'image|mimes:jpg,png|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('image', 'public');
        }

        Menu::create($data);
        return redirect()->route('menu')->with('success', 'Menu berhasil ditambahkan!');
    }

    // Form Edit (Hanya Admin)
    public function edit(Menu $menu)
    {
        return view('edit', compact('menu'));
    }

    // Update Data (Hanya Admin)
    public function update(Request $request, Menu $menu)
    {
        // Validasi 
        $request->validate([
            'nama_menu' => 'required|min:3|unique:menus,nama_menu,' . $menu->id, 
            'harga' => 'required|numeric',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            if ($menu->gambar) Storage::delete('public/'.$menu->gambar);
            $data['gambar'] = $request->file('gambar')->store('image', 'public');
        }

        $menu->update($data);
        return redirect()->route('menu')->with('success', 'Menu berhasil diperbarui!');
    }

    // Hapus Menu (Hanya Admin)
    public function destroy(Menu $menu)
    {
        if ($menu->gambar) Storage::delete('public/'.$menu->gambar);
        $menu->delete();
        return redirect()->route('menu')->with('success', 'Menu dihapus!');
    }

    public function search(Request $request)
    {
        $keyword  = $request->keyword;
        $kategori = $request->kategori ? explode(',', $request->kategori) : [];

        $query = Menu::query();

        if ($keyword) {
            $query->where('nama_menu', 'like', "%$keyword%");
        }

        if (!empty($kategori)) {
            $query->whereIn('kategori', $kategori);
        }

        return response()->json($query->get());
    }
}