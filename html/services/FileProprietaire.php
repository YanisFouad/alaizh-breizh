<?php

class FileProprietaire {

    private const FILE_PATH = "/files/proprietaires/";
    private const DEFAULT_PROPRIETAIRE_PATH = "/files/proprietaires/default.webp";

    public static function save($prorietaireId, $file) {
        $target_dir = self::FILE_PATH;
        $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $target_file = $target_dir . $prorietaireId . $imageFileType;
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

    public static function delete($prorietaireId) {
        $target_file = self::FILE_PATH . $prorietaireId . "*";
        $target_file = empty($target_file) ? false : $target_file[0];
        
        if (glob($target_file)) {
            unlink(glob($target_file)[0]);
            return true;
        } else {
            return false;
        }
    }

    public static function get($prorietaireId) {
        $target_file = glob(self::FILE_PATH . $prorietaireId . "*")[0];
        $target_file = empty($target_file) ? false : $target_file[0];
        if ($target_file) {
            return $target_file;
        } else {
            return self::DEFAULT_PROPRIETAIRE_PATH;
        }
    }
}