<?php
require_once("models/AccommodationModel.php");
require_once("services/FileLogement.php");
require_once("services/Adresse.php");
require_once("services/UserSession.php");
const NB_ITEM = 8;

if(!isset($_GET["page"]) || intval($_GET["page"]) <= 0) {
    $offset = 0;
} else {
    $offset = (intval($_GET["page"]) - 1) * NB_ITEM;
}

$logements = getLogements($offset);
$nbLogement = getNbLogement($logements);

if($nbLogement == 0) {
    $offset = 0;
    $logements = getLogements($offset);
    $nbLogement = getNbLogement($logements);
}

function getLogements($offset) {
    return AccommodationModel::find($offset, NB_ITEM);
}

function getNbLogement($logements) {
    return sizeof($logements);
}