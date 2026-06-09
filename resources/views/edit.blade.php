@extends('layouts.app')

@section('title', 'Edit Menu')

@section('content')
<div style="max-width: 600px; margin: 40px auto; padding: 20px;">
    <h2 style="margin-bottom: 20px; color: #333;">✏️ Edit Menu: {{ $menu->nama_menu }}</h2>

    <form action="{{ route('menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data" 
          style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        @csrf
        @method('PUT') 

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Menu</label>
            <input type="text" name="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            @error('nama_menu') <small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Harga (Rp)</label>
            <input type="number" name="harga" value="{{ old('harga', $menu->harga) }}" 
                   style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
            @error('harga') <small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Kategori</label>
            <select name="kategori" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                <option value="Menu Harian" {{ old('kategori', $menu->kategori) == 'Menu Harian' ? 'selected' : '' }}>Menu Harian</option>
                <option value="Menu Diet" {{ old('kategori', $menu->kategori) == 'Menu Diet' ? 'selected' : '' }}>Menu Diet</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Foto Menu (Kosongkan jika tidak diganti)</label>
            @if($menu->gambar)
                <div style="margin-bottom: 10px;">
                    <small>Foto saat ini:</small><br>
                    <img src="{{ asset('storage/' . $menu->gambar) }}" alt="Foto Lama" width="100" style="border-radius: 5px; margin-top: 5px;">
                </div>
            @endif
            <input type="file" name="foto" style="width: 100%;">
            @error('gambar') <br><small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="flex: 1; background: #f39c12; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                Perbarui Menu
            </button>
            <a href="{{ route('menu') }}" style="flex: 1; background: #eee; color: #333; text-decoration: none; padding: 12px; border-radius: 8px; text-align: center;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection