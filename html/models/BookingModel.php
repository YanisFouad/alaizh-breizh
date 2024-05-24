<?php
require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");
require_once(__DIR__."/../services/RequestBuilder.php");
require_once(__DIR__."/../services/fileManager/FileLogement.php");

class BookingModel extends Model {

    private static $TABLE_NAME = "_reservation";

    public function __construct($data = null, $isNew = true) {
        parent::__construct(self::$TABLE_NAME, array(
            "id_reservation" => array(),
            "id_locataire" => array(),
            "id_logement" => array(),
            "photo_logement" => array(
                "get" => array($this, "computeAccomodationPicture")
            ),
            "titre_logement" => array(),
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

    public function computeAccomodationPicture($data) {
        return FileLogement::get($data["photo_logement"]);
    }

    public static function find($owner_id, $period,$offset = 0, $limit = 10) {
        $date_du_jour = new DateTime();
        $date_du_jour = $date_du_jour->format('Y-m-d');
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("*")
            ->limit($limit)
            ->where("id_proprietaire = ?", $owner_id);
        //réservation à venir
        if($period == "a_venir"){
            $result = $result->where("date_arrivee > ?", $date_du_jour);
        }
        //réservation en cours
        elseif($period == "en_cours"){
            $result = $result->where("date_depart >= ? AND date_arrivee <= ?", $date_du_jour, $date_du_jour);
        }
        //réservation passée
        else{
            $result = $result->where("date_depart < ?", $date_du_jour);
        }
        $result = $result
            ->innerJoin("pls.logement", "pls.logement.id_logement = pls._reservation.id_logement")
            ->innerJoin("pls.proprietaire", "pls.proprietaire.id_compte = pls.logement.id_proprietaire")
            ->offset($offset)
            ->execute()
            ->fetchMany();
        return array_map(function($row) {
            return new self($row, false);
        }, $result);
    }

    public static function findAll($owner_id, $period) {
        $date_du_jour = new DateTime();
        $date_du_jour = $date_du_jour->format('Y-m-d');
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("*")
            ->where("id_proprietaire = ?", $owner_id);
        //réservation à venir
        if($period == "a_venir"){
            $result = $result->where("date_arrivee > ?", $date_du_jour);
        }
        //réservation en cours
        elseif($period == "en_cours"){
            $result = $result->where("date_depart >= ? AND date_arrivee <= ?", $date_du_jour, $date_du_jour);
        }
        //réservation passée
        else{
            $result = $result->where("date_depart < ?", $date_du_jour);
        }
        $result = $result
            ->innerJoin("pls.logement", "pls.logement.id_logement = pls._reservation.id_logement")
            ->innerJoin("pls.proprietaire", "pls.proprietaire.id_compte = pls.logement.id_proprietaire")
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
            ->innerJoin("logement", "logement.id_logement = _reservation.id_logement")
            ->innerJoin("proprietaire", "proprietaire.id_compte = logement.id_proprietaire")
            ->execute()
            ->fetchOne();
        if($result == null)
            return $result;
        return new self($result, false);
    }

    public static function countByPeriod($period, $owner_id) {
        $date_du_jour = new DateTime();
        $date_du_jour = $date_du_jour->format('Y-m-d');
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("count(*)")
            ->innerJoin("pls.logement", "pls.logement.id_logement = pls._reservation.id_logement")
            ->innerJoin("pls.proprietaire", "pls.proprietaire.id_compte = pls.logement.id_proprietaire")
            ->where("id_proprietaire = ?", $owner_id);

        //réservation à venir
        if($period == "a_venir"){
            $result = $result->where("date_arrivee > ?", $date_du_jour);
        }
        //réservation en cours
        elseif($period == "en_cours"){
            $result = $result->where("date_depart >= ? AND date_arrivee <= ?", $date_du_jour, $date_du_jour);
        }
        //réservation passée
        else{
            $result = $result->where("date_depart < ?", $date_du_jour);
        }
        $result = $result
            ->execute()
            ->fetchOne();
        return $result["count"] ?? 0;
    }
}