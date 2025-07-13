<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id('id_krg');
            $table->unsignedBigInteger('id_plg');
            $table->timestamps();

            $table->foreign('id_plg')->references('id_plg')->on('pelanggan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
