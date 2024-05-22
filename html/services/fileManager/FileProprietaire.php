<?php

require_once(__DIR__."/BaseFile.php");

class FileProprietaire extends BaseFile {
    protected static function getPath() {
        return "files/proprietaires/";
    }
}