<?php

require_once(__DIR__."../../../../models/AccommodationModel.php");

if(isset($_POST['idLogement'])){
    $logementId = $_POST['idLogement'];
    $logement = AccommodationModel::findOneById($logementId);
    $estVisibleActuel = $logement->get("est_visible");
    echo "valeur est visible actuel: ".$estVisibleActuel;
    $nouveauEstVisible = !$estVisibleActuel;

    $logement->set("est_visible", $nouveauEstVisible ? "true" : "false");
    $logement->save(); 
    

    header("Content-Type: application/json");
    echo json_encode([
        "success" => true
    ]);
    exit;
}