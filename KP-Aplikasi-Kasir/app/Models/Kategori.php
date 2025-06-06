<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';
    
    // Primary key
    protected $primaryKey = 'id';

    // Primary key bukan incrementing integer
    public $incrementing = false;

    // Tipe data primary key
    protected $keyType = 'string';
    protected $fillable = ['id', 'nama', 'deskripsi'];
}
