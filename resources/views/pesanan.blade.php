@extends('layouts.app')
@section('title', 'Pesanan')
@section('css')
    <link rel='stylesheet' href="{{ asset('css/pesanan.css') }}">
@endsection
 
@section('content')
 
{{-- Flash message --}}
@if(session('success'))
    <aside class='alert-success' role='status'>
        <p>✅ {{ session('success') }}</p>
    </aside>
@endif
@if(session('error'))
    <aside class='alert-error' role='alert'>
        <p>❌ {{ session('error') }}</p>
    </aside>
@endif
 
 
<h2 class='page-title'>🛒 Daftar Pesanan Aktif</h2>
 
@if(count($cart) > 0)
 
    {{-- ═══ KARTU PESANAN (READ) ═══ --}}
    <section id='daftar-pesanan'>
        @foreach($cart as $id => $item)
        <article class='order-card'>
            <section class='order-left'>
                <img src="{{ asset('image/' . ($item['gambar'] ?? '')) }}"
                    alt="{{ $item['nama_menu'] ?? '-' }}">
                <section class='order-info'>
                    <h4>{{ $item['nama_menu'] ?? '-' }}</h4>
                    <p class='price'>Rp {{ number_format($item['harga_satuan'] ?? 0, 0, ',', '.') }}</p>
                    <span class='badge-kategori'>{{ $item['kategori'] ?? '-' }}</span>
                    <p>📦 {{ $item['opsi'] ?? 'Take Away' }}</p>
                </section>
            </section>

            <section class='order-right'>
                <p>Jumlah: <strong>{{ $item['kuantitas'] ?? 1 }}</strong> Kotak</p>
                <p>Subtotal: <strong>Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</strong></p>
 
                <!-- UPDATE: Edit Jumlah  -->
                <form action="{{ route('pesanan.update', $id) }}" method='POST'
                      class='form-edit-jumlah'>
                    @csrf
                    <input type='number' name='kuantitas'
                           value='{{ $item['kuantitas'] ?? 1}}' min='1' max='99'
                           style='width:60px; padding:5px; border-radius:5px;'>
                    <button type='submit' class='btn-simpan'>💾 Simpan</button>
                </form>
 
                <!-- DELETE: Hapus Item  -->
                <button class='btn-hapus'
                        data-id="{{ $id }}"
                        data-nama="{{ $item['nama_menu'] ?? '-' }}">
                    🗑️ Hapus
                </button>
            </section>
        </article>
        @endforeach
    </section>
 
    <!--  ═══ TABEL RINGKASAN ═══  -->
    <section class='tabel-wrapper'>
        <table id='tabel-ringkasan'>
            <thead>
                <tr>
                    <th>#</th><th>Gambar</th><th>Menu</th>
                    <th>Opsi</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $id => $item)
                @php $total += $item['subtotal'] ?? 0; @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><img src="{{ asset('image/' . ($item['gambar'] ?? '')) }}"
                             width='46' style='border-radius:7px;'></td>
                    <td>{{ $item['nama_menu'] ?? '-' }}</td>
                    <td>{{ $item['opsi'] ?? '-' }}</td>
                    <td>Rp {{ number_format($item['harga_satuan'] ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $item['kuantitas'] ?? 1 }} kotak</td>
                    <td><strong>Rp {{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</strong></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan='6' style='text-align:right; font-weight:bold;'>
                        Total Keseluruhan
                    </td>
                    <td><strong>Rp {{ number_format($total,0,',','.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </section>
 
    <!-- BELI SEKARANG -->
        <!-- Ganti form beli langsung dengan buka modal dulu -->
    <nav class='btn-beli-wrapper'>
        <button type='button' id='btn-beli' onclick="bukaPilihBayar()">
            🛍️ Beli Sekarang
        </button>
    </nav>

    <!-- Modal pilihan pembayaran  -->
    <dialog id="modal-bayar" style="border:none; border-radius:15px; padding:25px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2); width:320px; text-align:center;">
        <h3 style="margin-bottom:15px;">💳 Pilih Metode Pembayaran</h3>

        {{-- Pilih Cash → langsung submit --}}
        <form action="{{ route('pesanan.beli') }}" method="POST">
            @csrf
            <input type="hidden" name="metode_bayar" value="cash">
            <button type="submit" style="width:100%; padding:12px; margin-bottom:10px;
                    background:#12b368; color:white; border:none; border-radius:8px;
                    font-size:1rem; cursor:pointer; font-weight:bold;">
                💵 Cash
            </button>
        </form>

        {{-- Pilih QRIS → nanti bisa ditambah logika generate QR --}}
        <form action="{{ route('pesanan.beli') }}" method="POST">
            @csrf
            <input type="hidden" name="metode_bayar" value="qris">
            <button type="submit" style="width:100%; padding:12px;
                    background:#f0f0f0; color:#333; border:1px solid #ccc;
                    border-radius:8px; font-size:1rem; cursor:pointer; font-weight:bold;">
                📱 QRIS
            </button>
        </form>

        <button onclick="document.getElementById('modal-bayar').close()"
                style="margin-top:10px; background:none; border:none;
                    color:#999; cursor:pointer;">
            Batal
        </button>
    </dialog>
 
    <!-- Modal konfirmasi hapus -->
    <aside id='modal-hapus'>
        <dialog id='dialog-hapus'>
            <h3>🗑️ Hapus pesanan ini?</h3>
            <p id='modal-nama-item'></p>
            <form id='form-hapus' method='POST'>
                @csrf
                <nav class='modal-actions'>
                    <button type='button' onclick="tutupModal()">Batal</button>
                    <button type='submit' class='btn-konfirm-hapus' style='color: #dc3545; '>Hapus</button>
                </nav>
            </form>
        </dialog>
    </aside>

@else
    <article class='empty-state'>
        <p>Belum ada pesanan aktif.</p>
        <a href="{{ route('menu') }}">→ Lihat Menu Catering</a>
    </article>
@endif

@endsection
 
@section('js')
<script>
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-hapus')) {
        const id   = e.target.dataset.id;
        const nama = e.target.dataset.nama;
        bukaModa(id, nama);
    }
});

function bukaModa(id, nama) {
    document.getElementById('modal-nama-item').textContent = nama;
    document.getElementById('form-hapus').action = 'pesanan/hapus/' + id;
    document.getElementById('dialog-hapus').showModal();
}

function tutupModal() {
    document.getElementById('dialog-hapus').close();
}

function bukaPilihBayar() {
    document.getElementById('modal-bayar').showModal();
}
</script>
@endsection
