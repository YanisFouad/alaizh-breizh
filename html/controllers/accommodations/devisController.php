<?php

require_once(__DIR__."/../../models/BookingModel.php");
require_once(__DIR__."/../../services/session/UserSession.php");
require_once(__DIR__."/../../services/session/PurchaseSession.php");


if(isset($_POST)) {
    $idCompte = UserSession::get()->get("id_compte");
    $purcharseSession = new PurchaseSession();
    $purcharseSession->set([
        "accommodationId" => $_POST["id_logement"],
        "nb_voyageurs" => $_POST["nb_voyageur"],
        "total_ati" => $_POST["prix_total"],
        "date_arrivee" => $_POST["date_arriveeNF"],
        "date_depart" => $_POST["date_departNF"]
    ]);

    $ajoutReservation = new BookingModel();
    $ajoutReservation->set("id_locataire", $idCompte);
    foreach($_POST as $key => $value){
        if($key != "date_arriveeNF" && $key != "date_departNF" && $key != "prix_totalF"){
            $ajoutReservation->set($key, $value);
        }
    }
    $ajoutReservation->set("date_reservation", date("Y-m-d"));
    $ajoutReservation->set("est_payee", 0);
    var_dump($ajoutReservation);
    $ajoutReservation->save();
    header("Content-type: application/json");
    return json_encode(["success" => true]);
}