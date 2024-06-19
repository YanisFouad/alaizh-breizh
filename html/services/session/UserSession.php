<?php

require_once(__DIR__."/../../models/AccountModel.php");
require_once(__DIR__."/BaseSession.php");

class UserSession extends BaseSession {

    public static function getName() {
        return "account";
    }

    public static function disconnect() {
        self::destroy();
    }

    public static function isConnected() {
        // connected to court
        return self::isDefined();
    }

    public static function isConnectedAsTenant() {
        // connected as tenant (locataire en FR)
        return self::isConnected() && self::get()->get("accountType") === AccountType::TENANT->name;
    }

    public static function isConnectedAsOwner() {
        // connected as owner
        return self::isConnected() && self::get()->get("accountType") === AccountType::OWNER->name;
    }

}