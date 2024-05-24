<?php

require_once(__DIR__."/../../services/session/PurchaseSession.php");

if(isset($_POST)) {
    PurchaseSession::set([
        "accommodationId" => $_POST["accommodationId"],
        "nb_voyageurs" => $_POST["nb_voyageurs"],
        "total_ati" => $_POST["total_ati"]
    ]);
    header("Content-type: application/json");
    return json_encode(["success" => true]);
}