<?php

try {
    $db = new PDO("pgsql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"].";dbname=".$_ENV["DB_DATABASE"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
    // define $db as a global variable while is required in a file
    global $db;
} catch(Exception $e) {
    print_r("Failed to connect to the database: ".$e->getMessage());
}