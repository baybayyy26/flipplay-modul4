<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// [RUBRIK - ROUTES] Semua endpoint didaftarkan di sini. Cek dengan: php artisan route:list --path=api

// CRUD Media: 5 route sekaligus (index, store, show, update, destroy).
// parameters(): kata "media" bentuk tunggalnya dibaca Laravel sebagai "medium",
// jadi parameter URL ditetapkan manual menjadi {media}.
Route::apiResource('media', MediaController::class)->parameters(['media' => 'media']);

// User (hanya lihat data)
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);

// Custom action My Vibe List (di luar CRUD, mengubah relasi Media <-> User)
Route::get('/users/{user}/vibe-list', [UserController::class, 'vibeList']);
Route::post('/users/{user}/vibe-list', [UserController::class, 'addToVibeList']);
Route::delete('/users/{user}/vibe-list/{media}', [UserController::class, 'removeFromVibeList']);
