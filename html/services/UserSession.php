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
     * @return AccountModel|null the user session, null otherwise
     */
    public static function get() {
        $session = $_SESSION[self::SESSION_NAME] ?? null;
        if($session == null)
            return null;
        return unserialize($session);
    }

    /**
     * update the user's session
     */
    public static function updateSession($accountModel) {
        session_start();
        $_SESSION[self::SESSION_NAME] = serialize($accountModel);
    }

    /**
     * verify if the user is connected or not
     * @return boolean if the user is connected or not
     */
    public static function isConnected() {
        return isset($_SESSION[self::SESSION_NAME]);
    }

}