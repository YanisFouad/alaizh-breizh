<?php
require_once(__DIR__."/../../services/session/UserSession.php");

$redirectTo = "/";
if(isset($_GET) && array_key_exists("redirectTo", $_GET))
    $redirectTo = $_GET["redirectTo"];

UserSession::disconnect();
header("Location: ". $redirectTo);
exit;