<?php

use App\Http\Controllers\FolderController;
use App\Http\Controllers\VideosController;
use App\Models\Folder;
use App\Models\Vimeo;
use Illuminate\Support\Facades\Route;
use App\Models\Videos;


Route::get('/', function () {
    $videos = Videos::paginate(10);
    $folders = Folder::query()->orderBy('title', 'asc')->get();
    return view('welcome', compact('videos', 'folders'));
});
Route::resource('folder', FolderController::class)->except('show');
Route::post('vimeo/upload', [VideosController::class, 'UploadToVimeo'])->name('upload.video');
Route::get('watch/video/{video}', [VideosController::class, 'watchVideo'])->name('watch.video');
Route::get('delete-video/{video}', [VideosController::class, 'deleteVideo'])->name('delete.video');
Route::post('folder-wise-delete', [VideosController::class, 'folderDelete'])->name('delete.folder');


Route::get('create-sub-folder', [VideosController::class, 'createSubFolder']);
