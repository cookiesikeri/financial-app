<?php


namespace App\Traits;


use Illuminate\Support\Facades\Storage;

trait ManagesUploads
{

    /**
     * upload file to aws
     * @param $file
     * @param $folder
     * @return mixed
     */
    public function uploadAwsFile($file, $folder) {
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid().'.'.$extension;

        $path = $file->storePubliclyAs($folder, $filename, 's3');
        if ($path) {
            return Storage::disk('s3')->url($path);
        }
        return null;
    }

    /**
     * delete file from aws
     * @param $full_aws_path
     * @return bool
     */
    public function deleteFromAws($full_aws_path) {
        if(strpos($full_aws_path, 'amazonaws.com')) {
            Storage::disk('s3')->delete($this->filePath($full_aws_path));
            return true;
        }
        return false;
    }

    /**
     * @param $url
     * @return false|string
     */
    private function filePath($url)
    {
        $withCom = strstr($url, '.com/');
        return substr($withCom, 5);
    }

}
