<?php
require_once(__DIR__."/../../services/session/UserSession.php");

$redirectTo = "/";
if(isset($_GET) && isset($_GET["redirectTo"]))
    $redirectTo = $_GET["redirectTo"];

UserSession::disconnect();
header("Location: ". $redirectTo . "?notification-message=Déconnecté&notification-type=SUCCESS");
exit;