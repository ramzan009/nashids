<?php

use Illuminate\Support\Facades\Route;


Route::get('/registration', [\App\Http\Controllers\Web\Registration\RegistrationController::class, 'index'])->name('registration');
Route::post('/registration_process', [\App\Http\Controllers\Web\Registration\RegistrationController::class, 'create'])->name('registration_process');
Route::get('/login', [\App\Http\Controllers\Web\Login\LoginController::class, 'index'])->name('login');
Route::post('/login', [\App\Http\Controllers\Web\Login\LoginController::class, 'create'])->name('login_process');
Route::get('/index', [\App\Http\Controllers\Web\Main\IndexController::class, 'index'])->name('index');
Route::get('/quran', [\App\Http\Controllers\Web\Quran\QuranController::class, 'index'])->name('quran');
Route::get('/nashid', [\App\Http\Controllers\Web\Nashid\NashidController::class, 'index'])->name('nashid');

