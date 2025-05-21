<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    //
    use HasFactory;
    protected $table = 'transaksi';
    protected $fillable = ['nama', 'total'];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function detail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
