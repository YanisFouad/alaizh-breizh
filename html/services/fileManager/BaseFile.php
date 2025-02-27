<?php

abstract class BaseFile {

    abstract protected static function getPath();

    private static function formatFilename($fileName) {
        return "/" . static::getPath() . $fileName;
    }

    private static function computedDir() {
        return __DIR__. "/../../" . static::getPath();
    }

    public static function get($fileName) {
        $result = glob(self::computedDir()."/".$fileName."*");
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

    public static function save($sourceFile, $name) {
        $imageFileType = strtolower(pathinfo($sourceFile["name"], PATHINFO_EXTENSION));
        $target_file = self::computedDir() . $name . "." . $imageFileType;
        $uploadOk = 1;

        $check = getimagesize($sourceFile["tmp_name"]);
        if($check) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        if ($sourceFile["size"] > 1000000) {
            $uploadOk = 0;

        }

        if(!in_array($imageFileType, ["jpg", "png", "jpeg", "webp"])) {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            return false;
        }

        //suppression du fichier si il existe déjà
        delete_if_exist($name);

        $res = move_uploaded_file($sourceFile["tmp_name"], $target_file);
        if(!$res)
            return false;
        return $target_file;
    }
}

function file_exist_without_extension($fileName) {
    // Utilise glob pour trouver des fichiers correspondant au motif dans le répertoire
    $files = glob(__DIR__.'/../../files/logements/' . $fileName . '.*');
    // Vérifie s'il y a exactement un fichier trouvé
    if (count($files) === 1 && file_exists($files[0])) {
        // Retourne le nom du fichier trouvé
        return $files[0];
    }
}

//supression du fichier si il existe
function delete_if_exist($fileName){
    $file = file_exist_without_extension($fileName);
    $file && unlink($file);
}