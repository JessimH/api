<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\PostController;
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

Route::post('auth/register', [ApiTokenController::class, 'register']);

Route::post('auth/login', [ApiTokenController::class, 'login']);

Route::middleware('auth:sanctum')->post('posts', [PostController::class, 'create']);

Route::get('posts', [PostController::class, 'showAllPosts']);

Route::get('posts/{id}', [PostController::class, 'showPost']);


