<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id('id_order');
            $table->unsignedBigInteger('id_plg');
            $table->unsignedBigInteger('id_checkout');
            $table->timestamps();

            $table->foreign('id_plg')->references('id_plg')->on('pelanggan');
            $table->foreign('id_checkout')->references('id_checkout')->on('checkout');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
