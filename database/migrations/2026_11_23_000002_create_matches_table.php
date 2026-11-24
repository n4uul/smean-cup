<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_home_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('team_away_id')->constrained('teams')->cascadeOnDelete();
            $table->dateTime('waktu_tanding');
            $table->string('lapangan');
            $table->string('babak');
            $table->integer('skor_home')->default(0);
            $table->integer('skor_away')->default(0);
            $table->enum('status_match', ['upcoming', 'live', 'finished'])->default('upcoming');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
