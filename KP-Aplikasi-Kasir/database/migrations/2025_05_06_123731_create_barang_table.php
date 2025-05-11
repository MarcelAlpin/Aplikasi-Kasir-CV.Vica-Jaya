<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama',100);   
            $table->text('deskripsi')->nullable();
            $table->longText('gambar')->nullable();
            $table->integer('stok')->default(0);
            $table->integer('harga')->default(0);
            $table->timestamps();
            $table->foreignId('kategori_id')->constraine()->onDelete('cascade');
            $table->foreignId('satuan_id')->constraine()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
