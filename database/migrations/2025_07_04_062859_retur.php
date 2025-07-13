<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retur', function (Blueprint $table) {
            $table->id('id_rt');
            $table->unsignedBigInteger('id_order');
            $table->dateTime('tgl_rt');
            $table->string('st_retur', 10);
            $table->timestamps();

            $table->foreign('id_order')->references('id_order')->on('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur');
    }
};
