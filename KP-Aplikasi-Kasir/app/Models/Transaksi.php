<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    //
    use HasFactory;
    protected $table = 'transaksi';

    // Primary key
    protected $primaryKey = 'id';
    // Primary key bukan incrementing integer
    public $incrementing = false;

    protected $fillable = ['id', 'atas_nama','status','order','total_bayar','pajak',];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function detail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
