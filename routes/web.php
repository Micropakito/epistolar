<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\FriendshipController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Historias: TODAS las rutas, incluida show
    Route::resource('stories', StoryController::class);

    // Personajes de una historia
    Route::prefix('stories/{story}')->group(function () {
        Route::get('characters', [CharacterController::class, 'index'])->name('stories.characters.index');
        Route::get('characters/create', [CharacterController::class, 'create'])->name('stories.characters.create');
        Route::post('characters', [CharacterController::class, 'store'])->name('stories.characters.store');
        Route::get('characters/{character}/edit', [CharacterController::class, 'edit'])->name('stories.characters.edit');
        Route::put('characters/{character}', [CharacterController::class, 'update'])->name('stories.characters.update');
        Route::delete('characters/{character}', [CharacterController::class, 'destroy'])->name('stories.characters.destroy');
    });

    // Amigos
    Route::get('friends', [FriendshipController::class, 'index'])->name('friends.index');
    Route::post('friends', [FriendshipController::class, 'store'])->name('friends.store');
    Route::put('friends/{friendship}/accept', [FriendshipController::class, 'accept'])->name('friends.accept');
    Route::put('friends/{friendship}/decline', [FriendshipController::class, 'decline'])->name('friends.decline');
    Route::delete('friends/{friendship}', [FriendshipController::class, 'destroy'])->name('friends.destroy');
});

require __DIR__.'/auth.php';
