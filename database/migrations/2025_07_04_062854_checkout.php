<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkout', function (Blueprint $table) {
            $table->id('id_checkout');
            $table->unsignedBigInteger('id_krg');
            $table->unsignedBigInteger('id_plg');
            $table->unsignedBigInteger('id_rwd')->nullable();
            $table->decimal('ongkir', 10, 2);
            $table->decimal('ttl_harga', 10, 2);
            $table->dateTime('tgl_checkout');
            $table->string('kurir', 50);
            $table->timestamps();
            $table->foreign('id_krg')->references('id_krg')->on('keranjang');
            $table->foreign('id_plg')->references('id_plg')->on('pelanggan');
            $table->foreign('id_rwd')->references('id_rwd')->on('reward')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout');
    }
};
