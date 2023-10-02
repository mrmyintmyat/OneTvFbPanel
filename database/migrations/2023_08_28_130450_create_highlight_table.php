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
        Schema::create('highlights', function (Blueprint $table) {
            $table->id();
            $table->string('match_time');
            $table->string('home_team_name');
            $table->string('home_team_logo');
            $table->integer('home_team_score')->nullable();
            $table->string('away_team_name');
            $table->string('away_team_logo');
            $table->integer('away_team_score')->nullable();
            $table->string('league_name')->default('Sports899 TV');
            $table->string('league_logo')->nullable();
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
        Schema::dropIfExists('highlights');
    }
};
