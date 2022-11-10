<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

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

Route::get('/file-import', [FileController::class, 'importView']);
Route::post('/import',[FileController::class,'import'])->name('import');
Route::post('file-upload', [FileController::class, 'store'])->name('file.store');

//Media library routes
Route::get('/chunkupload', [FileController::class, 'mediaLibrary'])->name('media-library');

//FILE UPLOADS CONTROLER
Route::post('/upload', [FileController::class, 'upload'])->name('upload');
Route::post('/delete', [FileController::class, 'delete'])->name('file-delete');

