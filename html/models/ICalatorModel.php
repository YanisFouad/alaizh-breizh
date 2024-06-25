<?php

require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");
require_once(__DIR__."/../services/RequestBuilder.php");
require_once(__DIR__."/../services/fileManager/FileLogement.php");

class ICalatorModel extends Model {

    private static $TABLE_NAME = "_icalator"; 

    public function __construct($data = null, $isNew = true) {
        parent::__construct(self::$TABLE_NAME, array(
            "cle_api" => array("primary" => true),
            "start_date" => array("required" => true),
            "end_date" => array("required" => true),
            "id_compte" => array("required" => true)
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
            ->where("id_logement = ?", $id)
            ->innerJoin("(select id_compte, nom, prenom from proprietaire) p", "logement.id_proprietaire = p.id_compte")
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
            ->where("id_compte = ?", $id)
            ->offset($offset)
            ->execute()
            ->fetchMany();

        return array_map(function($row) {
            return new self($row, false);
        }, $result);
    }

    public static function findByKey($key, $projection = "*") {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection($projection)
            ->where("cle_api = ?", $key)
            ->execute()
            ->fetchOne();

        if($result == null)
            return null;
        return new self($result, false);
    }

    public static function findAllById($id, $projection = "*") {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection($projection)
            ->where("id_proprietaire = ?", $id)
            ->execute()
            ->fetchMany();

        return $result;
    }
}