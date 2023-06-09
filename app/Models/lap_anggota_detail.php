<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lap_anggota_detail extends Model
{
    use HasFactory;
    protected $table = 'lap_anggota_detail';
    protected $primaryKey = 'id';
    protected $guarded = [];

    protected $fillable = [
        'id_user',
        'id_penjualan',
        'id_lap_anggota',
        'total_bayar',
        'credit',
        'credit_masuk',
        'credit_keluar',
        'poin',
        'poin_masuk',
        'poin_keluar',
        'tanggal',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'id', 'id_user');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'id', 'id_penjualan');
    }

    public function lap_anggota()
    {
        return $this->hasMany(lap_anggota::class, 'id', 'id_lap_anggota');
    }
}
