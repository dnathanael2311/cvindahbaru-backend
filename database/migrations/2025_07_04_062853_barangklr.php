<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangklr', function (Blueprint $table) {
            $table->id('id_klr');
            $table->unsignedBigInteger('id_brg');
            $table->integer('qty_klr');
            $table->dateTime('tgl_klr');
            $table->text('desk_klr');
            $table->timestamps();

            $table->foreign('id_brg')->references('id_brg')->on('barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangklr');
    }
};
