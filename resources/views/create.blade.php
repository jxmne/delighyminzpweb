@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection
@section('title', 'Tambah Menu Baru')

@section('content')
<div class="container" style="padding: 20px; max-width: 600px; margin: auto;">
    <h2 style="margin-bottom: 20px;">➕ Tambah Menu</h2>

    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data" 
          style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        @csrf

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Menu</label>
            <input type="text" name="nama_menu" value="{{ old('nama_menu') }}" 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;" 
                   placeholder="Contoh: Nasi Tumpeng Mini">
            @error('nama_menu') <small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Harga (Rp)</label>
            <input type="number" name="harga" value="{{ old('harga') }}" 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;" 
                   placeholder="15000">
            @error('harga') <small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Kategori</label>
            <select name="kategori" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 8px;">
                <option value="Menu Harian" {{ old('kategori') == 'Menu Harian' ? 'selected' : '' }}>Menu Harian</option>
                <option value="Menu Diet" {{ old('kategori') == 'Menu Diet' ? 'selected' : '' }}>Menu Diet</option>
            </select>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Foto Menu</label>
            <input type="file" name="gambar" style="width: 100%;">
            <small style="color: #666;">Format: JPG/PNG, Max: 2MB</small>
            @error('gambar') <br><small style="color: red;">{{ $message }}</small> @enderror
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="background: #12b368; color: white; border: none; padding: 12px 20px; border-radius: 8px; cursor: pointer; font-weight: bold; flex: 1;">
                Simpan Menu
            </button>
            <a href="{{ route('menu') }}" style="background: #f0f0f0; color: #333; text-decoration: none; padding: 12px 20px; border-radius: 8px; text-align: center; flex: 1;">
                Batal
            </a>
        </div>
    </form>
</div>

@if($errors->any())
    <div style="background: #fee; border: 1px solid #fcc; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
        <ul style="margin: 0; color: red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection