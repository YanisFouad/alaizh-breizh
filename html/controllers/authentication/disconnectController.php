<?php
require_once(__DIR__."/../../services/UserSession.php");

UserSession::disconnect();
header("Location: /");
exit;