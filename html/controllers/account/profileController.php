<?php
require_once(__DIR__."/../../services/session/UserSession.php");

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
    unset($_POST["editProfile"]);
    $profile = UserSession::get();
    
    try {
        foreach($_POST as $key => &$value) {
            $profile->set($key, $value);
        }
        $profile->save();
        sendJson("success", true);
    } catch(Exception $e) {
        sendJson("error", $e->getMessage());
    }
}