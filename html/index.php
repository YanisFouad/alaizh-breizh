<?php

require_once("services/Router.php");

$router = new Router();

// home view
$router->add("/", "views/home.php");

$router->set404View("views/errors/404.php");

$router->start();