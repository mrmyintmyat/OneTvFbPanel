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
        Schema::create('vnmatches', function (Blueprint $table) {
            $table->id();
            $table->string('match_time');
            $table->string('home_team_name');
            $table->longText('home_team_logo');
            $table->integer('home_team_score')->nullable();
            $table->string('away_team_name');
            $table->longText('away_team_logo');
            $table->integer('away_team_score')->nullable();
            $table->foreignId('league_id')->constrained('leagues')->onDelete('cascade');
            $table->string('match_status');
            $table->json('servers')->nullable();
            $table->boolean('is_auto_match');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vnmatches');
    }
};
