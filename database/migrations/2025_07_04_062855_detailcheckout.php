<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detailcheckout', function (Blueprint $table) {
            $table->unsignedBigInteger('id_checkout');
            $table->unsignedBigInteger('id_brg');
            $table->decimal('dt_hargasatuan', 10, 2);
            $table->integer('dt_qty');
            $table->timestamps();

            $table->primary(['id_checkout', 'id_brg']);

            $table->foreign('id_checkout')->references('id_checkout')->on('checkout');
            $table->foreign('id_brg')->references('id_brg')->on('barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detailcheckout');
    }
};
