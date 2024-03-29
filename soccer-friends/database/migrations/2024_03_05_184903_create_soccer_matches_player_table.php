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
      $table->uuid('id')->primary();
      $table->uuid('soccer_match_id');
      $table->uuid('player_id');
      $table->boolean('confirm')->default(false);
      $table->timestamps();

      $table->foreign('soccer_match_id')->references('id')->on('soccer_matches')->onDelete('restrict');
      $table->foreign('player_id')->references('id')->on('players')->onDelete('restrict');
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
