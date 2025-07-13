<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id('id_payment');
            $table->unsignedBigInteger('id_checkout');
            $table->dateTime('transaction_time');
            $table->string('transaction_status', 20);
            $table->text('pdf_url');
            $table->timestamps();

            $table->foreign('id_checkout')->references('id_checkout')->on('checkout');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
