<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Satuan extends Model
{
    //
    use HasFactory;
    protected $table = 'satuan';
    
    // Primary key
    protected $primaryKey = 'id';

    // Primary key bukan incrementing integer
    public $incrementing = false;

    protected $fillable = ['id', 'nama', 'deskripsi'];
}
