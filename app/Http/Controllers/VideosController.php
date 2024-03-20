<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoRequest;
use App\Models\Folder;
use App\Models\Videos;
use App\Services\VimeoService;
use Illuminate\Http\Request;
use Vimeo\Laravel\Facades\Vimeo;

class VideosController extends Controller
{
    public function UploadToVimeo(VideoRequest $request)
    {
        $valid = $request->validated();
        $folder = Folder::find($valid['folder_id']);
        $folderID = VimeoService::findFolder($folder->title);
        if ($folderID) {
            $vimeoVideoId =  VimeoService::uploadVideoToFolder($valid['title'], $valid['video'], $folderID, 'anybody');
            Videos::create(['folder_id' => $valid['folder_id'], 'title' => $valid['title'], 'video_id' =>  $vimeoVideoId]);
        } else {
            $folderID = VimeoService::createFolder($folder->title);
            $vimeoVideoId = VimeoService::uploadVideoToFolder($valid['title'], $valid['video'], $folderID, 'anybody');
            Videos::create(['folder_id' => $valid['folder_id'], 'title' => $valid['title'], 'video_id' =>  $vimeoVideoId]);
        }
        return back()->with('success', 'Uploaded Successfully!');
    }

    public function deleteVideo(Videos $video)
    {
        if (VimeoService::deleteSpecificVideo($video->video_id)) {
            $video->delete();
            return back()->with('success', 'Video Deleted Successfully!');
        } else
            return back()->with('error', 'Something went wrong!');
    }

    public function folderDelete(Request $request)
    {
        $valid = $request->validate(['folder_id' => ['required']]);
        $folder = Folder::find($valid['folder_id']);
        $folderID = VimeoService::findFolder($folder->title);
        if ($folderID) {
            VimeoService::deleteFolderVideos($folderID);
            VimeoService::deleteFolderByID($folderID);
            $folder->delete();
            return back()->with('success', 'Folder and its videos are deleted!');
        }
    }

    public function watchVideo(Videos $video)
    {
        return view('watch-video', compact('video'));
    }



    public function createSubFolder()
    {
        $folderID = VimeoService::findFolder('Laravel');
        return VimeoService::createSubfolder($folderID, 'Vue');
    }
}
