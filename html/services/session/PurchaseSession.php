<?php

require_once(__DIR__."BaseSession.php");

class PurchaseSession extends BaseSession {

    public static function getName() {
        return "purchase";
    }

}