<?php

use App\Http\Controllers\Web\Login\LoginController;
use App\Http\Controllers\Web\Main\IndexController;
use App\Http\Controllers\Web\Nashid\NashidController;
use App\Http\Controllers\Web\Quran\QuranController;
use App\Http\Controllers\Web\Registration\RegistrationController;
use App\Http\Controllers\Web\Search\SearchController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'guest'], function() {
    Route::get('/registration', [RegistrationController::class, 'index'])->name('registration');
    Route::post('/registration', [RegistrationController::class, 'create'])->name('registration_process');

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'create'])->name('login_process');
});


Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/quran', [QuranController::class, 'index'])->name('quran');
Route::get('/nashid', [NashidController::class, 'index'])->name('nashid');
Route::post('/search', [SearchController::class, 'search'])->name('search');

