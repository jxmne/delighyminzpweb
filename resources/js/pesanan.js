
const getPesanan  = () => JSON.parse(localStorage.getItem('pesanan'))  || [];
const setPesanan  = (data) => localStorage.setItem('pesanan', JSON.stringify(data));
const getRiwayat  = () => JSON.parse(localStorage.getItem('riwayat')) || [];
const setRiwayat  = (data) => localStorage.setItem('riwayat', JSON.stringify(data));

const formatRp = (n) => `Rp ${Number(n).toLocaleString('id-ID')}`;

// ── ID item yang dihapus
let idHapusPending = null;

// Render Kartu
function renderKartu() {
    const container = document.querySelector('#daftar-pesanan');
    if (!container) return;

    const data = getPesanan();

    if (data.length === 0) {
        container.innerHTML = `
            <article class="empty-state">
                <p>Belum ada pesanan aktif.</p>
                <a href="menumakancustomer.html">→ Lihat Menu Catering</a>
            </article>`;
        // sembunyikan tabel & tombol beli
        const tw = document.querySelector('#tabel-wrapper');
        if (tw) tw.style.display = 'none';
        return;
    }

    // Render setiap item sebagai <article class="order-card">
    container.innerHTML = data.map(item => `
        <article class="order-card" data-id="${item.id}">
            <section class="order-left">
                <img src="${item.gambar}" alt="${item.nama}">
                <section class="order-info">
                    <h4>${item.nama}</h4>
                    <p class="price">${formatRp(item.harga)}</p>
                    <span class="badge-kategori">${item.kategori}</span>
                    <p class="status-text">📦 ${item.opsi || 'Paket Standar'}</p>
                </section>
            </section>

            <section class="order-right">
                <!-- tampilan jumlah biasa -->
                <p class="jumlah-display" id="display-${item.id}">
                    Jumlah: <strong>${item.jumlah}</strong> Kotak
                </p>

                <!-- editor jumlah (tersembunyi sampai klik Edit) -->
                <section class="jumlah-editor" id="editor-${item.id}">
                    <input type="number" min="1" value="${item.jumlah}"
                           id="input-${item.id}" aria-label="Jumlah ${item.nama}">
                    <button class="btn-simpan" data-id="${item.id}">Simpan</button>
                </section>

                <nav class="order-actions">
                    <button class="btn-edit"  data-id="${item.id}">✏️ Edit</button>
                    <button class="btn-hapus" data-id="${item.id}">🗑️ Hapus</button>
                </nav>
            </section>
        </article>
    `).join('');

    renderTabel(data);
}

// ═══════════════════════════════════════════════════════
//  RENDER TABEL RINGKASAN
// ═══════════════════════════════════════════════════════
function renderTabel(data) {
    const wrapper = document.querySelector('#tabel-wrapper');
    const tbody   = document.querySelector('#tabel-ringkasan tbody');
    const tfoot   = document.querySelector('#tabel-ringkasan tfoot');
    if (!wrapper || !tbody || !tfoot) return;

    wrapper.style.display = data.length ? 'block' : 'none';
    if (!data.length) return;

    const total = data.reduce((acc, item) => acc + item.harga * item.jumlah, 0);

    tbody.innerHTML = data.map((item, i) => `
        <tr>
            <td>${i + 1}</td>
            <td><img src="${item.gambar}" alt="${item.nama}"
                     style="width:46px;height:46px;object-fit:cover;border-radius:7px;"></td>
            <td>${item.nama}</td>
            <td>${item.kategori}</td>
            <td>${formatRp(item.harga)}</td>
            <td>${item.jumlah} kotak</td>
            <td><strong>${formatRp(item.harga * item.jumlah)}</strong></td>
        </tr>
    `).join('');

    tfoot.innerHTML = `
        <tr class="total-row">
            <td colspan="6" style="text-align:right; padding-right:16px;">
                Total Keseluruhan
            </td>
            <td>${formatRp(total)}</td>
        </tr>`;
}


//  EVENT DELEGATION kartu (Edit, Simpan, Hapus)
    document.addEventListener('click', (e) => {

        // EDIT
        if (e.target.classList.contains('btn-edit')) {
            const id      = e.target.dataset.id;
            const display = document.querySelector(`#display-${id}`);
            const editor  = document.querySelector(`#editor-${id}`);
            const input   = document.querySelector(`#input-${id}`);
            if (!display || !editor) return;

            // toggle
            const sedangEdit = editor.classList.contains('aktif');
            display.style.display = sedangEdit ? ''      : 'none';
            editor.classList.toggle('aktif', !sedangEdit);
            if (!sedangEdit && input) input.focus();
            return;
        }

        // SIMPAN
        if (e.target.classList.contains('btn-simpan')) {
            const id    = e.target.dataset.id;
            const input = document.querySelector(`#input-${id}`);
            if (!input) return;

            const jumlahBaru = parseInt(input.value);
            if (isNaN(jumlahBaru) || jumlahBaru < 1) {
                input.style.border = '1.5px solid #e53e3e';
                input.focus();
                return;
            }

            const data = getPesanan();
            const item = data.find(p => String(p.id) === id);
            if (item) {
                item.jumlah = jumlahBaru;
                setPesanan(data);
            }
            renderKartu();  
            return;
        }

        // HAPUS
        if (e.target.classList.contains('btn-hapus')) {
            idHapusPending = e.target.dataset.id;
            const modal = document.querySelector('#modal-hapus');
            if (modal) modal.classList.add('tampil');
            return;
        }

        // BATAL
        if (e.target.id === 'modal-batal') {
            idHapusPending = null;
            document.querySelector('#modal-hapus')?.classList.remove('tampil');
            return;
        }

        // KONFIRM HAPUS
        if (e.target.id === 'modal-konfirm') {
            if (idHapusPending !== null) {
                const sisa = getPesanan().filter(p => String(p.id) !== String(idHapusPending));
                setPesanan(sisa);
                idHapusPending = null;
            }
            document.querySelector('#modal-hapus')?.classList.remove('tampil');
            renderKartu();
            return;
        }

        if (e.target.id === 'modal-hapus') {
            idHapusPending = null;
            e.target.classList.remove('tampil');
        }
    });

    // Render TOMBOL BELI
    document.querySelector('#btn-beli')?.addEventListener('click', () => {
        const data = getPesanan();
        if (data.length === 0) {
            alert('Tidak ada pesanan untuk dibeli.');
            return;
        }

        const sekarang = new Date();
        const tanggalStr = sekarang.toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric'
        });

        const entri = data.map(item => ({
            id       : Date.now() + Math.random(), 
            nama     : item.nama,
            harga    : item.harga,
            jumlah   : item.jumlah,
            kategori : item.kategori,
            gambar   : item.gambar,
            opsi     : item.opsi || 'Paket Standar',
            tanggal  : tanggalStr,
            timestamp: sekarang.getTime()
        }));

        const riwayatLama = getRiwayat();
        setRiwayat([...entri, ...riwayatLama]);

        // keranjang kosong
        localStorage.removeItem('pesanan');

        // Animasi konfirmasi 
        const btnBeli = document.querySelector('#btn-beli');
        if (btnBeli) {
            btnBeli.textContent = '✅ Berhasil melakukan pembelian!';
            btnBeli.style.background = '#2bac83';
            btnBeli.disabled = true;
        }

        setTimeout(() => {
            window.location.href = 'aktivitasriwayat.html';
        }, 900);
});

document.addEventListener('DOMContentLoaded', renderKartu);