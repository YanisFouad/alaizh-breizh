<?php

require_once(__DIR__."../../../../models/AccommodationModel.php");

if(isset($_POST['logementActuel']) && isset($_POST["estVisible"])){
    $logementId = $_POST['logementActuel'];
    $estVisibleActuel = boolval($_POST["estVisible"]);
    $nouveauEstVisible = !$estVisibleActuel;

    $logement = AccommodationModel::findOneById($logementId);
    $logement->set("est_visible", $nouveauEstVisible);
    $logement->save();   
    //var_dump($logementId, $nouveauEstVisible);

    // requete sql pour modifier 
    

    header("Location: /backoffice/logement?id_logement=".$logementId);
    exit;
}