<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aktivitas extends Model
{
    protected $table = 'aktivitas';
    protected $fillable = [
        'user_id',
        'nama_user',
        'halaman',
        'keterangan',
        'ip_address',
        'user_agent',
    ];
    public $timestamps = true; // aktifkan timestamps
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
