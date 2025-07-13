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
        Schema::create('detailretur', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rt');   // FK ke retur
            $table->unsignedBigInteger('id_brg');  // FK ke barang
            $table->integer('qty_rt');
            $table->text('alasan');
            $table->timestamps();

            $table->foreign('id_rt')->references('id_rt')->on('retur');
            $table->foreign('id_brg')->references('id_brg')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailretur');
    }
};
