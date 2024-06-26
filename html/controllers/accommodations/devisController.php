<?php

require_once(__DIR__."/../../models/BookingModel.php");
require_once(__DIR__."/../../services/session/UserSession.php");
require_once(__DIR__."/../../services/session/PurchaseSession.php");


if(isset($_POST)) {
    $idCompte = UserSession::get()->get("id_compte");
    PurchaseSession::set([
        "accommodationId" => $_POST["id_logement"],
        "nb_voyageurs" => $_POST["nb_voyageurs"],
        "total_ati" => $_POST["prix_total"]
    ]);

    //BookingModel::set(id_compte, $idCompte);
    //BookingModel::set("est_payee", false);
    foreach($_POST as $key => $value){
        echo "key : ".$key;
        echo "value : " .$value;
        //BookingModel::set($key, $value);
    }

    //BookingModel::save();
    header("Content-type: application/json");
    return json_encode(["success" => true]);
}