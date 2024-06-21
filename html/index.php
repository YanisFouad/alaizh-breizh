<?php
session_start();
require_once("services/Router.php");
require_once("services/session/UserSession.php");
require_once("services/ScriptLoader.php");
require_once("helpers/gobalUtils.php");

ScriptLoader::load("notification.js");

$router = new Router();

// home & default view
$router->add("/", "views/home.php");

// accommodation
$router->add("/logements", "views/housingList.php");
$router->add("/logement", "views/accommodations/pageDetaillee.php");

// bookings
$router->add("/finaliser-ma-reservation", "views/bookings/finalizeBooking.php");

$router->add("/reservations", "views/listeReservationsLocataire.php");
$router->add("/backoffice/reservation", "views/backoffice/bookings/booking.php");
$router->add("/reservation", "views/bookings/booking.php");

// account profile & public profile view
$router->add("/profil", "views/account/profile.php");

// backoffices views
$router->add("/backoffice", "views/backoffice/home.php");
$router->add("/backoffice/nouveau-logement", "views/backoffice/accomodations/newAccommodation.php");
$router->add("/backoffice/reservations", "views/backoffice/booking/listeReservationsProprietaire.php");
$router->add("/backoffice/logement", "views/backoffice/accomodations/pageDetailleeProprietaire.php");

// facture pdf
$router->add("/facture", "controllers/facturePdf.php");
$router->add("/backoffice/facture", "controllers/facturePdf.php");

$router->set404View("views/errors/404.php");

$router->start();