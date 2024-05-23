<?php

class Database {
    private static $db;
    private $connection;

    private function __construct() {
        try {
            $this->connection = new PDO("pgsql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"].";dbname=".$_ENV["DB_DATABASE"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
            $this->connection->exec("SET search_path TO pls");
        } catch(Exception $e) {
            print_r("Failed to connect to the database: ".$e->getMessage());
        }
    }

    function __destruct() {
        $this->connection = null;
    }

    public static function getConnection() {
        if (self::$db == null)
            self::$db = new Database();
        return self::$db->connection;
    }
}