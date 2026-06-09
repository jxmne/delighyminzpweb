<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Pesanan extends Model
{
    protected $fillable = [
        'pelanggan_id', 'tanggal_pesan', 'jumlah_pax',
        'total_harga', 'status', 'catatan','payment_type','payment_status',
    ];

    protected $casts = [
        'tanggal_pesan' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
 
    // Satu pesanan dimiliki satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'pelanggan_id');
    }
 
    // Satu pesanan punya banyak detail item
    public function details()
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
