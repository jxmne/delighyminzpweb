<?php
namespace App\Http\Controllers;
 
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
 
class PesananController extends Controller
{
    // ─── helper: ambil cart dari session ───────────────
    private function getCart(): array
    {
        return session()->get('cart', []);
    }
 
    // ════ READ ══════════════════════════════════════════
    // Tampilkan halaman keranjang
    public function index()
    {
        $cart            = $this->getCart();
        $jumlahKunjungan = session()->get('jumlah_kunjungan', 0);
        $waktuPertama    = session()->get('waktu_pertama', '-');
        $waktuTerakhir   = session()->get('waktu_terakhir', '-');
 
        return view('pesanan', compact(
            'cart', 'jumlahKunjungan', 'waktuPertama', 'waktuTerakhir'
        ));
    }
 
    // ════ CREATE ════════════════════════════════════════
    // Tambah item ke keranjang session
    public function tambahKeKeranjang(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $cart = session()->get('cart', []);
        
        // Ambil data dari request
        $kuantitas = $request->kuantitas ?? 1;
        $opsi = $request->opsi ?? 'Take Away';
        
        if (isset($cart[$id])) {
            $cart[$id]['kuantitas'] = $kuantitas;
            $cart[$id]['subtotal']  = $cart[$id]['harga_satuan'] * $kuantitas;
            $cart[$id]['opsi']      = $opsi;
        } else {
            // Tambah baru
            $cart[$id] = [
                'menu_id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'harga_satuan' => $menu->harga,
                'kuantitas' => $kuantitas,
                'subtotal' => $menu->harga * $kuantitas,
                'gambar' => $menu->gambar,
                'kategori' => $menu->kategori,
                'opsi' => $opsi
            ];
        }
        
        session()->put('cart', $cart);
        
        // Cek apakah request dari AJAX/fetch
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'opsi' => $opsi,
                'kuantitas' => $kuantitas,
                'message' => 'Item berhasil ditambahkan!'
            ]);
        }
        
        // Untuk request biasa (form submit)
        return redirect()->route('pesanan')->with('success', 'Item berhasil ditambahkan!');
    }
 
    // ════ UPDATE ════════════════════════════════════════
    // Edit jumlah item di keranjang
    public function updateJumlah(Request $request, $id)
    {
        $request->validate([
            'kuantitas' => 'required|integer|min:1|max:99',
        ]);
 
        $cart = $this->getCart();
 
        if (isset($cart[$id])) {
            $cart[$id]['kuantitas'] = $request->kuantitas;
            $cart[$id]['subtotal']  = $cart[$id]['harga_satuan'] * $request->kuantitas;
            session()->put('cart', $cart);
        }
 
        return redirect()->route('pesanan')->with('success', 'Jumlah berhasil diperbarui!');
    }
 
    // ════ DELETE ════════════════════════════════════════
    // Hapus satu item dari keranjang
    public function hapusItem($id)
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        session()->put('cart', $cart);
 
        return redirect()->route('pesanan')->with('success', 'Item dihapus dari keranjang!');
    }
 
    // ════ BELI → SIMPAN KE DATABASE ═════════════════════
    public function beli(Request $request)
    {
        $cart = $this->getCart();
        
        if (empty($cart)) {
            return redirect()->route('pesanan')->with('error', 'Keranjang kosong!');
        }
        
        $total = collect($cart)->sum('subtotal');
        $totalKuantitas = collect($cart)->sum('kuantitas');
        
        // DEBUG: cek nilai sebelum simpan
        Log::info('Beli dipanggil', [
            'cart' => $cart,
            'total' => $total,
            'totalKuantitas' => $totalKuantitas,
            'user_id' => Auth::id()
        ]);
        
        try {
            // Simpan header pesanan
            $pesanan = Pesanan::create([
                'pelanggan_id'  => Auth::id(),
                'tanggal_pesan' => now(),
                'total_harga'   => $total,
                'jumlah_pax'    => $totalKuantitas,
                'status'        => 'selesai',
                'catatan'       => $request->input('catatan'),
                'metode_bayar'  => $request->input('metode_bayar', 'cash'),
            ]);
            
            Log::info('Pesanan tersimpan', ['pesanan_id' => $pesanan->id]);
            
            // Simpan detail per item
            foreach ($cart as $item) {
                DetailPesanan::create([
                    'pesanan_id'   => $pesanan->id,
                    'menu_id'      => $item['menu_id'],
                    'kuantitas'    => $item['kuantitas'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal'     => $item['subtotal'],
                    'opsi'         => $item['opsi'],
                ]);
            }
            
            Log::info('Detail pesanan tersimpan', ['jumlah_item' => count($cart)]);
            
            // Kosongin keranjang
            session()->forget('cart');
            
            return redirect()->route('aktivitas')->with('success', 'Pesanan berhasil! Cek riwayat kamu.');
            
        } catch (Exception $e) {
            Log::error('Gagal simpan pesanan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('pesanan')->with('error', 'Gagal menyimpan pesanan: ' . $e->getMessage());
        }
    }

    // ======================= QRIS PAYMENT (MIDTRANS) =======================
    public function generateQris(Request $request, $order_id) 
    {
        $pesanan = Pesanan::find($order_id);
        
        // 2. Request ke Midtrans 
        // ... logic request midtrans ...
        // NOTE: midtrans integration not implemented here. Create a placeholder
        // QR string to return so callers don't get an undefined variable.

        // Example placeholder QR payload (should be replaced with real Midtrans response)
        $qrString = base64_encode('qris:' . $pesanan->id . ':' . now()->timestamp);
        $response = (object) ['qr_string' => $qrString];

        // 3. Simpan payment_type dan status di table 'pesanans'
        $pesanan->update([
            'payment_type' => 'qris',
            'status' => 'pending'
        ]);

        // 4. Return data ke AJAX/Frontend
        return response()->json(['qr_string' => $response->qr_string]);
    }
}