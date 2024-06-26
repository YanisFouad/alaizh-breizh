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
    $id_logement = $_POST["id_logement"];
    unset($_POST["id_logement"]);

    $categorie = "";
    if(isset($_POST["categorie_logement"]))
        $categorie = strtolower($_POST["categorie_logement"]);
    $_POST["categorie_logement"] = $categorie;

    $fields = [];
    // prevent from XSS
    foreach($_POST as $k => $v)
        if($v)
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

    $accommodation = AccommodationModel::findOneById($id_logement);
    $insertedFields = [];

    // map all activities and layouts
    $activitiesCount = 1;
    $layoutsCount = 1;
    foreach($_POST as $k => $v) {
        if(preg_match("/^activity_/", $k)) {
            $v = preg_replace("/^activity_/", "", $k);
            $k = "activite_".$activitiesCount;
            $v = strtolower($v);
        } else if(preg_match("/^distance_for_/", $k)) {
            $k = "perimetre_activite_".$activitiesCount;
            $activitiesCount++;
        } else if(preg_match("/^layout_/", $k)) {
            $v = preg_replace("/^layout_/", "", $k);
            $k = "amenagement_".$layoutsCount;
            $layoutsCount++;
            $v = strtolower($v);
        }
        $insertedFields[$k] = $v;
    }

    $insertedFields["id_proprietaire"] = UserSession::get()->get("id_compte");
    foreach($insertedFields as $field => $value){
        $accommodation->set($field, $value);
    }
    
    try {
        // echo '<pre>';
        // var_dump($accommodation);
        // echo '</pre>';
        // die;
        $accommodation->save();
        if(trim($picture["name"]) != "") {
            $pictureName = $id_logement . "_" . $insertedFields["categorie_logement"];
            FileLogement::save($picture, $pictureName);
        }
        sendResponse(["success" => true]);
    } catch(Exception $e) {
        sendResponse(["error" => "Erreur lors de sauvegarde: " . $e->getMessage()]);
    }
}
