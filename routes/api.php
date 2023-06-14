<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;

Route::apiResource('articles', ArticleController::class)
    ->names('api.v1.articles');

// Route::get('articles/{article}', [ArticleController::class, 'show'])
//     ->name('api.v1.articles.show');

// Route::get('articles/', [ArticleController::class, 'index'])
//     ->name('api.v1.articles.index');

// Route::post('articles/', [ArticleController::class, 'store'])
//     ->name('api.v1.articles.store');

// Route::patch('articles/{article}', [ArticleController::class, 'update'])
//     ->name('api.v1.articles.update');

// Route::delete('articles/{article}', [ArticleController::class, 'destroy'])
//     ->name('api.v1.articles.destroy');
