<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\JWTAuthController;
use App\Http\Middleware\JWTMiddleware;
use App\Http\Middleware\AdminMiddleware;

Route::post("/register", [JWTAuthController::class, "register"]);
Route::post("/login", [JWTAuthController::class, "login"]);

Route::get('/getNews', [NewsController::class, 'get_news']);

Route::middleware('admin')->group(function () {
    Route::post('/news/create', [NewsController::class,'create_news']);
    Route::post('/news/update/{id}', [NewsController::class,'update_news']);
    Route::delete('/news/delete/{id}', [NewsController::class, 'delete_news']);
});

Route::get('/articles/createArticle', [ArticleController::class,'create_article']);
Route::get('/articles/updateAticle', [ArticleController::class,'update_article']);