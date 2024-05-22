<?php

require_once(__DIR__."/BaseFile.php");

class FileLogement extends BaseFile {
    protected static function getPath() {
        return "files/logements/";
    }
}