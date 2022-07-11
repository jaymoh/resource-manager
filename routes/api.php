<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::post('/storeLink', [PostController::class, 'storeLink'])->name('posts.storeLink');
Route::put('/updateLink/{id}', [PostController::class, 'updateLink'])->name('posts.updateLink');
Route::post('/storePdf', [PostController::class, 'storePdf'])->name('posts.storePdf');
Route::put('/updatePdf/{id}', [PostController::class, 'updatePdf'])->name('posts.updatePdf');
Route::post('/storeHtml', [PostController::class, 'storeHtml'])->name('posts.storeHtml');
Route::put('/updateHtml/{id}', [PostController::class, 'updateHtml'])->name('posts.updateHtml');

Route::get('/downloadPdf/{id}', [PostController::class, 'downloadPdfPost'])->name('posts.downloadPdf');
