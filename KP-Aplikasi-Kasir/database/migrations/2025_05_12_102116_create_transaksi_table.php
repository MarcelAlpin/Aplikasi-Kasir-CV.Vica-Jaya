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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            $table->enum('status', ['Lunas', 'Belum Bayar']);
            $table->enum('order', ['Cash', 'Qris', 'Debit', 'Kredit']);
            $table->integer('total_bayar');
            $table->integer('pajak')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
