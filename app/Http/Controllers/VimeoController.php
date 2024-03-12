<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VimeoController extends Controller
{
    public function UploadToVimeo(Request $request)
    {
        $valid = $request->validate([
            'video' => ['required', 'file', 'mimes:mp4,mkv,3gb,avi,flv,webm,mov']
        ]);

        
    }
}
