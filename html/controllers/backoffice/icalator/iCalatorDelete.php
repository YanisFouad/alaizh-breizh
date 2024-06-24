<?php
include_once("models/ICalatorModel.php");
include_once("models/LogementICalatorModel.php");

try {
    if (isset($_GET["key"])) {
        $key = $_GET["key"];
        $calendar = ICalatorModel::findByKey($key);
        if ($calendar) {
            $logements = LogementICalatorModel::findById($calendar->get("cle_api"));
            foreach ($logements as $logement) {
                $logement->delete();
            }
            $calendar->delete();
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    } else {
        http_response_code(404);
    }
} catch (\Throwable $th) {
    http_response_code(500);
}
