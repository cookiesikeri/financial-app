<?php

namespace App\Traits;

Use Image;
/**
 *
 */
trait UploadImage
{
    public function upload($image, String $stoarge_path, float $length = 0, float $height = 0) : String
    {
        $original_name = $image->getClientOriginalName();
        $new_name = md5(microtime() . $original_name) . '.' . 'png';
        $intervention_image = Image::make($image->getRealPath());

        if($length > 5 && $height > 5) {
            $intervention_image->fit(200, 200);
        }

        try {
            $intervention_image->save($stoarge_path . $new_name, 60);
            return $new_name;
        } catch (\Throwable $th) {
        //     throw $th;
        }

        return '';

    }
}
