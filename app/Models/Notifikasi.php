<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $fillable = [
        'barang_id',
        'nama_barang',
        'lokawisata_id',
        'nama_lokawisata',
        'jumlah',
        'status',
        'pesan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function lokawisata()
    {
        return $this->belongsTo(Lokawisata::class);
    }
}
