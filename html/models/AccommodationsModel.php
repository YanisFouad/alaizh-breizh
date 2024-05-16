<?php

require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");

class AccommodationsModel extends Model {

    private static $TABLE_NAME = "logements"; 

    public function __construct($data = null, $isNew = true) {
        parent::__construct(self::$TABLE_NAME, array(
            // @todo faire le model ici
        ), $data, $isNew);
    }

    public static function find($offset = 0, $limit = 10, $projection = "*", $orderField="date_arrivee", $orderDir="ASC") {
        if(is_array($projection))
            $projection = join(",", $projection);
        $orderByExpr = "";
        if($orderField != null && $orderDir != null)
            $orderByExpr = "ORDER BY " . $orderField . " " . $orderDir;

        $request = Database::getConnection()->prepare("SELECT " . $projection . " FROM " . self::$TABLE_NAME . $orderByExpr . " LIMIT " . $limit . " OFFSET " . $offset);
        $request->execute();
        $rows = $request->fetch(PDO::FETCH_ASSOC);
        return array_map(function ($row) {
            return new self($row, false);
        }, $rows);
    }

    public static function findOneById($id, $projection = "*") {
        if(is_array($projection))
            $projection = join(",", $projection);

        $request = Database::getConnection()->prepare("SELECT " . $projection . " FROM ".self::$TABLE_NAME." WHERE id_logement = ?");
        $request->bindParam(1, $id);
        $request->execute();
        
        $row = $request->fetch(PDO::FETCH_ASSOC);
        if(sizeof($row) < 1)
            return null;
        return new self($row, false);
    }

}