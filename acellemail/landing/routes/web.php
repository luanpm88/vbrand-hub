<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/features', [PageController::class, 'features'])->name('features');
Route::get('/email-marketing', [PageController::class, 'emailMarketing'])->name('email-marketing');
Route::get('/automation', [PageController::class, 'automation'])->name('automation');
Route::get('/integrations', [PageController::class, 'integrations'])->name('integrations');
Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
Route::get('/security', [PageController::class, 'security'])->name('security');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/help', [PageController::class, 'help'])->name('help');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/sitemap.xml', function () {
    return response()
        ->view('sitemap')
        ->header('Content-Type', 'application/xml');
})->name('sitemap');
