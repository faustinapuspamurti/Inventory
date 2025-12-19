<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang_masuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    protected $fillable = [
        'barang_id',
        'jumlah_masuk',
        'tanggal_masuk',
        'deskripsi',
        'evidence',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function hargaBarang()
    {
        return $this->hasMany(Barang_masuk::class);
    }
    
}
