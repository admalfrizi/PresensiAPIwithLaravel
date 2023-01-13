<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'id',
        'user_id',
        'keterangan',
        'latitude',
        'longitude',
        'tanggal',
        'masuk',
        'pulang',
        'created_at'
    ];
}
