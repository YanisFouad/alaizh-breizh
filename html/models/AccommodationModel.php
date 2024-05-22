<?php

require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");
require_once(__DIR__."/../services/RequestBuilder.php");
require_once(__DIR__."/../services/fileManager/FileLogement.php");

class AccommodationModel extends Model {

    private static $TABLE_NAME = "pls.logement"; 

    public function __construct($data = null, $isNew = true) {
        parent::__construct(self::$TABLE_NAME, array(
            "id_logement" => array("primary" => true),
            "id_proprietaire" => array("required" => true),
            "id_adresse" => array("required" => true),
            "titre_logement" => array("required" => true),
            "photo_logement" => array(
                "get" => array($this, "computeAccomodationPicture")
            ),
            "accroche_logement" => array(),
            "description_logement" => array(),
            "gps_longitude_logement" => array(),
            "gps_latitude_logement" => array(),
            "categorie_logement" => array(),
            "surface_logement" => array(),
            "max_personne_logement" => array(),
            "nb_lits_simples_logement" => array(),
            "nb_lits_doubles_logement" => array(),
            "prix_ht_logement" => array(),
            "prix_ttc_logement" => array(),
            "est_visible" => array(),
            "duree_minimale_reservation" => array(),
            "delais_minimum_reservation" => array(),
            "delais_prevenance" => array(),
            "classe_energetique" => array(),
            "type_logement" => array(),
            "numero" => array(),
            "complement_numero" => array(),
            "rue_adresse" => array(),
            "complement_adresse" => array(),
            "ville_adresse" => array(),
            "code_postal_adresse" => array(),
            "pays_adresse" => array(),
            "activites" => array(
                "get" => array($this, "computeActivities")
            ),
            "amenagements" => array(
                "get" => array($this, "computeAmenagements")
            )
        ), $data, $isNew);
    }

    public function computeActivities($data) {
        $activites = [];
        $i = 1;
        do {
            $activites[] = array(
              "name" => $data["activite_".$i],
              "id" => $data["id_activite_".$i],
              "perimetre" => $data["perimetre_activite_".$i]
            );
            $i++;
        } while($data["activite_".$i] !== null);
        return $activites;
    }

    public function computeAmenagements($data) {
        $amenagements = [];
        $i = 1;
        do {
            $amenagements[] = array(
              "name" => $data["amenagement_".$i],
              "id" => $data["id_amenagement_".$i],
            );
            $i++;
        } while($data["amenagement_".$i] !== null);
        return $amenagements;
    }

<<<<<<< HEAD
    public function computeAccomodationPicture($model) {
        return FileLogement::get(
            $model->get("id_logement"),
            $model->get("categorie_logement")
        );
=======
    public function computeAccomodationPicture($data) {
        return FileLogement::get($data["photo_logement"]);
>>>>>>> develop
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
            ->where("id_logement = ?", $id)
            ->innerJoin("(select id_compte, nom, prenom from pls.proprietaire) p", "pls.logement.id_proprietaire = p.id_compte")
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
            ->where("id_proprietaire = ?", $id)
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
            ->where("id_proprietaire = ?", $id)
            ->execute()
            ->fetchMany();

        return $result;
    }
}