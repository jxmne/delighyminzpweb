@extends('layouts.app')

@section('title', 'Menu')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
@endsection

@section('content')

        <aside id="sidebar-filter">
            <h2>Filter Menu</h2>
            <section class="filter-group">
                <label class="filter-item"><input type="checkbox" value="Menu Harian"> Menu Harian</label>
                <label class="filter-item"><input type="checkbox" value="Menu Diet"> Menu Diet</label>
            </section>
        </aside>

        <article class="menu-container">

            <form class="search-form" action="#">
                <input type="text" id="input-cari" placeholder="Cari menu catering...">
                <button type="submit">Cari</button>
            </form>

            <h2>Daftar Menu</h2>

            <section id="menu-display" class="grid-menu">
                @foreach($menu as $m)
                    <article class="card-menu">
                        <img src="{{ asset('image/' . $m->gambar) }}" alt="{{ $m->nama_menu }}">
                        <section class="card-content">
                            <h4>{{ $m->nama_menu }}</h4>
                            <p>Rp {{ number_format($m->harga, 0, ',', '.') }}</p>
                            <span class="badge-kategori {{ $m->kategori == 'Menu Diet' ? 'menu-diet' : 'menu-harian' }}">{{ $m->kategori }}</span>

                            @if(auth()->check() && auth()->user()->is_admin)
                                <div class="admin-controls" style="margin-top: 10px; display: flex; gap: 5px;">
                                    <button type="button"
                                        onclick="bukaModalEdit({{ $m->id }}, '{{ addslashes($m->nama_menu) }}', {{ $m->harga }}, '{{ $m->kategori }}', '{{ $m->gambar }}')"
                                        style="background:orange;color:white;border:none;padding:5px 10px;border-radius:5px;cursor:pointer;font-size:0.8rem;">✏️ Edit</button>
                                    <form action="{{ route('menu.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:red;color:white;border:none;padding:5px 10px;border-radius:5px;cursor:pointer;font-size:0.8rem;">🗑️ Hapus</button>
                                    </form>
                                </div>
                            @elseif(auth()->check())
                                <button type="button" class="btn-pesan" data-id="{{ $m->id }}" data-nama="{{ $m->nama_menu }}">Pesan Sekarang</button>
                            @else
                                <button type="button" class="btn-pesan" onclick="document.getElementById('modal-guest').showModal()">Pesan Sekarang</button>
                            @endif
                        </section>
                    </article>
                @endforeach
            </section>

            <div class="pagination-wrapper">
                {{ $menu->links() }}
            </div>
        </article>
    </main>

    {{-- MODAL TAMBAH --}}
    <dialog id="modal-tambah" style="border:none;border-radius:15px;padding:30px;box-shadow:0 10px 30px rgba(0,0,0,0.25);width:480px;max-width:95vw;">
        <h3 style="margin-bottom:20px;color:#333;border-left:4px solid #12b368;padding-left:12px;">➕ Tambah Menu</h3>
        @if($errors->any())
        <div style="background:#fee;border:1px solid #fcc;padding:10px;border-radius:8px;margin-bottom:15px;">
            <ul style="margin:0;padding-left:1.2rem;color:#d32f2f;">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif
        <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom:15px;">
                <label style="display:block;font-weight:bold;margin-bottom:5px;">Nama Menu</label>
                <input type="text" name="nama_menu" value="{{ old('nama_menu') }}"
                    style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;"
                    placeholder="Contoh: Nasi Tumpeng Mini">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block;font-weight:bold;margin-bottom:5px;">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga') }}"
                    style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;" placeholder="15000">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block;font-weight:bold;margin-bottom:5px;">Kategori</label>
                <select name="kategori" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;">
                    <option value="Menu Harian" {{ old('kategori') == 'Menu Harian' ? 'selected' : '' }}>Menu Harian</option>
                    <option value="Menu Diet" {{ old('kategori') == 'Menu Diet' ? 'selected' : '' }}>Menu Diet</option>
                </select>
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block;font-weight:bold;margin-bottom:5px;">Foto Menu</label>
                <input type="file" name="gambar" style="width:100%;">
                <small style="color:#888;">Format: JPG/PNG, Max: 2MB</small>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" style="flex:1;background:#12b368;color:white;border:none;padding:12px;border-radius:8px;cursor:pointer;font-weight:bold;">Simpan Menu</button>
                <button type="button" onclick="document.getElementById('modal-tambah').close()" style="flex:1;background:#f0f0f0;color:#333;border:none;padding:12px;border-radius:8px;cursor:pointer;font-weight:bold;">Batal</button>
            </div>
        </form>
    </dialog>

    {{-- MODAL EDIT --}}
    <dialog id="modal-edit" style="border:none;border-radius:15px;padding:30px;box-shadow:0 10px 30px rgba(0,0,0,0.25);width:480px;max-width:95vw;">
        <h3 style="margin-bottom:20px;color:#333;border-left:4px solid #f39c12;padding-left:12px;">✏️ Edit Menu</h3>
        <form id="form-edit" action="" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="margin-bottom:15px;">
                <label style="display:block;font-weight:bold;margin-bottom:5px;">Nama Menu</label>
                <input type="text" id="edit-nama" name="nama_menu"
                    style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block;font-weight:bold;margin-bottom:5px;">Harga (Rp)</label>
                <input type="number" id="edit-harga" name="harga"
                    style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block;font-weight:bold;margin-bottom:5px;">Kategori</label>
                <select id="edit-kategori" name="kategori" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;box-sizing:border-box;">
                    <option value="Menu Harian">Menu Harian</option>
                    <option value="Menu Diet">Menu Diet</option>
                </select>
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block;font-weight:bold;margin-bottom:5px;">Foto Menu (Kosongkan jika tidak diganti)</label>
                <div id="edit-foto-preview" style="margin-bottom:8px;"></div>
                <input type="file" name="foto" style="width:100%;">
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" style="flex:1;background:#f39c12;color:white;border:none;padding:12px;border-radius:8px;cursor:pointer;font-weight:bold;">Perbarui Menu</button>
                <button type="button" onclick="document.getElementById('modal-edit').close()" style="flex:1;background:#f0f0f0;color:#333;border:none;padding:12px;border-radius:8px;cursor:pointer;font-weight:bold;">Batal</button>
            </div>
        </form>
    </dialog>

    {{-- Modal Guest --}}
    <dialog id="modal-guest" style="border:none;border-radius:15px;padding:25px;box-shadow:0 10px 25px rgba(0,0,0,0.2);width:320px;text-align:center;">
        <h3 style="margin-bottom:10px;">🔐 Belum Login</h3>
        <p style="margin-bottom:20px;color:#666;">Silakan login atau daftar terlebih dahulu untuk memesan.</p>
        <div style="display:flex;gap:10px;justify-content:center;">
            <button onclick="window.location.href='{{ route('login') }}'" style="background:#12b368;color:white;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:bold;">Login</button>
            <button onclick="window.location.href='{{ route('register') }}'" style="background:#f0f0f0;color:#333;border:1px solid #ccc;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:bold;">Daftar</button>
        </div>
    </dialog>

    {{-- Modal Opsi --}}
    <dialog id="modal-opsi" style="border:none;border-radius:15px;padding:25px;box-shadow:0 10px 25px rgba(0,0,0,0.2);width:320px;text-align:center;">
        <h3 id="modal-nama-produk" style="margin-bottom:5px;"></h3>
        <p style="font-size:0.9rem;margin-bottom:15px;">Masukkan jumlah dan pilih metode penyajian.</p>
        <div style="margin-bottom:20px;">
            <label style="display:block;font-size:0.85rem;margin-bottom:5px;font-weight:bold;">Jumlah Pesanan:</label>
            <input type="number" id="modal-jumlah" value="1" min="1"
                style="width:80px;padding:8px;border-radius:5px;border:1px solid #ccc;text-align:center;font-size:1rem;">
        </div>
        <div style="display:flex;gap:10px;justify-content:center;">
            <button id="btn-ta" style="background:#12b368;color:white;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:bold;flex:1;">Take Away</button>
            <button id="btn-di" style="background:#f0f0f0;color:#333;border:1px solid #ccc;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:bold;flex:1;">Dine In</button>
        </div>
    </dialog>

@endsection

@section('js')
<script>
    const isAdmin = {{ auth()->check() && auth()->user()->is_admin ? 'true' : 'false' }};
    const isLogin = {{ auth()->check() ? 'true' : 'false' }};
    let tempMenuId = null;

    function bukaModalTambah() {
        document.getElementById('modal-tambah').showModal();
    }

    function bukaModalEdit(id, nama, harga, kategori, gambar) {
        document.getElementById('form-edit').action = `/menu/${id}`;
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-harga').value = harga;
        document.getElementById('edit-kategori').value = kategori;
        const preview = document.getElementById('edit-foto-preview');
        preview.innerHTML = gambar
            ? `<small>Foto saat ini:</small><br><img src="/storage/${gambar}" width="80" style="border-radius:5px;margin-top:4px;">`
            : '';
        document.getElementById('modal-edit').showModal();
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Auto buka modal dari URL param (?modal=tambah)
        const params = new URLSearchParams(window.location.search);
        if (params.get('modal') === 'tambah') {
            document.getElementById('modal-tambah').showModal();
            history.replaceState(null, '', '/menu');
        }
        pasangEventPesan();
    });

    function bukaModalOpsi(menuId, menuNama) {
        tempMenuId = menuId;
        document.getElementById('modal-nama-produk').innerText = menuNama;
        document.getElementById('modal-jumlah').value = 1;
        document.getElementById('modal-opsi').showModal();
    }

    function kirimPesanan(opsi) {
        const jumlah = document.getElementById('modal-jumlah').value;
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('kuantitas', jumlah);
        formData.append('opsi', opsi);
        fetch(`/pesanan/tambah/${tempMenuId}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modal-opsi').close();
                window.location.href = '{{ route("pesanan") }}';
            } else {
                alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(err => alert('Error: ' + err.message));
    }

    function handlePesanClick(e) {
        const btn = e.currentTarget;
        if (!isLogin) {
            document.getElementById('modal-guest').showModal();
        } else {
            bukaModalOpsi(btn.getAttribute('data-id'), btn.getAttribute('data-nama'));
        }
    }

    function pasangEventPesan() {
        document.querySelectorAll('.btn-pesan').forEach(btn => {
            btn.removeEventListener('click', handlePesanClick);
            btn.addEventListener('click', handlePesanClick);
        });
    }

    document.getElementById('btn-ta').addEventListener('click', () => kirimPesanan('Take Away'));
    document.getElementById('btn-di').addEventListener('click', () => kirimPesanan('Dine In'));

    ['modal-tambah','modal-edit','modal-guest','modal-opsi'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('click', e => { if (e.target === el) el.close(); });
    });

    function fetchMenu() {
        const keyword = document.getElementById('input-cari').value;
        const checked = [...document.querySelectorAll('#sidebar-filter input:checked')].map(el => el.value);
        const params = new URLSearchParams();
        if (keyword) params.append('keyword', keyword);
        if (checked.length > 0) params.append('kategori', checked.join(','));
        fetch(`/menu/search?${params.toString()}`)
            .then(r => r.json())
            .then(data => renderHasilCari(data))
            .catch(err => console.error(err));
    }

    function renderHasilCari(data) {
        const menuDisplay = document.getElementById('menu-display');
        menuDisplay.innerHTML = '';
        if (data.length === 0) {
            menuDisplay.innerHTML = '<p style="grid-column:1/-1;text-align:center;">Menu tidak ditemukan.</p>';
            return;
        }
        data.forEach(item => {
            const badgeClass = item.kategori === 'Menu Diet' ? 'menu-diet' : 'menu-harian';
            let tombol = isAdmin
                ? `<div style="margin-top:10px;display:flex;gap:5px;">
                    <button type="button" onclick="bukaModalEdit(${item.id},'${item.nama_menu.replace(/'/g,"\\'")}',${item.harga},'${item.kategori}','${item.gambar}')"
                        style="background:orange;color:white;border:none;padding:5px 10px;border-radius:5px;cursor:pointer;font-size:0.8rem;">✏️ Edit</button>
                    <form action="/menu/${item.id}" method="POST" onsubmit="return confirm('Yakin?')" style="display:inline;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" style="background:red;color:white;border:none;padding:5px 10px;border-radius:5px;cursor:pointer;font-size:0.8rem;">🗑️ Hapus</button>
                    </form></div>`
                : `<button type="button" class="btn-pesan" data-id="${item.id}" data-nama="${item.nama_menu}">Pesan Sekarang</button>`;
            menuDisplay.innerHTML += `
                <article class="card-menu">
                    <img src="/storage/${item.gambar}" alt="${item.nama_menu}" onerror="this.src='/images/default.png'">
                    <section class="card-content">
                        <h4>${item.nama_menu}</h4>
                        <p>Rp ${Number(item.harga).toLocaleString('id-ID')}</p>
                        <span class="badge-kategori ${badgeClass}">${item.kategori}</span>
                        ${tombol}
                    </section>
                </article>`;
        });
        pasangEventPesan();
    }

    document.getElementById('input-cari').addEventListener('input', fetchMenu);
    document.querySelectorAll('#sidebar-filter input[type=checkbox]').forEach(cb => cb.addEventListener('change', fetchMenu));
</script>
@endsection