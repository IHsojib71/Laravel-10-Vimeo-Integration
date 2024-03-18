<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoRequest;
use App\Models\Videos;
use App\Services\VimeoService;
use Illuminate\Http\Request;
use Vimeo\Laravel\Facades\Vimeo;

class VideosController extends Controller
{
    public function UploadToVimeo(VideoRequest $request)
    {
        $valid = $request->validated();

        $folderID = VimeoService::findFolder('Laravel');
        if($folderID) {
            $vimeoVideoId =  VimeoService::uploadVideoToFolder($valid['title'], $valid['video'], $folderID, 'anybody');
            Videos::create(['title' => $valid['title'], 'video_id' =>  $vimeoVideoId]);
        }
        else{
            $folderID = VimeoService::createFolder('Laravel');
            $vimeoVideoId = VimeoService::uploadVideoToFolder($valid['title'], $valid['video'], $folderID, 'anybody');
            Videos::create(['title' => $valid['title'], 'video_id' =>  $vimeoVideoId]);
        }
        return back()->with('success', 'Uploaded Successfully!');
    }

    public function deleteVideo(Videos $video)
    {
        if(VimeoService::deleteSpecificVideo($video->video_id)) {
            $video->delete();
            return back()->with('success', 'Video Deleted Successfully!');
        }
        else
            return back()->with('error', 'Something went wrong!');
    }

    public function watchVideo(Videos $video)
    {
        return view('watch-video', compact('video'));
    }




}
