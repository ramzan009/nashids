<?php

use App\Http\Controllers\Web\Login\LoginController;
use App\Http\Controllers\Web\Main\IndexController;
use App\Http\Controllers\Web\Nashid\NashidController;
use App\Http\Controllers\Web\Profile\ChangProfileController;
use App\Http\Controllers\Web\Profile\ProfileController;
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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/change', [ProfileController::class, 'profileChange'])->name('profile_change');
    Route::put('/profile/change', [ProfileController::class, 'profileUpdate'])->name('profile_update');
    Route::delete('/profile/delete', [ProfileController::class, 'delete'])->name('profile_delete');
    Route::post('/profile/change/avatar', [ProfileController::class, 'changeAvatar'])->name('profile_change_avatar');
    Route::post('/profile/avatar/delete', [ProfileController::class, 'deleteAvatar'])->name('profile_avatar_delete');
});


Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/quran', [QuranController::class, 'index'])->name('quran');
Route::get('/nashid', [NashidController::class, 'index'])->name('nashid');
Route::post('/search', [SearchController::class, 'search'])->name('search');

