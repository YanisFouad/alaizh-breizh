<?php
require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");
require_once(__DIR__."/../services/RequestBuilder.php");

class BookingModel extends Model {

    private static $TABLE_NAME = "pls._reservation";

    public function __construct($data = null, $isNew = true) {
        parent::__construct(self::$TABLE_NAME, array(
            "id_reservation" => array(),
            "id_locataire" => array(),
            "id_logement" => array(),
            "nb_nuit" => array(),
            "date_arrivee" => array("type" => "date"),
            "date_depart" => array("type" => "date"),
            "nb_voyageur" => array(),
            "date_reservation" => array(),
            "frais_de_service" => array(),
            "prix_nuitee_ttc" => array(),
            "prix_total" => array(),
            "est_payee" => array(),
            "est_annulee" => array()
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

    public static function findOneById($id, $projection = "*") {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("*")
            ->where("id_reservation = ?", $id)
            ->execute()
            ->fetchOne();
        return new self($result, false);
    }

}