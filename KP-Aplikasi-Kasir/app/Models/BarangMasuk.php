<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasuk extends Model
{
    use HasFactory;
    
    protected $table = 'barang_masuk';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['id','barang_id','jumlah_masuk','harga'];
    
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function agen()
    {
        return $this->belongsTo(Agen::class);
    }
}
