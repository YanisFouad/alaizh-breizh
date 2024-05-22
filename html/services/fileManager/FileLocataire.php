<?php

require_once(__DIR__."/BaseFile.php");

class FileLocataire extends BaseFile {
    protected static function getPath() {
        return "files/locataires/";
    }
}