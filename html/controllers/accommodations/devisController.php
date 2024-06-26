<?php

require_once(__DIR__."/../../models/BookingModel.php");
require_once(__DIR__."/../../services/session/UserSession.php");

if(isset($_POST)) {
    $idCompte = UserSession::get()->get("id_compte");
    PurchaseSession::set([
        "accommodationId" => $_POST["accommodationId"],
        "nb_voyageurs" => $_POST["nb_voyageurs"],
        "total_ati" => $_POST["total_ati"]
    ]);

    foreach($_POST as $key => $value){
        echo "key : ".$key;
        echo "value : " .$value;
        //BookingModel::set($key, $value);
    }
    header("Content-type: application/json");
    return json_encode(["success" => true]);
}