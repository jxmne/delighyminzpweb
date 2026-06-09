@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/tentang.css') }}">
@endsection

@section('content')
    <article class="tentang-container">
        <section class="tentang-hero">
            <h2>Tentang Catering Delighy</h2>
            <p>Layanan pre-order catering praktis dan lezat sejak 2024.</p>
        </section>

        <section class="tentang-detail">
            <h3>📍 Lokasi Kami</h3>
            <p>Jl. Kuliner Nusantara No. 15, Pekanbaru, Riau</p>
        </section>

        <section class="tentang-visi">
            <h3>🎯 Visi & Misi</h3>
            <p><strong>Visi:</strong> Menjadi pilihan utama layanan catering halal, higienis, dan tepat waktu di Pekanbaru.</p>
            <ul>
                <li>Menyajikan menu berkualitas dengan bahan-bahan segar dan halal.</li>
                <li>Memberikan kemudahan pemesanan melalui sistem pre-order online.</li>
                <li>Memastikan pengiriman tepat waktu ke setiap pelanggan.</li>
            </ul>
        </section>

        <section class="tentang-tim">
            <h3>👩‍🍳 Tim Kami</h3>
            @php
                $tim = [
                    ['nama' => 'Chef Rina', 'peran' => 'Head Chef', 'ikon' => '👩‍🍳'],
                    ['nama' => 'Budi', 'peran' => 'Koordinator Pesan', 'ikon' => '📋'],
                    ['nama' => 'Siti', 'peran' => 'Pengiriman & Logistik', 'ikon' => '🚗'],
                ];
            @endphp
            <ul class="tim-list">
                @forelse($tim as $anggota)
                    <li class="tim-item">
                        <span class="tim-ikon">{{ $anggota['ikon'] }}</span>
                        <div>
                            <strong>{{ $anggota['nama'] }}</strong>
                            <small>{{ $anggota['peran'] }}</small>
                        </div>
                    </li>
                @empty
                    <li>⚠️ Data tim belum tersedia.</li>
                @endforelse
            </ul>
        </section>
    </article>
@endsection