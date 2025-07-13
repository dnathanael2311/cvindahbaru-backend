<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expedisi', function (Blueprint $table) {
            $table->id('id_exp');
            $table->string('nm_exp', 100);
            $table->string('no_exp', 15)->unique();
            $table->string('email_exp', 50)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expedisi');
    }
};
