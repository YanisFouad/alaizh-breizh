<?php
require_once(__DIR__ . "/../models/BookingModel.php");
require_once("models/ICalatorModel.php");
require_once("models/LogementICalatorModel.php");
require_once("models/AccountModel.php");

try {
    $key = htmlspecialchars($_GET["key"]);
    $calendar = ICalatorModel::findByKey($key);
    $logementsIds = LogementICalatorModel::findAllById($key);
    $userId = $calendar->get("id_compte");

    $logements = [];
    foreach ($logementsIds as $logementId) {
        $logements[] = $logementId["id_logement"];
    }

    $reservations = BookingModel::findAllById($userId);
    $subscribeStartDate = $calendar->get("start_date");
    $subscribeEndDate = $calendar->get("end_date");

    $reservations = array_filter($reservations, function ($reservation) use ($logements, $subscribeStartDate, $subscribeEndDate) {
        return in_array($reservation["id_logement"], $logements) && $reservation["date_arrivee"] >= $subscribeStartDate && $reservation["date_arrivee"] <= $subscribeEndDate;
    });

    if (!$calendar || !$logementsIds || !$logements || !$reservations) {
        http_response_code(500);
    }
} catch (\Throwable $th) {
    http_response_code(500);
}

$calendar = "BEGIN:VCALENDAR\n";
$calendar .= "VERSION:2.0\n";
$calendar .= "PRODID:-//hacksw/handcal//NONSGML v1.0//FR\n";

foreach ($reservations as $reservation) {
    $idLocataire = $reservation["id_locataire"];
    $locataire = AccountModel::findOneById($idLocataire);

    if ($locataire) {
        $calendar .= "BEGIN:VEVENT\n";
        $calendar .= "DTSTART:" . date("Ymd\THis\Z", strtotime($reservation["date_arrivee"])) . "\n";
        $calendar .= "DTEND:" . date("Ymd\THis\Z", strtotime($reservation["date_depart"])) . "\n";
        $calendar .= "SUMMARY:Reservation\n";
        $calendar .= "Category:Réservations confirmées\n";
        $calendar .= "LOCATION:" . $reservation["numero"] . $reservation['complement_numero'] . ' ' . $reservation["rue_adresse"] . ' ' . $reservation['complement_adresse'] . "\, " . $reservation["ville_adresse"] . ' ' . $reservation["code_postal_adresse"] . "\, " . '\n' . $reservation["pays_adresse"] . "\n";
        $calendar .= "DESCRIPTION:Reservation du logement \"" . $reservation["titre_logement"] . "\"" . "par " . "<b> " . $locataire->get("prenom") . ' ' . strtoupper($locataire->get("nom")) . "</b>" .
        '\nEmail: ' . "<b>" . $locataire->get("mail") . "</b>" . " Téléphone: " . "<b>" . $locataire->get("telephone") . "</b>" . '\n\n' . "<a href=" . $_SERVER['HTTP_HOST'] . "/backoffice/reservation?id=" . $reservation["id_reservation"] . ">Voir la réservation</a>\n";
        $calendar .= "STATUS:CONFIRMED\n";
        $calendar .= "END:VEVENT\n";
    }
}

$calendar .= "END:VCALENDAR\n";

header("Content-Type: text/calendar");
echo $calendar;
