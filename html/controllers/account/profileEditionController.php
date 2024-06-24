<?php
require_once(__DIR__."/../../services/session/UserSession.php");
require_once(__DIR__."/../../models/AccountModel.php");
require_once(__DIR__."/../../helpers/globalUtils.php");
require_once(__DIR__."/../../helpers/apiUtils.php");

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

if(isset($_POST) && isset($_POST["generateApiKey"])) {
    $profile = UserSession::get();
    try {
        $apiKeyGenerated = generate_api_key();
        $profile->set("cle_api", $apiKeyGenerated);
        $profile->save();

        // update the user session profile
        UserSession::set($profile);

        send_json_response(["apiKey" => $apiKeyGenerated]);
    } catch(Exception $e) {
        send_json_response([
            "error" => "Erreur lors de la création de l'api key: " . $e->getMessage()
        ]);
    }
}

if(isset($_POST) && isset($_POST["editProfile"])) {
    // first remove the edit profile attribute
    $profilePicture = $_FILES["profilePicture"] ?? NULL;
    unset($_POST["editProfile"]);
    
    $profile = UserSession::get();

    $nowYear = intval(date_create()->format("Y"));
    $selectedYear = intval(date_create($_POST["date_naissance"])->format("Y")); 
    if($nowYear-$selectedYear < 18) {
        send_json_response(["error" => "L'âge ne doit pas être inférieur à 18 ans !"]);
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

        send_json_response(["success" => true]);
    } catch(Exception $e) {
        send_json_response(["error" => $e->getMessage()]);
    }
}