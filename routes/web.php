<?php

use Illuminate\Support\Facades\Route;

Route::get('/{name?}', [\App\Http\Controllers\RaceController::class, 'index'])->name('race.index');
