<?php

class ScriptLoader {

    private static $scripts = [];

    public static function load($path) {
        if(str_starts_with("/", $path))
            $path = substr($path, 0, 1);
        self::$scripts[] = "/scripts/".$path;
    }

    /**
     * Load & render the script directly
     */
    public static function loadAndRender($path) {
        self::load($path);
        self::render();
    }

    public static function render() {
        foreach(self::$scripts as $script) {
            echo '<script type="text/javascript" src="'.$script.'"></script>';
        }
    }

}