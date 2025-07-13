<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id('id_adm');
            $table->string('nm_adm', 100);
            $table->string('user_adm', 30)->unique();
            $table->string('no_adm', 15)->unique();
            $table->string('email_adm', 100)->unique();
            $table->string('pass_adm', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
