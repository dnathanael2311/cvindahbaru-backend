<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('isikeranjang', function (Blueprint $table) {
        $table->unsignedBigInteger('id_krg');
        $table->unsignedBigInteger('id_brg');
        $table->integer('krg_qty');
        $table->timestamps();

        $table->primary(['id_krg', 'id_brg']); // ✅ PRIMARY KEY gabungan
        $table->foreign('id_krg')->references('id_krg')->on('keranjang')->onDelete('cascade');
        $table->foreign('id_brg')->references('id_brg')->on('barang')->onDelete('cascade');
    });
}
};
