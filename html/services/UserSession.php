<?php

class UserSession {

    // arbitrary session name
    private const SESSION_NAME = "account";

    /**
     * disconnect the user
     */
    public static function disconnect() {
        session_start();
        session_destroy();
    }

    /**
     * get the current user session
     * @return array|null the user session, null otherwise
     */
    public static function get() {
        return $_SESSION[self::SESSION_NAME] ?? null;
    }

    /**
     * update the user's session
     */
    public static function updateSession($data) {
        session_start();
        $_SESSION[self::SESSION_NAME] = $data;
    }

    /**
     * verify if the user is connected or not
     * @return boolean if the user is connected or not
     */
    public static function isConnected() {
        return isset($_SESSION[self::SESSION_NAME]);
    }

}