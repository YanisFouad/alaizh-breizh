<?php
session_start();
require_once("services/Router.php");
require_once("services/session/UserSession.php");
require_once("services/ScriptLoader.php");

$router = new Router();

// home & default view
$router->add("/", "views/home.php");
$router->add("/logements", "views/housing-list.php");

// bookings
$router->add("/finaliser-ma-reservation", "views/bookings/finalizeBooking.php");

// account profile & public profile view
$router->add("/profil", "views/account/profile.php");

// backoffices views
$router->add("/backoffice", "views/backoffice/home.php");
$router->add("/backoffice/nouveau-logement", "views/backoffice/accomodations/newAccommodation.php");
$router->add("/backoffice/reservations", "views/backoffice/booking/listeReservationsProprietaire.php");
$router->add("/reservations", "views/listeReservationsLocataire.php");

$router->set404View("views/errors/404.php");

$router->start();

?>