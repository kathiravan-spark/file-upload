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

Route::get('/',[FileController::class,'index'])->name('view');
Route::get('/file-import',[FileController::class,'importView'])->name('import-view');
Route::post('/import',[FileController::class,'import'])->name('import');
Route::get('/export-users',[FileController::class,'exportUsers'])->name('export-users');
Route::post('file-upload', [FileController::class, 'store'])->name('file.store');

