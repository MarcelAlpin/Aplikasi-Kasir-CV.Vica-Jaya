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
            $table->string('id', 12)->primary();
            $table->string('nama',100);   
            $table->text('deskripsi')->nullable();
            $table->longText('gambar')->nullable();
            $table->integer('stok')->default(0);
            $table->integer('harga')->default(0);
            $table->timestamps();
            // import id kategori
            $table->string('kategori_id', 8);
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
            // import id satuan
            $table->string('satuan_id', 8);
            $table->foreign('satuan_id')->references('id')->on('satuan')->onDelete('cascade');
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
