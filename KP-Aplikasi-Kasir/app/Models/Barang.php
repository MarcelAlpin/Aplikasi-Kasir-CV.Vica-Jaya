<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    //
    use HasFactory;
    protected $table = 'barang';

    // Primary key
    protected $primaryKey = 'id';

    // Primary key bukan incrementing integer
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama',
        'deskripsi',
        'gambar',  // Make sure this is included
        'stok',
        'harga',
        'kategori_id',
        'satuan_id'
    ];

public function kategori()
{
    return $this->belongsTo(Kategori::class);
}

public function satuan()
{
    return $this->belongsTo(Satuan::class);
}
}

