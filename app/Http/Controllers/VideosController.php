<?php

namespace App\Http\Controllers;

use App\Models\Videos;
use Illuminate\Http\Request;
use Vimeo\Laravel\Facades\Vimeo;

class VideosController extends Controller
{
    public function UploadToVimeo(Request $request)
    {
        $valid = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'video' => ['required', 'file', 'mimes:mp4,mkv,3gb,avi,flv,webm,mov']
        ]);

        $vimeoVideoLink = Vimeo::upload($valid['video'], [
            'name' =>  'Test',
            'description' => 'test video'
        ]);

        $vimeoVideoId = explode('/videos/', $vimeoVideoLink)[1];


        Videos::create(['title' => $valid['title'], 'video_id' =>  $vimeoVideoId]);
        return back()->with('success', 'Uploaded Successfully!');
    }

    public function watchVideo(Videos $video)
    {
        return view('watch-video', compact('video'));
    }
}
