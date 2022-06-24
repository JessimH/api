<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;


// AUTH
Route::post('auth/register', [ApiTokenController::class, 'register']);

Route::post('auth/login', [ApiTokenController::class, 'login']);

//POSTS

Route::middleware('auth:sanctum')->post('posts', [PostController::class, 'create']);

Route::middleware('auth:sanctum')->get('posts', [PostController::class, 'showFollowingPosts']);

Route::middleware('auth:sanctum')->put('posts/{id}', [PostController::class, 'updatePost']);

Route::middleware('auth:sanctum')->delete('posts/{id}', [PostController::class, 'deletePost']);

Route::middleware('auth:sanctum')->get('profile/{id}', [ProfileController::class, 'showProfileContent']);

Route::middleware('auth:sanctum')->get('explorer', [ExplorerController::class, 'showExplorerContent']);

// Route explorer POST

Route::middleware('auth:sanctum')->get('map', [MapController::class, 'showDistance']);

Route::middleware('auth:sanctum')->post('sessions', [SessionController::class, 'create']);

Route::middleware('auth:sanctum')->delete('sessions/{id}', [SessionController::class, 'deleteSession']);



