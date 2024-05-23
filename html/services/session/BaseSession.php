<?php

abstract class BaseSession {

    abstract static function getName();

    private static function startSession() {
        if(session_status() !== PHP_SESSION_ACTIVE)
            session_start();
    }
    public static function destroy() {
        self::startSession();
        session_destroy();
    }

    public static function get() {
        self::startSession();
        $session = $_SESSION[static::getName()] ?? null;
        if(!isset($session))
            return null;
        return unserialize($session);
    }

    public static function set($newData) {
        self::startSession();
        $_SESSION[static::getName()] = serialize($newData);
    }

    public static function isDefined() {
        return self::get() != null;
    }

}