<?php
session_start();
require_once("services/Router.php");
require_once("services/UserSession.php");

$router = new Router();

// home & default view
$router->add("/", "views/home.php");

// account profile & public proqfile view
$router->add("/profil", "views/account/profile.php");

$router->add("/logement", "views/accomodations/pageDetaillee.php");

// backoffices views
$router->add("/backoffice", "views/backoffice/home.php");
$router->add("/backoffice/connexion", "views/backoffice/login.php");
$router->add("/backoffice/logements/nouveau", "views/backoffice/accomodations/new.php");
$router->add("/backoffice/logements/pageDetailleeProprietaire", "views/backoffice/accomodations/pageDetailleeProprietaire.php");

$router->add("/housing-list", "views/housing-list.php");

$router->set404View("views/errors/404.php");

$router->start();

// require_once("./models/AccommodationModel.php");
// foreach(AccommodationModel::find(0, 10) as $accomodation) {
//     var_dump($accomodation->get("id_logement"));
// }