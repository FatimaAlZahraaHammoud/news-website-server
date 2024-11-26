<?php

namespace App\Http\kernel;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\JWTAuthController;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Middleware\AdminMiddleware;

Route::post("/register", [JWTAuthController::class, "register"]);
Route::post("/login", [JWTAuthController::class, "login"]);

Route::middleware(JWTMiddleware::class)->group(function () {
    Route::get('/getNews', [NewsController::class, 'get_news']);

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::post('/news/create', [NewsController::class,'create_news']);
        Route::post('/news/update/{id}', [NewsController::class,'update_news']);
        Route::delete('/news/delete/{id}', [NewsController::class, 'delete_news']);
    });

    Route::post('/articles/createArticle', [ArticleController::class,'create_article']);
    Route::post('/articles/updateAticle', [ArticleController::class,'update_article']);
});