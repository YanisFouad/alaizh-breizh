<?php

abstract class BaseFile {

    abstract protected static function getPath();

    private static function formatFilename($fileName) {
        return "/" . static::getPath() . $fileName;
    }

    public static function get($fileName) {
        $path = __DIR__. "/../../" . static::getPath();
        $result = glob($path."/".$fileName."*");
        if(count($result) < 1)
            return self::formatFilename("default.webp");
        $file = $result[0];
        $file = basename($file);
        return self::formatFilename($file);
    }

    public static function delete($fileName) {
        $file = self::get($fileName);
        $file && unlink($file);
        return !!$file;
    }
    public static function save($fileName, $sourceFile) {
        $target_dir = static::getPath();
        $imageFileType = strtolower(pathinfo($sourceFile['name'], PATHINFO_EXTENSION));
        $target_file = $target_dir . $sourceFile . "." . $imageFileType;
        $uploadOk = 1;

        $check = getimagesize($sourceFile["tmp_name"]);
        if($check) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            $uploadOk = 0;
        }

        if ($sourceFile["size"] > 1000000) {
            $uploadOk = 0;
        }

        if(!in_array($imageFileType, ["jpg", "png", "jpeg"])) {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            return false;
        }
        return move_uploaded_file($sourceFile["tmp_name"], $target_file);
    }


}