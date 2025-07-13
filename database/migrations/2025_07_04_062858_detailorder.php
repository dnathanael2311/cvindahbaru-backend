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
        Schema::create('detailorder', function (Blueprint $table) {
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_brg');
            $table->integer('dor_qty');
            $table->decimal('dor_hargasatuan', 10, 2);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_order')->references('id_order')->on('order');
            $table->foreign('id_brg')->references('id_brg')->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailorder');
    }
};
