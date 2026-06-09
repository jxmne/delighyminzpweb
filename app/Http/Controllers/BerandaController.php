<?php
namespace App\Http\Controllers;
use App\Models\Pesanan;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index()
    {
        $hari_ini = Carbon::today();

        // Total pesanan hari ini
        $pesananHariIni = Pesanan::whereDate('created_at', $hari_ini)->count();

        // Total pax hari ini (jumlah_pax dari semua pesanan hari ini)
        $paxHariIni = Pesanan::whereDate('created_at', $hari_ini)->sum('jumlah_pax');

        // Kuota harian (bisa kamu ubah sesuai kapasitas)
        $kuotaHarian = 100;
        $sisaKuota   = max(0, $kuotaHarian - $paxHariIni);

        // Menu/paket terlaris bulan ini (nama menu paling sering dipesan)
        $terlaris = \App\Models\DetailPesanan::with('menu')
            ->whereHas('pesanan', function($q) {
                $q->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
            })
            ->selectRaw('menu_id, SUM(kuantitas) as total_terjual')
            ->groupBy('menu_id')
            ->orderByDesc('total_terjual')
            ->first();

        return view('beranda', compact(
            'pesananHariIni',
            'paxHariIni',
            'sisaKuota',
            'kuotaHarian',
            'terlaris'
        ));
    }

    public function tentang()
    {
        return view('tentang');
    }
}