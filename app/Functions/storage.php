<?php

namespace App\Functions;

class storage
{
    public static function UploadToPath($tempName, $fileName)
    {
        try {
            $destination = env('UPLOAD_PATH') . '/' . $fileName;
            move_uploaded_file($fileName, $fileName);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
}