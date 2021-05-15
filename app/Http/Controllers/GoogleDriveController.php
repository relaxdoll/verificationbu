<?php

namespace App\Http\Controllers;

use App\Classes\Drive;
use App\GoogleDrive;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{

    protected $client;
    /**
     * Drive constructor.
     */

    protected $root_folder = '0AL9HODku-y8ZUk9PVA';

    protected $refuel = '1NV85B0ILuAH_G5rWQyswNKLcP0PLzYBi';

    public function __construct()
    {
        $this->client = new \Google_Client();
        $this->client->setClientId(getenv('GOOGLE_DRIVE_CLIENT_ID'));
        $this->client->setClientSecret(getenv('GOOGLE_DRIVE_CLIENT_SECRET'));
        $this->client->refreshToken(getenv('GOOGLE_DRIVE_REFRESH_TOKEN'));
    }

    public function test()
    {
        $drive = new Drive();
    }

    public function uploadImage($image, $name, $parentId)
    {

        $folder = new \Google_Service_Drive_DriveFile();

        $folder->setParents([GoogleDrive::find($parentId)->folder_id]);
        $folder->setName($name);

        $media = [
            'data'              => file_get_contents($image),
            'mimeType'          => 'application/octet-stream',
            'uploadType'        => 'media',
            'supportsAllDrives' => true
        ];

        $service = new \Google_Service_Drive($this->client);

        $file_id = $service->files->create($folder, $media)->getId();

        $permission = new \Google_Service_Drive_Permission();
        $permission->setRole('reader');
        $permission->setType('anyone');

        $response = $service->permissions->create($file_id, $permission, ['supportsAllDrives' => true]);

        return ['file_id' => $file_id, 'link' => 'https://drive.google.com/uc?export=view&id=' . $file_id];

    }

    public function createFolder($parent, $name)
    {

        $folder = new \Google_Service_Drive_DriveFile();

        $folder->setParents([$parent]);
        $folder->setName($name);
        $folder->setMimeType("application/vnd.google-apps.folder");

        $service = new \Google_Service_Drive($this->client);

        $response = $service->files->create($folder, ['supportsAllDrives' => true]);

        return $response->getId();
    }

    public function getParentId($file_id)
    {

        $service = new \Google_Service_Drive($this->client);

        $file = $service->files->get($file_id, ['supportsAllDrives' => true, 'fields' => 'parents']);

        return $file->getParents();

    }

    public function changeParent($file_id, $old, $new)
    {

        $file = new \Google_Service_Drive_DriveFile();


        $service = new \Google_Service_Drive($this->client);

        $file = $service->files->update($file_id, $file, ['supportsAllDrives' => true, 'removeParents' => $old, 'addParents' => $new, 'fields' => 'id, parents']);

        return $file;
    }
}
