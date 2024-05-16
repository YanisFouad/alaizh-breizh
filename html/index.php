<<<<<<< HEAD
<?php
session_start();
require_once("services/Router.php");
require_once("services/UserSession.php");

$router = new Router();

// home & default view
$router->add("/", "views/home.php");

// account profile view
$router->add("/profile", "views/account/profile.php");

$router->set404View("views/errors/404.php");

$router->start();
=======
<?php
require_once("./services/database.php");
>>>>>>> 6a1f5da470a542107709de9ada761192bcaa2708
