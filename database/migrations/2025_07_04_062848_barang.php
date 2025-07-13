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
        Schema::create('barang', function (Blueprint $table) {
            $table->id('id_brg');
            $table->text('img')->nullable();
            $table->string('nm_brg', 100);
            $table->integer('id_ktg');
            $table->string('merk', 100);
            $table->string('stok', 5);
            $table->string('satuan_brg');
            $table->decimal('berat', 6, 2);
            $table->decimal('diskon', 5, 2);
            $table->decimal('harga_brg', 10,2);
            $table->text('desk_brg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
