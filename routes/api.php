<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::post('user', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('validateToken', [AuthController::class, 'validateToken']);

Route::group(['prefix' => 'free'], function () {
  Route::get('note', [NoteController::class, 'indexPublic']);
  Route::get('note/{id}', [NoteController::class, 'showPublic']);
  Route::post('note', [NoteController::class, 'storePublic']);
  Route::delete('note/{id}', [NoteController::class, 'destroyPublic']);
});

Route::middleware(['api', 'prefix' => 'auth'])->group(function () {
  Route::post('category', [CategoryController::class, 'store']);
  Route::get('category', [CategoryController::class, 'index']);
  Route::delete('category/{id}', [CategoryController::class, 'destroy']);
  Route::get('category/{id}/notes', [CategoryController::class, 'getNotes']);

  Route::get('note/{id}', [NoteController::class, 'show']);
  Route::post('note', [NoteController::class, 'store']);
  Route::delete('note/{id}', [NoteController::class, 'destroy']);
});
