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
            'name' =>  $valid['title'],
            'folder' => '19872628',
        ]);
        $vimeoVideoId = explode('/videos/', $vimeoVideoLink)[1];
        Videos::create(['title' => $valid['title'], 'video_id' =>  $vimeoVideoId]);
        return back()->with('success', 'Uploaded Successfully!');
    }

    public function watchVideo(Videos $video)
    {
        return view('watch-video', compact('video'));
    }

    public static function findFolder(string $folderName)
    {
        $folderId = null;
        // Make a request to get the list of user's folders
        $response = Vimeo::request('/me/projects', ['per_page' => 400], 'GET');
        // Iterate through the response to find the desired folder by name
        $folders = $response['body']['data'];
        foreach ($folders as $folder) {
            if ($folder['name'] === $folderName) {
                $folderId = $folder['uri'];
                break;
            }
        }
        return $folderId;
    }

    public static function createFolder(string $folderName)
    {
        $response = Vimeo::request('/me/projects', ['name' => $folderName], 'POST');
        $folderId = $response['body']['uri'];
        return $folderId;
    }

    public static function findProject(string $projectName)
    {
        $projectId = null;
        // Make a request to get the list of user's projects (folders)
        $response = Vimeo::request('/me/projects', ['per_page' => 100], 'GET');
        // Check if the request was successful
        if ($response['status'] === 200) {
            $projects = $response['body']['data'];
            // Iterate through the projects
            foreach ($projects as $project) {
                // Check if the project name matches the desired name
                if ($project['name'] === $projectName) {
                    $projectId = $project['uri'];
                }
            }
            // If the project with the given name was not found
            return $projectId;
        }
    }


    public static function createSubfolder(string $parentFolderID, string $subfolderName)
    {
        // Make a POST request to create the subfolder
        $response = Vimeo::request("/me/projects/{$parentFolderID}/projects", array(
            'name' => $subfolderName,
        ), 'POST');

        // Check if the request was successful
        if ($response['status'] === 201) {
            // Subfolder (project) created successfully
            return $response['body']['uri'];
        } else {
            // Handle errors
            $errorMessage = $response['body']['error'];
            // Handle error
            return null;
        }
    }

    public static function deleteSpecificVideo(string $videoID)
    {
        // Make a DELETE request to delete the video
        $response = Vimeo::request("/videos/{$videoID}", [], 'DELETE');
        // Check if the request was successful and no content
        if ($response['status'] === 204) {
            // Video deleted successfully
            return true;
        } else {
            // Handle errors
            $errorMessage = $response['body']['error'];
            // Handle error
            return false;
        }
    }
}
