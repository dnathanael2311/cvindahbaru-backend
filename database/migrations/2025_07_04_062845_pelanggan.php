<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_plg');
            $table->string('user_plg', 30)->unique();
            $table->string('nm_plg', 100);
            $table->string('no_plg', 15)->unique();
            $table->string('email_plg', 100)->unique();
            $table->date('tgl_lahir');
            $table->string('alamat', 255);
            $table->string('provinsi', 100); // ✅ Simpan langsung nama kota (bukan ID)
            $table->string('kota', 100); // ✅ Simpan langsung nama kota (bukan ID)
            $table->string('pass_plg', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
