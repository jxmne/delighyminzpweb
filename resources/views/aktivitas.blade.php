@extends('layouts.app')

@section('title', 'Aktivitas')

@section('css')
    <link rel='stylesheet' href="{{ asset('css/aktivitas.css') }}">
@endsection

@section('content')
@auth

    @if(session('success'))
        <aside class='alert-success'>{{ session('success') }}</aside>
    @endif

    {{-- TAMPILAN ADMIN --}}
    @if(auth()->user()->is_admin)
        <section class='page-title'>
            <h2>Riwayat Transaksi Customer</h2>
        </section>

        {{-- FILTER WAKTU UNTUK ADMIN --}}
        <section class="filter-waktu">
            <form method="GET" action="{{ route('aktivitas') }}">
                <label>Dari tanggal:</label>
                <input type="date" name="dari" value="{{ request('dari') }}">
                <label>Sampai tanggal:</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}">
                <button type="submit">Filter</button>
                <a href="{{ route('aktivitas') }}" class="reset" style="background: #6c757d; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">Reset</a>
            </form>
        </section>

        @if($transaksi->count() > 0)
            <section class='tabel-wrapper'>
                <table class='aktivitas-table'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Menu</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $t)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $t->user->name ?? 'Unknown' }}</td>
                            <td>
                                @foreach($t->details as $d)
                                    <span class='badge-menu'>{{ $d->menu->nama_menu ?? '-' }}</span><br>
                                @endforeach
                            </td>
                            <td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                            <td><span class='status-badge status-{{ $t->status }}'>{{ ucfirst($t->status) }}</span></td>
                            <td>{{ $t->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            
            <nav class='pagination-wrapper'>
                {{ $transaksi->links() }}
            </nav>
        @else
            <article class='empty-state'>
                <p>Belum ada transaksi.</p>
            </article>
        @endif

    {{-- TAMPILAN CUSTOMER --}}
    @else
        <section class='customer-riwayat-container'>
            <h2 class='riwayat-title-polos'>Riwayat Aktivitas Pemesanan</h2>

            {{-- FILTER WAKTU UNTUK CUSTOMER --}}
            <section class="filter-waktu">
                <form method="GET" action="{{ route('aktivitas') }}">
                    <label>Dari tanggal:</label>
                    <input type="date" name="dari" value="{{ request('dari') }}">
                    <label>Sampai tanggal:</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}">
                    <button type="submit">Filter</button>
                    <a href="{{ route('aktivitas') }}" class="reset" style="background: #6c757d; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">Reset</a>
                </form>
            </section>

            @if($transaksi->count() > 0)
                @foreach($transaksi as $t)
                <article class='riwayat-card-customer' style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 10px;">
                    <p><strong>Tanggal:</strong> {{ $t->created_at->format('d M Y') }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($t->total_harga, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($t->status) }}</p>
                    
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        @foreach($t->details as $d)
                        <li style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                            <img src="{{ asset('storage/'.($d->menu->gambar ?? '')) }}" width="50" style="border-radius: 5px;" onerror="this.src='/images/default.png'">
                            <section>
                                <strong>{{ $d->menu->nama_menu ?? '-' }}</strong><br>
                                Jumlah: {{ $d->kuantitas }} kotak<br>
                                Harga: Rp {{ number_format($d->menu->harga ?? $d->subtotal, 0, ',', '.') }}
                            </section>
                            <form action="{{ route('pesanan.tambah', $d->menu_id) }}" method="POST">
                                @csrf
                                <button type='submit' style="background: #12b368; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">Beli lagi</button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                </article>
                @endforeach
                
                <nav class='pagination-wrapper'>
                    {{ $transaksi->links() }}
                </nav>
            @else
                <article class='empty-state'>
                    <p>Belum ada riwayat pesanan.</p>
                    <a href="{{ route('menu') }}">→ Mulai pesan sekarang</a>
                </article>
            @endif
        </section>
    @endif
@endauth
@endsection