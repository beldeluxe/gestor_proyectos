<?php

namespace Model\DataModel;

class FileManPrivate extends FileMan
{
    const APP_DATA_DIR   = 'data';

    public static function uplodadMediaFile( $containerDir, $mediaFile, $isAdmin, &$publicDir, &$targetType )
    {
        $uploadFinish = false;
        $destinationDir = FileManPrivate::getUploadsDir( $containerDir, $isAdmin );

        $nameAndExtension = explode(".",basename($mediaFile["name"]));
        $targetFileName = strtolower(Sanitizer::sanitizeInput(substr(str_replace(' ', '_' , $nameAndExtension[0]),0,15))."_".rand ( 1 , 1000000 )).".".strtolower($nameAndExtension[1]);
        //$targetFileName = Sanitizer::sanitizeInput(substr($nameAndExtension[0],0,15))."_".rand ( 1 , 1000000 ).".".$nameAndExtension[1];

        $fileName = basename($mediaFile["name"]);
        $targetFile = $destinationDir . "/" . $targetFileName;
        $targetType = FileMan::ctypeForMimeType($mediaFile["type"]);

        $uploadOk = filesize($mediaFile["tmp_name"]) > 0;
        $uploadOk = $uploadOk && ($mediaFile["size"] < 6000000);

        $mimeContentType = mime_content_type($mediaFile["tmp_name"]);
        $uploadOk = $uploadOk && in_array($mimeContentType, FileMan::allowedMimeTypes($targetType) );

        if ($uploadOk) {
            $uploadFinish = @move_uploaded_file($mediaFile["tmp_name"], $targetFile);
            $publicDir = FileManPrivate::getUploadsDir($containerDir, true, true). "/" . $targetFileName;
        }

        if ($uploadFinish) {
            return $publicDir;
        }else{
            return false;
        }

    }

    public static function getUploadsDir( $subdir, $isAdmin, $getOnlyRelativePath = false )
    {

       // $uploadsPath  = "/uploads";

        $uploadsPath  = "";
        $dirPath      = (empty($subdir))? $uploadsPath : ( $uploadsPath . "/" . $subdir );

        if ($getOnlyRelativePath) {
            return $dirPath;
        }

        $uploadsPath  = FileManPrivate::APP_DATA_DIR . $uploadsPath;
        if (!is_dir($uploadsPath)) {
            mkdir($uploadsPath,0775);
        }

        $dirPath      = FileManPrivate::APP_DATA_DIR . $dirPath;
        if (!is_dir($dirPath)) {
            mkdir($dirPath,0775);
        }

        return $dirPath;
    }


}

