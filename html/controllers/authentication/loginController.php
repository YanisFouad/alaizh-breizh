<?php

require_once(__DIR__."/../../models/AccountModel.php");
require_once(__DIR__."/../../services/UserSession.php");

$EMAIL_REGEX_PATTERN = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";

function sendJson($key, $message) {
    header("Content-Type: application/json");
    echo json_encode(array($key => $message));
    exit;
}

if(isset($_POST)) {
    extract($_POST);

    if(!isset($email) || !preg_match($EMAIL_REGEX_PATTERN, $email)) {
        sendJson("error", "Email invalide.");
    }
    $account = AccountModel::findOneByMail($email);

    if(!isset($account)) {
        sendJson("error", "Le compte associé est introuvable.");
    }

    if(!isset($password) || !password_verify($password, $account->get("mot_passe"))) {
        sendJson("error", "Mot de passe invalide.");
    }

    UserSession::updateSession($account);

    // if everything is good then return a json with "success" true inside
    sendJson("success", true);
}