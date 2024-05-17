<?php

require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");
require_once(__DIR__."/../services/RequestBuilder.php");

class AccommodationsModel extends Model {

    private static $TABLE_NAME = "logements"; 

    public function __construct($data = null, $isNew = true) {
        parent::__construct(self::$TABLE_NAME, array(
            // @todo faire le model ici
        ), $data, $isNew);
    }

    public static function find($offset = 0, $limit = 10, $projection = "*", $orderField="date_arrivee", $orderDir="ASC") {
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

    public static function findOneById($id, $projection = "*") {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("*")
            ->where("id_logement = ?", $id)
            ->execute()
            ->fetchOne();
        return new self($result, false);
    }

}