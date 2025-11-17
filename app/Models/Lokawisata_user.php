<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokawisata_user extends Model
{
    use HasFactory;

    protected $table = 'lokawisata_user';

    protected $fillable = [
        'user_id',
        'lokawisata_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'lokawisata_user');
    }

    
}
