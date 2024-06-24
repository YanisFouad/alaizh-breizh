<?php

require_once(__DIR__."/../../../models/AccommodationModel.php");
require_once(__DIR__."/../../../services/session/UserSession.php");

function sendResponse(...$entries) {
    header("Content-Type: application/json");
    echo json_encode(...$entries);
    exit;
}

if(isset($_POST)) {
    $picture = $_FILES["photo_logement"] ?? null;
    $optionnalFields = ["complement_adresse","nb_lits_doubles_logement","nb_lits_simples_logement"];

    $fields = [];
    // prevent from XSS
    foreach($_POST as $k => $v)
        $fields[$k] = htmlentities($v);

    // add all empty fields here
    $badFields = [];
    foreach($fields as $field => $value) {
        if(empty(trim($value)) && !in_array($field, $optionnalFields))
            $badFields[] = $field;
    }

    // if we have bad fields then print it
    if(count($badFields) > 0) {
        sendResponse([
            "error" => "Certains champs sont incomplets." . count($badFields), 
            "fields" => $badFields
        ]);
    }
    
    $accommodation = new AccommodationModel();
    $insertedFields = [];

    // map all activities and layouts
    $activitiesCount = 1;
    $layoutsCount = 1;
    foreach($_POST as $k => $v) {
        if(preg_match("/^activity_/", $k)) {
            $v = preg_replace("/^activity_/", "", $k);
            $k = "activite_".$activitiesCount;
        } else if(preg_match("/^distance_for_/", $k)) {
            $v = preg_replace("/^distance_for_/", "", $k);
            $k = "perimetre_activite_".$activitiesCount;
        } else if(preg_match("/^layout_/", $k)) {
            $v = preg_replace("/^layout_/", "", $k);
            $k = "amenagement_".$layoutsCount;
        }

        $insertedFields[$k] = $v;
    }

    $insertedFields["id_proprietaire"] = UserSession::get()->get("id_compte");
    foreach($insertedFields as $field => $value) 
        $accommodation->set($field, $value);

    try {
        if($picture != null) {
            $lastInsertedId = $accommodation->save("_logement_id_logement_seq");
            $pictureName = $lastInsertedId . "_" . $insertedFields["type_logement"];
            FileLogement::save($picture, $pictureName);
        }

        sendResponse(["success" => true]);
    } catch(Exception $e) {
        sendResponse(["error" => "Erreur lors de sauvegarde: " . $e->getMessage()]);
    }
}
