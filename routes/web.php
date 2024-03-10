<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', [UploadController::class, 'index']);
Route::get('/progress', [UploadController::class, 'progress'])->name('progress');

Route::post('/upload/file', [UploadController::class, 'uploadFile'])->name('processFile');
Route::get('/progress/data', [UploadController::class, 'progressData'])->name('progressData');

// Route::get('/queue-work', function () {
//     $exitCode = Artisan::call('queue:work');
//     return 'ya';
// })->name('queueWork');

// Route::get('/config-cache', function () {
//     $exitCode = Artisan::call('config:cache');
//     return '<h1>Clear Config cleared</h1>';
// });

// Route::get('/optimize-clear', function () {
//     $exitCode = Artisan::call('optimize:clear');
//     return '<h1>Optimize cleared</h1>';
// });
