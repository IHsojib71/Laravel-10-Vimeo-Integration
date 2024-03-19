<?php
namespace App\Services;

use Vimeo\Laravel\Facades\Vimeo;

class VimeoService
{
//    my custom reusable static functions for vimeo operations
    public static function findFolder(string $folderName)
    {
        $folderId = null;
        // Make a request to get the list of user's folders
        $response = Vimeo::request('/me/projects', ['per_page' => 100], 'GET');
        // Iterate through the response to find the desired folder by name
        $folders = $response['body']['data'];
        if(count($folders))
            foreach ($folders as $folder) {
                if ($folder['name'] === $folderName) {
                    $folderId = $folder['uri'];
                    break;
                }
            }
        return basename($folderId);
    }

    public static function createFolder(string $folderName)
    {
        $response = Vimeo::request('/me/projects', ['name' => $folderName], 'POST');
        $folderId = $response['body']['uri'];
        return basename($folderId);
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
    public static function uploadVideoToFolder(string $videoTitle, string $videoPath, string $folderID, string $privacyName)
    {
        $vimeoVideoLink = Vimeo::upload($videoPath, [
            'name' =>  $videoTitle,
            'privacy' => ['view' => $privacyName],
            'folder_id' => $folderID,
        ]);
        return  explode('/videos/', $vimeoVideoLink)[1];
    }

    public static function uploadVideoToRoot(string $videoTitle, string $videoPath, string $privacyName)
    {
        $vimeoVideoLink = Vimeo::upload($videoPath, [
            'name' =>  $videoTitle,
            'privacy' => ['view' => $privacyName],
        ]);
        return explode('/videos/', $vimeoVideoLink)[1];
    }

    public static function deleteFolderByID(string $folderID)
    {
        // Make a DELETE request to delete the folder
        $response = Vimeo::request("/me/projects/{$folderID}", [], 'DELETE');

        // Check if the request was successful
        if ($response['status'] === 204) {
            // Folder deleted successfully
            return true;
        } else {
            // Handle errors
            $errorMessage = $response['body']['error'];
            // Handle error
            return false;
        }
    }

    public static function deleteFolderVideos(string $folderID)
    {
        // 1. Retrieve videos in the folder
        $response = Vimeo::request("/me/projects/{$folderID}/videos", ['per_page' => 100], 'GET');
        // Check if the request was successful
        if ($response['status'] === 200) {
            $videos = $response['body']['data'];
            // 2. Delete each video
            foreach ($videos as $video) {
                $videoId = substr($video['uri'], strrpos($video['uri'], '/') + 1);
                self::deleteSpecificVideo($videoId); // method to delete a video
            }
        }
    }
}
