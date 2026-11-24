<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('nama_tim')->unique();
            $table->string('asal_sekolah');
            $table->string('nama_pelatih');
            $table->string('no_wa', 20);
            $table->string('logo_tim')->nullable();
            $table->string('berkas_syarat')->nullable();
            $table->string('status_verifikasi')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
