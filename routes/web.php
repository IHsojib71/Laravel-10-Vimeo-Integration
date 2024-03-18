<?php

use App\Http\Controllers\FolderController;
use App\Http\Controllers\VideosController;
use App\Models\Vimeo;
use Illuminate\Support\Facades\Route;
use App\Models\Videos;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $videos = Videos::paginate(10);
    return view('welcome',['videos' => $videos]);
});
Route::resource('folder', FolderController::class);
Route::post('vimeo/upload', [VideosController::class,'UploadToVimeo'])->name('upload.video');
Route::get('watch/video/{video}', [VideosController::class,'watchVideo'])->name('watch.video');
Route::get('delete-video/{video}', [VideosController::class,'deleteVideo'])->name('delete.video');


