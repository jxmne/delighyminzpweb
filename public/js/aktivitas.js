// ═══════════════════════════════════════════════════════
//  riwayat.js
//  Alur: pesanan.js → simpan ke localStorage['riwayat']
//        → halaman ini baca & render kartu
//  Tombol "Beli lagi" → tambah balik ke localStorage['pesanan']
// ═══════════════════════════════════════════════════════

// ── Data produk ─────────────────────────────────────────
let daftarProduk = JSON.parse(localStorage.getItem('produk')) || [
    { id: 1, nama: 'Nasi Kuning',              harga: 12000, kategori: 'Menu Harian', gambar: 'nasi kuning tumpeng.jpg' },
    { id: 2, nama: 'Nasi Uduk Ayam',           harga: 19000, kategori: 'Menu Harian', gambar: 'Nasi Uduk dengan Ayam Goreng.jpg' },
    { id: 3, nama: 'Nasi Ayam Goreng',         harga: 17000, kategori: 'Menu Harian', gambar: 'nasi ayam.jpg' },
    { id: 4, nama: 'Nasi Campur',              harga: 13000, kategori: 'Menu Harian', gambar: 'nasi campur.jpg' },
    { id: 5, nama: 'Nasi Jagung',              harga: 15000, kategori: 'Menu Diet',   gambar: 'nasi jagung.jpg' },
    { id: 6, nama: 'Nasi Merah Tumis Brokoli', harga: 19000, kategori: 'Menu Diet',   gambar: 'nasi merah tumis brokoli.jpg' },
];

// ── Helpers ─────────────────────────────────────────────
const getRiwayat = () => JSON.parse(localStorage.getItem('riwayat')) || [];
const formatRp   = (n)  => `Rp ${Number(n).toLocaleString('id-ID')}`;

// ── Badge class berdasarkan kategori ────────────────────
function badgeClass(kategori = '') {
    const k = kategori.toLowerCase();
    if (k.includes('harian'))    return 'badge-harian';
    if (k.includes('diet'))      return 'badge-diet';
    if (k.includes('prasmanan')) return 'badge-prasmanan';
    if (k.includes('lauk'))      return 'badge-lauk';
    if (k.includes('personal'))  return 'badge-personal';
    return 'badge-default';
}

// ── Filter berdasarkan dropdown waktu ───────────────────
function filterData(data, pilihan) {
    if (pilihan === 'all') return data;
    const HARI = 24 * 60 * 60 * 1000;
    const now  = Date.now();
    if (pilihan === '7')    return data.filter(p => now - p.timestamp <= 7  * HARI);
    if (pilihan === '30')   return data.filter(p => now - p.timestamp <= 30 * HARI);
    if (pilihan === 'lama') return data.filter(p => now - p.timestamp  > 30 * HARI);
    return data;
}

// ─── Render kartu riwayat ────────────────────────────────
function renderRiwayat(pilihan = 'all') {
    const grid = document.querySelector('#history-grid');
    if (!grid) return;

    const semua = getRiwayat();
    const data  = filterData(semua, pilihan);

    if (data.length === 0) {
        grid.innerHTML = `
            <article class="empty-state">
                <p>Belum ada riwayat pemesanan.</p>
                <a href="menumakancustomer.html">→ Mulai pesan sekarang</a>
            </article>`;
        return;
    }

    grid.innerHTML = data.map((item, idx) => `
        <article class="order-card" data-idx="${idx}">

            <!-- KIRI: foto + info -->
            <section class="order-left">
                <img src="${item.gambar}" alt="${item.nama}"
                     onerror="this.src='placeholder.jpg'">
                <section class="order-info">
                    <h4>${item.nama}</h4>
                    <span class="badge ${badgeClass(item.kategori)}">
                        Kategori: ${item.kategori}
                    </span>
                    <p class="harga">${formatRp(item.harga)}</p>
                    <p class="opsi">📦 ${item.opsi || 'Paket Standar'}</p>
                </section>
            </section>

            <!-- TENGAH: tanggal -->
            <p class="order-date">Dibeli pada: ${item.tanggal || '—'}</p>

            <!-- KANAN: jumlah + tombol beli lagi -->
            <section class="order-right">
                <p class="qty">Jumlah: ${item.jumlah} Kotak</p>
                <button class="btn-beli-lagi" data-idx="${idx}"
                        aria-label="Beli lagi ${item.nama}">
                    Beli lagi
                </button>
            </section>

        </article>
    `).join('');
}

// ── Event: filter dropdown ───────────────────────────────
document.querySelector('#filter-waktu')?.addEventListener('change', (e) => {
    renderRiwayat(e.target.value);
});

// ── Event delegation: Beli lagi ─────────────────────────
document.querySelector('#history-grid')?.addEventListener('click', (e) => {
    if (!e.target.classList.contains('btn-beli-lagi')) return;

    const pilihan = document.querySelector('#filter-waktu')?.value || 'all';
    const data    = filterData(getRiwayat(), pilihan);
    const idx     = parseInt(e.target.dataset.idx);
    const item    = data[idx];
    if (!item) return;

    // Tambahkan ke keranjang pesanan
    const pesanan = JSON.parse(localStorage.getItem('pesanan')) || [];
    const ada = pesanan.find(p => p.nama === item.nama);
    if (ada) {
        ada.jumlah += item.jumlah;
    } else {
        pesanan.push({
            id      : Date.now(),
            nama    : item.nama,
            harga   : item.harga,
            jumlah  : item.jumlah,
            kategori: item.kategori,
            gambar  : item.gambar,
            opsi    : item.opsi || 'Paket Standar',
            status  : 'Proses'
        });
    }
    localStorage.setItem('pesanan', JSON.stringify(pesanan));

    // Feedback visual di tombol
    const btn = e.target;
    btn.textContent = 'Berhasil!';
    btn.style.background = 'linear-gradient(135deg,#0e8a50,#0b6e40)';
    btn.disabled = true;

    setTimeout(() => {
        btn.textContent = 'Beli lagi';
        btn.style.background = '';
        btn.disabled = false;
    }, 1800);
});

// ── Init ─────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    renderRiwayat('all');
});




// ═══════════════════════════════════════════════════════
//  riwayat.js
//  Alur: pesanan.js → simpan ke localStorage['riwayat']
//        → halaman ini baca & render kartu
//  Tombol "Beli lagi" → tambah balik ke localStorage['pesanan']
// ═══════════════════════════════════════════════════════

// ── Helpers ─────────────────────────────────────────────
// const getRiwayat = () => JSON.parse(localStorage.getItem('riwayat')) || [];
// const formatRp   = (n)  => `Rp ${Number(n).toLocaleString('id-ID')}`;

// // ── Badge class berdasarkan kategori ────────────────────
// function badgeClass(kategori = '') {
//     const k = kategori.toLowerCase();
//     if (k.includes('harian'))    return 'badge-harian';
//     if (k.includes('diet'))      return 'badge-diet';
//     if (k.includes('prasmanan')) return 'badge-prasmanan';
//     if (k.includes('lauk'))      return 'badge-lauk';
//     if (k.includes('personal'))  return 'badge-personal';
//     return 'badge-default';
// }

// // ── Filter berdasarkan dropdown waktu ───────────────────
// function filterData(data, pilihan) {
//     if (pilihan === 'all') return data;
//     const HARI = 24 * 60 * 60 * 1000;
//     const now  = Date.now();
//     if (pilihan === '7')    return data.filter(p => now - p.timestamp <= 7  * HARI);
//     if (pilihan === '30')   return data.filter(p => now - p.timestamp <= 30 * HARI);
//     if (pilihan === 'lama') return data.filter(p => now - p.timestamp  > 30 * HARI);
//     return data;
// }

// // ─── Render kartu riwayat ────────────────────────────────
// function renderRiwayat(pilihan = 'all') {
//     const grid = document.querySelector('#history-grid');
//     if (!grid) return;

//     const semua = getRiwayat();
//     const data  = filterData(semua, pilihan);

//     if (data.length === 0) {
//         grid.innerHTML = `
//             <article class="empty-state">
//                 <p>Belum ada riwayat pemesanan.</p>
//                 <a href="menumakancustomer.html">→ Mulai pesan sekarang</a>
//             </article>`;
//         return;
//     }

//     grid.innerHTML = data.map((item, idx) => `
//         <article class="order-card" data-idx="${idx}">

//             <!-- KIRI: foto + info -->
//             <section class="order-left">
//                 <img src="${item.gambar}" alt="${item.nama}"
//                      onerror="this.src='placeholder.jpg'">
//                 <section class="order-info">
//                     <h4>${item.nama}</h4>
//                     <span class="badge ${badgeClass(item.kategori)}">
//                         Kategori: ${item.kategori}
//                     </span>
//                     <p class="harga">${formatRp(item.harga)}</p>
//                     <p class="opsi">📦 ${item.opsi || 'Paket Standar'}</p>
//                 </section>
//             </section>

//             <!-- TENGAH: tanggal -->
//             <p class="order-date">Dibeli pada: ${item.tanggal || '—'}</p>

//             <!-- KANAN: jumlah + tombol beli lagi -->
//             <section class="order-right">
//                 <p class="qty">Jumlah: ${item.jumlah} Kotak</p>
//                 <button class="btn-beli-lagi" data-idx="${idx}"
//                         aria-label="Beli lagi ${item.nama}">
//                     Beli lagi
//                 </button>
//             </section>

//         </article>
//     `).join('');
// }

// // ── Event: filter dropdown ───────────────────────────────
// document.querySelector('#filter-waktu')?.addEventListener('change', (e) => {
//     renderRiwayat(e.target.value);
// });

// // ── Event delegation: Beli lagi ─────────────────────────
// document.querySelector('#history-grid')?.addEventListener('click', (e) => {
//     if (!e.target.classList.contains('btn-beli-lagi')) return;

//     const pilihan = document.querySelector('#filter-waktu')?.value || 'all';
//     const data    = filterData(getRiwayat(), pilihan);
//     const idx     = parseInt(e.target.dataset.idx);
//     const item    = data[idx];
//     if (!item) return;

//     // Tambahkan ke keranjang pesanan
//     const pesanan = JSON.parse(localStorage.getItem('pesanan')) || [];
//     const ada = pesanan.find(p => p.nama === item.nama);
//     if (ada) {
//         ada.jumlah += item.jumlah;
//     } else {
//         pesanan.push({
//             id      : Date.now(),
//             nama    : item.nama,
//             harga   : item.harga,
//             jumlah  : item.jumlah,
//             kategori: item.kategori,
//             gambar  : item.gambar,
//             opsi    : item.opsi || 'Paket Standar',
//             status  : 'Proses'
//         });
//     }
//     localStorage.setItem('pesanan', JSON.stringify(pesanan));

//     // Feedback visual di tombol
//     const btn = e.target;
//     btn.textContent = 'Berhasil!';
//     btn.style.background = 'linear-gradient(135deg,#0e8a50,#0b6e40)';
//     btn.disabled = true;

//     setTimeout(() => {
//         btn.textContent = 'Beli lagi';
//         btn.style.background = '';
//         btn.disabled = false;
//     }, 1800);
// });

// // ── Init ─────────────────────────────────────────────────
// document.addEventListener('DOMContentLoaded', () => {
//     renderRiwayat('all');
// });