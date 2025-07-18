<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Barang;
use App\Models\User;

class Transaksi extends Model
{
    //
    use HasFactory;
    protected $table = 'transaksi';

    // Primary key
    protected $primaryKey = 'id';
    // Primary key bukan incrementing integer
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id','pembayaran','total_bayar','pajak', 'users_id'];
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
        public function detail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
