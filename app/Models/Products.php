<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $guarded = [];

    protected $fillable = [
        'id_pembelian',
        'nama',
        'harga',
        'kategori',
        'stok',
        'created_at',
        'updated_at',
    ];

    public function penjualan_detail()
    {
        return $this->belongsTo(penjualan_detail::class);
    }

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'id', 'id_pembelian');
    }
}
