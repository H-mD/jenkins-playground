<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\FishController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function(){
    Route::post('/users/register', 'register');
    Route::post('/users/login', 'login');

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/users/logout', 'logout');
    });
});

Route::middleware('auth:sanctum')->group(function(){
    Route::controller(UserController::class)->group(function(){
        Route::get('/users', 'getUser');
        Route::put('/users', 'updateUser');
        Route::delete('/users', 'deleteUser');
    });

    Route::controller(CaptureController::class)->group(function(){
        Route::get('/captures', 'getCaptures');
        Route::get('/captures/count/{count}', 'getRecentCaptures');
        Route::get('/captures/{capture_id}', 'getCapturebyCaptureID');
        Route::post('/captures', 'storeCapture');
    });

    Route::controller(FishController::class)->group(function(){
        Route::get('/fishes', 'getAllFishes');
        Route::get('/fish/{fish_id}', 'getFishByID');
    });

    Route::controller(PostController::class)->group(function(){
        Route::post('/posts', 'storePost');
        Route::put('/posts/{post_id}', 'updatePost');
        Route::delete('/posts/{post_id}', 'deletePost');
        Route::post('/post/{post_id}/comment', 'storeComment');
        Route::post('/post/{post_id}/comment/{comment_id}/reply', 'storeReply');
        Route::post('/post/{post_id}/like', 'storeLike');
        Route::delete('/post/{post_id}/like', 'deleteLike');
        Route::post('/post/{post_id}/follow', 'storeFollow');
        Route::delete('/post/{post_id}/follow', 'deleteFollow');
    });

    Route::controller(ArticleController::class)->group(function(){
        Route::get('/articles', 'getArticles');
        Route::get('/articles/{article_id}', 'getArticleByID');
        Route::get('/articles/search/{keywords}', 'getArticlesByName');
    });

    Route::controller(ForumController::class)->group(function(){
        Route::get('/forums', 'getForums');
        Route::get('/forums/{article_id}', 'getForumByID');
        Route::get('/forums/search/{keywords}', 'getForumsByName');
    });
});