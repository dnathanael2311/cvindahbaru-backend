<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward', function (Blueprint $table) {
            $table->id('id_rwd');
            $table->unsignedBigInteger('id_plg');
            $table->string('jenis_rwd', 15); // 'diskon_total' / 'diskon_ongkir'
            $table->decimal('value_rwd', 5, 2); // nilai diskon
            $table->dateTime('expired_rwd');
            $table->timestamps();

            $table->foreign('id_plg')->references('id_plg')->on('pelanggan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward');
    }
};
