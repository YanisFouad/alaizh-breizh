<?php

require_once(__DIR__."/../../models/AccountModel.php");
require_once(__DIR__."/BaseSession.php");

class UserSession extends BaseSession {

    public static function getName() {
        return "account";
    }

    public static function disonnect() {
        self::destroy();
    }

    public static function isConnected() {
        return self::isDefined();
    }

}