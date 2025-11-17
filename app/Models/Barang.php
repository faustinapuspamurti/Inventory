<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    protected $fillable = [
        'nama_barang',
        'deskripsi',
        'satuan',
        'harga_satuan',
        'harga_total',
        'jumlah_stok',
    ];

    public function barangMasuk()
    {
        return $this->hasMany(Barang_masuk::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function barangKeluar()
    {
        return $this->hasMany(Barang_keluar::class, 'barang_id');
    }

}
