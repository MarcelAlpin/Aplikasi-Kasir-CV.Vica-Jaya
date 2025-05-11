<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    //
    use HasFactory;
    protected $table = 'barang';
    protected $fillable = ['nama', 'deskripsi', 'gambar', 'stok', 'harga', 'kategori_id', 'satuan_id'];

public function kategori()
{
    return $this->belongsTo(Kategori::class);
}

public function satuan()
{
    return $this->belongsTo(Satuan::class);
}
}

