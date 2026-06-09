@extends('layouts.app')

@section('title', 'Beranda')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/beranda.css') }}">
@endsection

@section('content')

    <article class="menu-container">

        <section class="hero-section">
            <h2>Selamat Datang di Catering Delighy</h2>
            <p>Menu sehat, higienis, dan lezat siap diantar ke tempat Anda.</p>
        </section>

        <section class="promo-card">
            <h3>🎉 Promo Spesial Bulan Ini</h3>
            <p>Diskon 5% untuk pemesanan paket Nasi Kotak minimal 30 pax! Berlaku untuk semua menu.</p>
            <p><strong>Periode:</strong> 1 - 30 Juni 2026</p>
        </section>

        <section class="hubungi-section">
            <h2>Hubungi Kami</h2>
            <a href="https://wa.me/6281234567890?text=Halo%20Catering%20Delighy,%20saya%20mau%20order%20paket..." target="_blank">
                Pesan via WhatsApp Sekarang
            </a>
        </section>

        <section id="fitur">
            <h3>Mengapa Memilih Delighy?</h3>
            <ul>
                <li>Bahan berkualitas dan halal</li>
                <li>Pemesanan H-3 untuk persiapan maksimal</li>
                <li>Pengiriman tepat waktu</li>
                <li>Varian menu lengkap</li>
            </ul>
        </section>

        <section class="testimoni-section">
            <h3>Testimoni Pelanggan</h3>
            <article class="testimonial">
                <p>"Masakannya enak, porsinya pas. Sangat recommended untuk acara kantor!"</p>
                <footer>- Ibu Sarah</footer>
            </article>
            <article class="testimonial">
                <p>"Pesan buat arisan bulanan, semua suka. Next order pasti kesini lagi."</p>
                <footer>- Bu RT 05, Perumahan Mutiara</footer>
            </article>
        </section>

    </article>

    <aside>
            <h3>🍱 Statistik Cepat</h3>
 
            <!-- <article class="stat-item" style="border-left: 5px solid #3498db;">
                <h4>🌤️ Informasi Cuaca</h4>
                <p id="loading-weather" style="display: block; font-style: italic; color: #777;">
                    🔄 Menghubungkan ke satelit...</p>
                <div id="weather-content" style="display: none;">
                    <p>Kota: <strong id="weather-city">-</strong></p>
                    <p>Suhu: <strong id="weather-temp">-</strong></p>
                    <p>Kondisi: <span id="weather-desc">-</span></p>
                </div>
            </article> -->

                <article class="stat-item">
                    <h4>Pesanan Hari Ini</h4>
                    <p>{{ $pesananHariIni }} pesanan aktif</p>
                    <progress value="{{ $paxHariIni }}" max="{{ $kuotaHarian }}"></progress>
                    <small>Sisa kuota: {{ $sisaKuota }} pax lagi</small>
                </article>

                <article class="stat-item">
                    <h4>Paket Terlaris</h4>
                    @if($terlaris && $terlaris->menu)
                        <p>{{ $terlaris->menu->nama_menu }}</p>
                        <small>Terjual {{ $terlaris->total_terjual }} pax bulan ini</small>
                    @else
                        <p>-</p>
                        <small>Belum ada data bulan ini</small>
                    @endif
                </article>

                <article class="stat-item">
                    <h4>Jam Operasional</h4>
                    <p>Senin - Sabtu: 08.00 - 20.00</p>
                    <p>Minggu: 10.00 - 18.00</p>
                </article>

                <article class="stat-item">
                    <h4>Deadline PO</h4>
                    <p>H-3: 18.00 WIB</p>
                </article>
            </aside>

@endsection

@section('js')
    <script src="{{ asset('js/beranda.js') }}"></script>
@endsection

<!-- @push('scripts')
<script>
    // JavaScript spesifik halaman beranda
    const progress = document.getElementById('progress-terjual');
    const keterangan = document.getElementById('keterangan-kuota');
    const totalTerjual = document.getElementById('total-terjual');
    
    if(progress && totalTerjual) {
        console.log('Statistik beranda dimuat');
    }
</script>
@endpush -->