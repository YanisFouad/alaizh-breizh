<?php
include_once(__DIR__ . "/../../services/RequestBuilder.php");

const TABLE_NAME = "pls._reservation";

if (isset($_GET["id"]) && $_GET['id']) {
    $idLogement = $_GET["id"];
    $startDate = date("Y-m-d");
    $bookings = RequestBuilder::select(TABLE_NAME)
        ->projection("date_arrivee", "date_depart")
        ->innerJoin("logement", "logement.id_logement = _reservation.id_logement")
        ->innerJoin("proprietaire", "proprietaire.id_compte = logement.id_proprietaire")
        ->where("logement.id_logement = ?", $idLogement)
        ->execute()
        ->fetchMany();

    if (!$bookings) {
        http_response_code(404);
    } else {
        echo json_encode($bookings);
    }
} else {
    http_response_code(400);
}
