<?php

class ScriptLoader {

    private static $scripts = [];

    public static function load($path) {
        if(str_starts_with("/", $path))
            $path = substr($path, 0, 1);
        if(!str_ends_with(".js", $path))
            $path .= ".js";
        self::$scripts[] = "/scripts/".$path;
    }

    public static function render() {
        foreach(self::$scripts as $script) {
            echo '<script type="text/javascript" src="'.$script.'"></script>';
        }
    }

}