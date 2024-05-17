<?php
session_start();
require_once("services/Router.php");
require_once("services/UserSession.php");

$router = new Router();

// home & default view
$router->add("/", "views/home.php");

// account profile & public profile view
$router->add("/profil", "views/account/profile.php");

$router->set404View("views/errors/404.php");

$router->start();
