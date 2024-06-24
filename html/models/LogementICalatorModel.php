<?php

require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");
require_once(__DIR__."/../services/RequestBuilder.php");
require_once(__DIR__."/../services/fileManager/FileLogement.php");

class LogementICalatorModel extends Model {

    private static $TABLE_NAME = "_icalator_logement"; 

    public function __construct($data = null, $isNew = true) {
        parent::__construct(self::$TABLE_NAME, array(
            "cle_api" => array("primary" => true),
            "id_logement" => array("required" => true),
        ), $data, $isNew);
    }

    public static function find($offset = 0, $limit = 10, $projection = "*") {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("*")
            ->limit($limit)
            ->offset($offset)
            ->execute()
            ->fetchMany();
        return array_map(function($row) {
            return new self($row, false);
        }, $result);
    }
    public static function count() {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("count(*)")
            ->execute()
            ->fetchOne();
        return $result["count"] ?? 0;
    }

    public static function findOneById($id, $projection = "*") {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("*")
            ->where("cle_api = ?", $id)
            ->execute()
            ->fetchOne();
        if($result == null)
            return null;
        return new self($result, false);
    }

    public static function findById($id, $offset = 0, $limit = 10, $projection = "*") {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("*")
            ->limit($limit)
            ->where("cle_api = ?", $id)
            ->offset($offset)
            ->execute()
            ->fetchMany();

        return array_map(function($row) {
            return new self($row, false);
        }, $result);
    }

    public static function findAllById($id, $projection = "*") {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection($projection)
            ->where("cle_api = ?", $id)
            ->execute()
            ->fetchMany();

        return $result;
    }
}