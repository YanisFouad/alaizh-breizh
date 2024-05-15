<?php

function handleError($message) {
    echo json_encode(array("error" => $message));
    exit;
}

if(isset($_POST)) {
    header("Content-Type: application/json");
    extract($_POST);

    if(!isset($email) || !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email)) {
        handleError("Email invalide.");
    }
    // @todo vérifier si mot de passe correspond au profil trouvé grâce à l'email.
    if(!isset($password)) {
        handleError("Mot de passe invalide.");
    }

    // @todo manage user session here after all checks

    // if everything is good then return a json with "success" true inside
    echo json_encode(array("success", true));
    exit;
}