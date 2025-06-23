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
        Schema::create('transaksi_detail', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            // import id transaksi
            $table->string('transaksi_id', 12);
            $table->foreign('transaksi_id')->references('id')->on('transaksi')->onDelete('cascade');
            // import id barang
            $table->string('barang_id', 12);
            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            $table->integer('qty');
            $table->integer('harga'); // harga satuan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_detail');
    }
};
