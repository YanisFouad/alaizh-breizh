<?php

require_once(__DIR__."../../../../models/AccommodationModel.php");

if(isset($_POST['idLogement'])){
    $logementId = $_POST['idLogement'];
    $logement = AccommodationModel::findOneById($logementId);
    $estVisibleActuel = $logement->get("est_visble");
    $nouveauEstVisible = !$estVisibleActuel;

    echo $nouveauEstVisible;
    $logement->set("est_visible", $nouveauEstVisible ? "true" : "false");
    $logement->save(); 
    

    header("Location: /backoffice/logement?id_logement=".$logementId);
    exit;
}