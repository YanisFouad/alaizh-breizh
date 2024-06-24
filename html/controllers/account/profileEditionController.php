<?php
require_once(__DIR__."/../../services/session/UserSession.php");
require_once(__DIR__."/../../models/AccountModel.php");

function sendJson($key, $message) {
    header("Content-Type: application/json");
    echo json_encode(array($key => $message));
    exit;
}

function obsfuceRIB($rib) {
    $obsfuceLength = 4;
    $length = strlen($rib);
    $rib2keep = substr($rib, 0, $obsfuceLength);
    return wordwrap(
        $rib2keep . join("", array_fill(0, $length-$obsfuceLength-1,"*")), 
        4, 
        " ", 
        true
    );
}

if(isset($_POST) && isset($_POST["editProfile"])) {
    // first remove the edit profile attribute
    $profilePicture = $_FILES["profilePicture"] ?? NULL;
    unset($_POST["editProfile"]);
    unset($_POST["profilePicture"]);

    $profile = UserSession::get();

    $nowYear = intval(date_create()->format("Y"));
    $selectedYear = intval(date_create($date_naissance)->format("Y")); 
    if($nowYear-$selectedYear < 18) {
        sendJson("error", "L'âge ne doit pas être inférieur à 18 ans !");
        return;
    }

    
    try {
        foreach($_POST as $key => &$value) {
            $profile->set($key, htmlentities($value));
        }
        if($profilePicture) {
            $pictureName = $profile->get("id_compte");
            if($profile->get("accountType") === AccountType::TENANT->name) {
                FileLocataire::save($profilePicture, $pictureName);
            } else {
                FileProprietaire::save($profilePicture, $pictureName);
            }
            $profile->set("photo_profil", $pictureName);
        }

        $profile->save();

        // update the user session profile
        UserSession::set($profile);

        sendJson("success", true);
    } catch(Exception $e) {
        sendJson("error", $e->getMessage());
    }
}