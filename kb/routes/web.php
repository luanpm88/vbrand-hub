<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [ArticleController::class, 'index'])->name('home');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/category/{slug}', [ArticleController::class, 'category'])->name('articles.category');
Route::get('/tag/{slug}', [ArticleController::class, 'tag'])->name('articles.tag');
Route::get('/search', [ArticleController::class, 'search'])->name('articles.search');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.articles.index'));
    Route::resource('articles', Admin\ArticleController::class);
});
