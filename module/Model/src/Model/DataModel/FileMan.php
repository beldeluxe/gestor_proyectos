<?php

namespace Model\DataModel;

class FileMan
{
    const APP_PUBLIC_DIR   = 'public';

    const FILE_TYPE_IMG = 'IMG';
    const FILE_TYPE_VID = 'VID';
    const FILE_TYPE_DOC = 'DOC';
    const FILE_TYPE_URL = 'URL';

    public static function getUploadsDir( $subdir, $isAdmin, $getOnlyRelativePath = false )
    {
        
        $uploadsPath  = "/uploads";
        $uploadsPath .= ($isAdmin)? "/admins" : "/users";
        $dirPath      = (empty($subdir))? $uploadsPath : ( $uploadsPath . "/" . $subdir );

        if ($getOnlyRelativePath) {
            return $dirPath;
        }

        $uploadsPath  = FileMan::APP_PUBLIC_DIR . $uploadsPath;
        if (!is_dir($uploadsPath)) {
            mkdir($uploadsPath,0775);
        }

        $dirPath      = FileMan::APP_PUBLIC_DIR . $dirPath;
        if (!is_dir($dirPath)) {
            mkdir($dirPath,0775);
        }

        return $dirPath;
    }

    public static function getTmpDir( $subdir = '' )
    {
        $path = FileMan::APP_PUBLIC_DIR . "/tmp";

        if (!empty($subdir)) {
            $path .= "/$subdir";
            if (!is_dir($path)) {
                mkdir($path,0775);
            }
        }

        return $path;
    }

    public static function allowedMimeTypes($forCtype = '')
    {
        return array('image/jpeg','image/png','video/quicktime','video/avi','video/mpeg','video/mp4','application/pdf');

    }

    public static function ctypeForMimeType( $mimeType )
    {
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/png':
                return FileMan::FILE_TYPE_IMG;
            case 'video/avi':
            case 'video/quicktime':
            case 'video/mpeg':
            case 'video/mp4':
                return FileMan::FILE_TYPE_VID;
            default:
                return FileMan::FILE_TYPE_DOC;
        }
    }

    public static function uplodadMediaFile( $containerDir, $mediaFile, $isAdmin, &$publicDir, &$targetType )
    {
        $uploadFinish = false;
        $destinationDir = FileMan::getUploadsDir( $containerDir, $isAdmin );

        $nameAndExtension = explode(".",basename($mediaFile["name"]));
        $targetFileName = $nameAndExtension[0]."_".rand ( 1 , 1000000 ).".".$nameAndExtension[1];

        $fileName = basename($mediaFile["name"]);
        $targetFile = $destinationDir . "/" . $targetFileName;
        $targetType = FileMan::ctypeForMimeType($mediaFile["type"]);

        $uploadOk = filesize($mediaFile["tmp_name"]) > 0;
        $uploadOk = $uploadOk && ($mediaFile["size"] < 6000000);

        $mimeContentType = mime_content_type($mediaFile["tmp_name"]);
        $uploadOk = $uploadOk && in_array($mimeContentType, FileMan::allowedMimeTypes($targetType) );

        if ($uploadOk) {
            $uploadFinish = @move_uploaded_file($mediaFile["tmp_name"], $targetFile);
            $publicDir = FileMan::getUploadsDir($containerDir, true, true). "/" . $targetFileName;
        }

        if ($uploadFinish) {
            return $publicDir;
        }else{
            return false;
        }

    }


    public static function deleteFile( $containerDir, $targetFile, $isAdmin )
    {
        $destinationDir = FileMan::getUploadsDir( $containerDir, $isAdmin );
        $targetFile = $destinationDir . "/" . basename($targetFile);
        @unlink($targetFile);
    }

    public static function deleteFilesInDir( $dir, $deleteDir = false )
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = glob( $dir . '/*' );
        foreach($files as $file) {
            if( is_file($file) ) @unlink($file); // delete file
        }

        if ($deleteDir) {
            @rmdir($dir);    
        }        
    }

    public static function deleteTmpFiles( $subdir = '' )
    {
        FileMan::deleteFilesInDir( FileMan::getTmpDir( $subdir ), !empty($subdir) );
    }

    public static function getFileNamesArr( $containerDir, $isAdmin )
    {
        $baseDir = FileMan::getUploadsDir( $containerDir, $isAdmin );

        return glob( $baseDir . '/*');
    }

}

