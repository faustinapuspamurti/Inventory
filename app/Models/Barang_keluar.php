<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use App\Models\Lokawisata;

class Barang_keluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';
    protected $fillable = [
        'barang_id',
        'tanggal_keluar',
        'jumlah_keluar',
        'harga_satuan',
        'harga_total',
        'lokawisata_id',
        'keterangan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function lokawisata()
    {
        return $this->belongsTo(Lokawisata::class, 'lokawisata_id');
    }

    public function harga_keluar()
    {
        return $this->belongsTo(Barang_masuk::class);
    }
}
