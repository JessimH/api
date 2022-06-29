<?php


use App\Http\Controllers\CommentController;
use App\Http\Controllers\SportController;
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

Route::middleware('auth:sanctum')->post('post', [PostController::class, 'create']);

Route::middleware('auth:sanctum')->get('posts', [PostController::class, 'showFollowingPosts']);

Route::middleware('auth:sanctum')->delete('post/{id}', [PostController::class, 'deletePost']);

// PROFILE

Route::middleware('auth:sanctum')->get('profile/{id}', [ProfileController::class, 'showProfileContent']);

Route::middleware('auth:sanctum')->post('updateprofile/{id}', [ProfileController::class, 'updateUser']);

// EXPLORER

Route::middleware('auth:sanctum')->get('explorer', [ExplorerController::class, 'showExplorerContent']);

Route::middleware('auth:sanctum')->post('userexplorer', [ExplorerController::class, 'showExplorerSearch']);

// MAP

Route::middleware('auth:sanctum')->get('map', [MapController::class, 'showDistance']);

// SESSIONS

Route::middleware('auth:sanctum')->post('session', [SessionController::class, 'create']);

Route::middleware('auth:sanctum')->delete('session/{id}', [SessionController::class, 'deleteSession']);

// COMMENTS

Route::middleware('auth:sanctum')->post('comment', [CommentController::class, 'create']);

Route::middleware('auth:sanctum')->delete('comment/{id}', [CommentController::class, 'deleteComment']);

// SPORTS

Route::get('sports', [SportController::class, 'getSports']);

// LIKES

Route::middleware('auth:sanctum')->post('like', [PostController::class, 'addLike']);

Route::middleware('auth:sanctum')->post('follow', [ProfileController::class, 'addRmFollow']);






