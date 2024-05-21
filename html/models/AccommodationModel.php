<?php

require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");
require_once(__DIR__."/../services/RequestBuilder.php");
require_once(__DIR__."/../services/FileLogement.php");

class AccommodationModel extends Model {

    private static $TABLE_NAME = "pls.logement"; 

    public function __construct($data = null, $isNew = true) {
        parent::__construct(self::$TABLE_NAME, array(
            "id_logement" => array("primary" => true),
            "id_proprietaire" => array("required" => true),
            "id_adresse" => array("required" => true),
            "titre_logement " => array("required" => true),
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
            ),
            "activite_1" => array(),
            "activite_2" => array(),
            "activite_3" => array(),
            "activite_4" => array(),
            "activite_5" => array(),
            "activite_6" => array(),
            "activite_7" => array(),
            "perimetre_activite_1" => array(),
            "perimetre_activite_2" => array(),
            "perimetre_activite_3" => array(),
            "perimetre_activite_4" => array(),
            "perimetre_activite_5" => array(),
            "perimetre_activite_6" => array(),
            "perimetre_activite_7" => array(),
            "id_activite_1" => array(),
            "id_activite_2" => array(),
            "id_activite_3" => array(),
            "id_activite_4" => array(),
            "id_activite_5" => array(),
            "id_activite_6" => array(),
            "id_activite_7" => array(),
            "amenagement_1" => array(),
            "amenagement_2" => array(),
            "amenagement_3" => array(),
            "amenagement_4" => array(),
            "amenagement_5" => array(),
            "id_amenagement_1" => array(),
            "id_amenagement_2" => array(),
            "id_amenagement_3" => array(),
            "id_amenagement_4" => array(),
            "id_amenagement_5" => array()
        ), $data, $isNew);
    }

    public function computeActivities($model) {
        $activites = [];
        $i = 1;
        do {
            $activites[] = array(
              "name" => $model->get("activite_".$i),
              "id" => $model->get("id_activite_".$i),
              "perimetre" => $model->get("perimetre_activite_".$i)
            );
            $i++;
        } while($model->get("activite_".$i) !== null);
        return $activites;
    }

    public function computeAmenagements($model) {
        $amenagements = [];
        $i = 1;
        do {
            $amenagements[] = array(
              "name" => $model->get("amenagement_".$i),
              "id" => $model->get("id_amenagement_".$i),
            );
            $i++;
        } while($model->get("amenagement_".$i) !== null);
        return $amenagements;
    }

    public function computeAccomodationPicture($model) {
        return FileLogement::get(
            $model->get("id_logement"),
            $model->get("type_logement")
        );
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
            ->execute()
            ->fetchOne();
        return new self($result, false);
    }

}