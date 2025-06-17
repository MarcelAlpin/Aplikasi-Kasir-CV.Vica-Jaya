<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agen extends Model
{
    use HasFactory;

    protected $table = 'agen';

    // Primary key
    protected $primaryKey = 'id';

    // Primary key bukan incrementing integer
    public $incrementing = false;

    protected $fillable = ['id', 'nama', 'perushaan', 'alamat', 'no_telepon', 'email'];
}
