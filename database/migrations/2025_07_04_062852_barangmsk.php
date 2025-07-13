<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangmsk', function (Blueprint $table) {
            $table->id('id_msk');
            $table->unsignedBigInteger('id_brg');
            $table->unsignedBigInteger('id_exp');
            $table->integer('qty_msk');
            $table->dateTime('tgl_msk');
            $table->text('desk_msk');
            $table->timestamps();

            $table->foreign('id_brg')->references('id_brg')->on('barang');
            $table->foreign('id_exp')->references('id_exp')->on('expedisi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangmsk');
    }
};
