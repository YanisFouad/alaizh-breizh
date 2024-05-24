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
$router->add("/reservation", "views/bookings/booking.php");
$router->add("/backoffice/reservation", "views/backoffice/bookings/booking.php");

// account profile & public profile view
$router->add("/profil", "views/bookings/reservation.php");

// backoffices views
$router->add("/backoffice", "views/backoffice/home.php");
$router->add("/backoffice/nouveau-logement", "views/backoffice/accomodations/newAccommodation.php");

$router->set404View("views/errors/404.php");

$router->start();

require_once("services/session/PurchaseSession.php");
require_once("models/AccommodationModel.php");
PurchaseSession::set(10);