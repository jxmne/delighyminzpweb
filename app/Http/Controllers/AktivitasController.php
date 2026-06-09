<?php
namespace App\Http\Controllers;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $dari   = $request->dari;
        $sampai = $request->sampai;

        if (Auth::user()->is_admin) {
            $query = Pesanan::with(['user', 'details.menu'])->latest();
        } else {
            $query = Pesanan::with('details.menu')
                ->where('pelanggan_id', Auth::id())
                ->latest();
        }

        // Filter tanggal kalau ada
        if ($dari) {
            $query->whereDate('created_at', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('created_at', '<=', $sampai);
        }

        $transaksi = $query->paginate(Auth::user()->is_admin ? 15 : 10)
                           ->appends($request->only('dari', 'sampai')); // biar pagination tetap bawa filter

        return view('aktivitas', compact('transaksi'));
    }
}