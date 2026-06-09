<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class DetailPesanan extends Model
{
    protected $table = 'detail_pesanans';
 
    protected $fillable = [
        'pesanan_id', 'menu_id',
        'kuantitas', 'harga_satuan', 'subtotal', 'opsi',
    ];
 
    // Detail milik satu pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
 
    // Detail merujuk ke satu menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
