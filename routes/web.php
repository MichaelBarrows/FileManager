<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// Upload routes
Route::get('/upload', [FileController::class, 'upload_file']);
Route::post('/upload', [FileController::class, 'store_file']);

// Download routes
Route::get('/', [FileController::class, 'show_files']);
Route::get('/download/{id}', [FileController::class, 'download_file']);
Route::delete('/delete/{id}', [FileController::class, 'delete_file']);

// Admin route
Route::get('/admin', [AdminController::class, 'show_files']);

require __DIR__.'/auth.php';
