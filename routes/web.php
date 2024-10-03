<?php

use Illuminate\Support\Facades\Route;

Route::get('/index', [\App\Http\Controllers\Web\Main\IndexController::class, 'index'])->name('index');
Route::get('/quran', [\App\Http\Controllers\Web\Quran\QuranController::class, 'index'])->name('quran');
