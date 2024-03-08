<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\SoccerMatchController;
use App\Http\Controllers\SoccerMatchTeamController;
use App\Http\Controllers\SoccerMatchPlayerController;
use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');

Route::prefix('players')->group(function () {
    Route::get('/', [PlayerController::class, 'index'])->name('players.index');
    Route::get('/create', [PlayerController::class, 'create'])->name('players.create');
    Route::post('/create', [PlayerController::class, 'store'])->name('players.store');
    Route::get('/update/{player}', [PlayerController::class, 'edit'])->name('players.edit');
    Route::put('/update/{player}', [PlayerController::class, 'store'])->name('players.update');
    Route::delete('/{player}', [PlayerController::class, 'destroy'])->name('players.destroy');
});

Route::prefix('soccerMatch')->group(function () {
    Route::get('/', [SoccerMatchController::class, 'index'])->name('soccer_match.index');
    Route::get('/create', [SoccerMatchController::class, 'create'])->name('soccer_match.create');
    Route::post('/create', [SoccerMatchController::class, 'store'])->name('soccer_match.store');
    Route::get('/update/{player}', [SoccerMatchController::class, 'edit'])->name('soccer_match.edit');
    Route::put('/update/{player}', [SoccerMatchController::class, 'store'])->name('soccer_match.update');
    Route::delete('/{player}', [SoccerMatchController::class, 'destroy'])->name('soccer_match.destroy');
});

Route::prefix('SoccerMatchPlayer')->group(function () {
    Route::post('/{soccerMatch}/confirm/{player}', [SoccerMatchPlayerController::class, 'confirm'])->name('soccer_match_player.confirm');
});

Route::prefix('soccerMatchTeam')->group(function () {
    Route::post('/{soccerMatch}', [SoccerMatchTeamController::class, 'create'])->name('soccer_match_team.create');
});