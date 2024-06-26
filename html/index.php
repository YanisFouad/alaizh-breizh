<?php
session_start();
require_once("services/Router.php");
require_once("services/session/UserSession.php");
require_once("services/ScriptLoader.php");
require_once("helpers/globalUtils.php");

ScriptLoader::load("notification.js");

$router = new Router();

// home & default view
$router->add("/", "views/home.php");

// accommodation
$router->add("/logements", "views/housingList.php");
$router->add("/logement", "views/accommodations/pageDetaillee.php");

// bookings
$router->add("/finaliser-ma-reservation", "views/bookings/finalizeBooking.php");

$router->add("/reservations", "views/bookings/listeReservationsLocataire.php");
$router->add("/backoffice/reservation", "views/backoffice/bookings/booking.php");
$router->add("/reservation", "views/bookings/booking.php");

// account profile & public profile view
$router->add("/profil", "views/account/profile.php");
$router->add("/inscription", "views/account/profileCreation.php");


// backoffices views
$router->add("/backoffice", "views/backoffice/home.php");

$router->add("/backoffice/nouveau-logement", "views/backoffice/accomodations/newAccommodation.php");
$router->add("/backoffice/reservations", "views/backoffice/booking/listeReservationsProprietaire.php");
$router->add("/backoffice/logement", "views/backoffice/accomodations/pageDetailleeProprietaire.php");
$router->add("/backoffice/modification-logement", "views/backoffice/accomodations/modificationLogement.php");

$router->add("/backoffice/previsualisation-logement", "views/backoffice/accomodations/accommodationPreview.php");

$router->add("/backoffice/profil", "views/backoffice/account/profile.php");

// facture pdf
$router->add("/facture", "controllers/facturePdf.php");
$router->add("/backoffice/facture", "controllers/facturePdf.php");

// iCalator
$router->add("/icalator", "services/iCalator.php");
$router->add("/backoffice/calendrier/nouveau", "views/backoffice/icalator/iCalator.php");
$router->add("/backoffice/calendrier", "views/backoffice/icalator/homeiCalator.php");
$router->add("/backoffice/calendrier/succes", "views/backoffice/icalator/iCalatorSuccess.php");
$router->add("/backoffice/calendrier/voir", "views/backoffice/icalator/iCalatorRead.php");
$router->add("/backoffice/calendrier/editer", "views/backoffice/icalator/iCalatorEdit.php");
$router->add("/backoffice/calendrier/supprimer", "controllers/backoffice/icalator/iCalatorDelete.php");

$router->set404View("views/errors/404.php");

$router->start();