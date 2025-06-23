<?php

use App\Http\Controllers\EnclosureController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware('auth')->name('home');

Route::middleware('auth')->group(function() {
    Route::resource('enclosures', EnclosureController::class);
});

Route::middleware('auth')->group(function() {
    Route::resource('animals', AnimalController::class);
});

Route::patch('/animals/{id}/restore', [AnimalController::class, 'restore'])->middleware('auth')->name('animals.restore');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
