<?php
namespace App\Services;

use Vimeo\Laravel\Facades\Vimeo;

class VimeoService
{
//    my custom reusable static functions for vimeo operations

    /**
     * Finding a folder by name and return folder id
     * @param string $folderName
     * @return string
     */
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

    /**
     * Creating a folder of given folder name and return folder id
     * @param string $folderName
     * @return string
     */
    public static function createFolder(string $folderName)
    {
        $response = Vimeo::request('/me/projects', ['name' => $folderName], 'POST');
        $folderId = $response['body']['uri'];
        return basename($folderId);
    }


    /**
     * Finding project by project name
     * @param string $projectName
     * @return mixed|void|null
     */
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


    /**
     * Creating sub folder under a folder
     * @param string $parentFolderID
     * @param string $subfolderName
     * @return mixed|null
     */
    public static function createSubfolder(string $parentFolderID, string $subfolderName)
    {
        // Make a POST request to create the subfolder
        $response = Vimeo::request("/me/projects/{$parentFolderID}/folders", [
            'name' => $subfolderName,
        ], 'POST');
        // Check if the request was successful
        if ($response['status'] === 201) {
            // Subfolder (project) created successfully
            return $response['body']['uri'];
        } else {
            // Handle errors
            $errorMessage = $response['body']['error'];
            // Handle error
            return $errorMessage;
        }
    }

    /**
     * Delete a specific video by video id
     * @param string $videoID
     * @return bool
     */
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

    /**
     * Uploading a video inside a folder
     * @param string $videoTitle
     * @param string $videoPath
     * @param string $folderID
     * @param string $privacyName
     * @return string
     */
    public static function uploadVideoToFolder(string $videoTitle, string $videoPath, string $folderID, string $privacyName)
    {
        $vimeoVideoLink = Vimeo::upload($videoPath, [
            'name' =>  $videoTitle,
            'privacy' => ['view' => $privacyName],
            'folder_id' => $folderID,
        ]);
        return  explode('/videos/', $vimeoVideoLink)[1];
    }

    /**
     * Upload a video to root
     * @param string $videoTitle
     * @param string $videoPath
     * @param string $privacyName
     * @return string
     */
    public static function uploadVideoToRoot(string $videoTitle, string $videoPath, string $privacyName)
    {
        $vimeoVideoLink = Vimeo::upload($videoPath, [
            'name' =>  $videoTitle,
            'privacy' => ['view' => $privacyName],
        ]);
        return explode('/videos/', $vimeoVideoLink)[1];
    }


    /**
     * Delete a folder by its ID
     * @param string $folderID
     * @return bool
     */
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

    /**
     * Delete all videos inside a folder
     * @param string $folderID
     * @return void
     */
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
