// alert("Halo, JS sudah aktif!");

const updateStatistik = () => {
    // 1. Ambil data pesanan dari localStorage
    const daftarPesanan = JSON.parse(localStorage.getItem('pesanan')) || [];

    // 2. Hitung total item menggunakan array method .reduce()
    const totalItem = daftarPesanan.reduce((akumulator, item) => {
        return akumulator + parseInt(item.jumlah);
    }, 0);

    // 3. Update DOM (Manipulasi tampilan)
    const elemenTotal = document.querySelector('#total-terjual');
    const elemenProgress = document.querySelector('#progress-terjual');
    const elemenKet = document.querySelector('#keterangan-kuota');

    if (elemenTotal) {
        elemenTotal.innerText = `${totalItem} pax`;
        
        // Misal target harianmu 100 pax
        elemenProgress.value = totalItem;
        elemenProgress.max = 100; 
        
        elemenKet.innerText = totalItem >= 100 
            ? "Target harian tercapai!" 
            : `Sisa kuota: ${100 - totalItem} pax lagi`;
    }
};


const fetchWeatherData = async () => {
    const loadingEl = document.getElementById('loading-weather');
    const contentEl = document.getElementById('weather-content');
    
    try {
        // Ambil data format JSON j1 
        const response = await fetch('https://wttr.in/Surabaya?format=j1');
        if (!response.ok) throw new Error('Gagal mengambil data cuaca');
        
        const data = await response.json();
        
        // Ekstrak data dari struktur JSON wttr.in
        const currentCondition = data.current_condition[0];
        const areaInfo = data.nearest_area[0];
        
        const namaKota = areaInfo.areaName[0].value;
        const suhuSaatIni = currentCondition.temp_C;
        const deskripsiCuaca = currentCondition.lang_id 
            ? currentCondition.lang_id[0].value 
            : currentCondition.weatherDesc[0].value;

        // Pasang data ke element DOM HTML
        document.getElementById('weather-city').innerText = namaKota + ", Surabaya";
        document.getElementById('weather-temp').innerText = `${suhuSaatIni}°C`;
        document.getElementById('weather-desc').innerText = deskripsiCuaca;
        
        // Tampilkan konten cuaca
        loadingEl.style.display = 'none';
        contentEl.style.display = 'block';
        
    } catch (error) {
        console.error('Error Cuaca:', error);
        loadingEl.innerText = '❌ Gagal memuat info cuaca.';
    }
};

// Jalankan fungsi saat halaman index dibuka
document.addEventListener('DOMContentLoaded', updateStatistik);