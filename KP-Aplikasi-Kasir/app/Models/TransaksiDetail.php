<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiDetail extends Model
{
    //
    protected $table = 'transaksi_detail';

    // Primary key
    protected $primaryKey = 'id';
    // Primary key bukan incrementing integer
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'transaksi_id','barang_id','harga','qty',];
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
