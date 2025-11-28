<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Barang_keluar;
use App\Models\Barang_masuk;

class Lokawisata extends Model
{
    use HasFactory;
    protected $table = 'lokawisata';
    protected $fillable = [
        'nama_lokawisata',
        'keterangan',
        'alamat',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function barangKeluar()
    {
        return $this->hasMany(Barang_keluar::class, 'lokawisata_id', 'id');
    }

    public function barangMasuk()
    {
        return $this->hasMany(Barang_masuk::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }
}
