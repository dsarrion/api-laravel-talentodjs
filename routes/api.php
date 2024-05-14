<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); 



Route::get('home', [TrackController::class, 'all']);

Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);


Route::resource('tracks',TrackController::class);
Route::get('tracks/by-category/{categoryId}', [TrackController::class, 'getByCategory']);

Route::resource('categories',CategoryController::class);
Route::resource('comments',CommentController::class);
Route::resource('likes',LikeController::class);


Route::middleware('auth:sanctum')->group(function () { 
    
    Route::get('user/details/{id}', [UserController::class, 'detail']);
    Route::put('user/update', [AuthController::class, 'update']);
    Route::post('user/upload', [UserController::class, 'uploadAvatar']);
    Route::get('user/avatar/{filename}', [UserController::class, 'getImage']);

});