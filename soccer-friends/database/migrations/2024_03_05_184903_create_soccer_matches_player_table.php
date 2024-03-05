<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soccer_matches_player', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soccer_matches_id')->constrained('soccer_matches')->onDelete('restrict');
            $table->foreignId('player_id')->constrained('players')->onDelete('restrict');
            $table->boolean('confirm')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('soccer_matches_player');
    }
};
