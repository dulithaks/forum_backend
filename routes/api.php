<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('posts', [PostsController::class, 'index']);
    Route::post('posts', [PostsController::class, 'store']);
    Route::put('posts/{post}/approve', [PostsController::class, 'approve']);
    Route::put('posts/{post}/reject', [PostsController::class, 'reject']);
    Route::delete('posts/{post}', [PostsController::class, 'destroy']);

    Route::get('posts/{post}/comments', [CommentsController::class, 'index']);
    Route::post('posts/{post}/comments', [CommentsController::class, 'store']);
});
