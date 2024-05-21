<?php

class FileLogement {

    private const FILE_PATH = "files/logements/";

    public static function save($logementId, $logementType, $file) {
        $target_dir = self::FILE_PATH;
        $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $target_file = $target_dir . $logementId . "_" . $logementType . ".$imageFileType";
        $uploadOk = 1;

        if(isset($_POST["submit"])) {
            $check = getimagesize($file["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        }

        if (file_exists($target_file)) {
            $uploadOk = 0;
        }

        if ($file["size"] > 1000000) {
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            return false;
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return true;
            } else {
                return "test";
            }
        }
    }

    public static function delete($logementId, $logementType) {
        $target_file = glob(self::FILE_PATH . $logementId . "_" . $logementType . "*")[0];
        $target_file = empty($target_file) ? false : $target_file[0];
        
        if ($target_file) {
            unlink($target_file);
            return true;
        } else {
            return false;
        }
    }

    public static function get($logementId, $logementType) {
        $target_file = glob(self::FILE_PATH . $logementId . "_" . $logementType . "*")[0];
        $target_file = empty($target_file) ? false : $target_file[0];
        
        if ($target_file) {
            return $target_file;
        } else {
            return false;
        }
    }
}