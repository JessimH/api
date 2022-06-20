<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\ExplorerController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [ApiTokenController::class, 'register']);

Route::post('auth/login', [ApiTokenController::class, 'login']);

Route::middleware('auth:sanctum')->post('posts', [PostController::class, 'create']);

Route::middleware('auth:sanctum')->get('posts', [PostController::class, 'showFollowingPosts']);

Route::middleware('auth:sanctum')->get('profile/{id}', [ProfileController::class, 'showProfileContent']);

Route::middleware('auth:sanctum')->get('explorer', [ExplorerController::class, 'showExplorerContent']);

// Route explorer POST

Route::middleware('auth:sanctum')->get('map', [MapController::class, 'showDistance']);



