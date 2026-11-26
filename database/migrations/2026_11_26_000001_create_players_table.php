<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->string('nama_lengkap');
            $table->unsignedTinyInteger('nomor_punggung');
            $table->enum('posisi', ['Kiper', 'Anchor', 'Flank', 'Pivot'])->default('Flank');
            $table->string('nisn', 30)->nullable();
            $table->unsignedInteger('jumlah_gol')->default(0);
            $table->unsignedTinyInteger('jumlah_kartu_kuning')->default(0);
            $table->unsignedTinyInteger('jumlah_kartu_merah')->default(0);
            $table->timestamps();

            $table->unique(['team_id', 'nomor_punggung']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
