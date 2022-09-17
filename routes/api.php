<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\userController;
use App\Http\Controllers\API\CommentController;

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
 // Comment
 Route::get('/posts/{id}/comments', [CommentController::class, 'index']); // all comments of a post
 Route::post('/posts/{id}/comments', [CommentController::class, 'store']); // create comment on a post
 Route::put('/comments/{id}', [CommentController::class, 'update']); // update a comment
 Route::delete('/comments/{id}', [CommentController::class, 'destroy']); // delete a comment

 // Like
 Route::post('/posts/{id}/likes', [LikeController::class, 'likeOrUnlike']); // like or dislike back a post

//Post management
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/posts',[PostController::class,'index']);
});

Route::post('/posts',[PostController::class,'store']);
Route::get('/posts/{id}',[PostController::class,'show']);
Route::put('/posts/{id}',[PostController::class,'update']);
Route::delete('/posts/{id}',[PostController::class,'delete']);

//User Login register
Route::get('/user',[userController::class,'user']);
Route::post('/logout',[userController::class,'logout']);
Route::post('/login',[userController::class,'login']);
Route::post('/register',[userController::class,'register']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
