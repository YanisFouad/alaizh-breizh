<?php
    require_once(__DIR__."/../../../models/AccountModel.php");
    require_once(__DIR__."/../../../services/session/UserSession.php");
    require_once(__DIR__."/../../../helpers/globalUtils.php");
    require_once(__DIR__."/../../../helpers/passwordUtils.php");

    if(isset($_POST)) {
        extract($_POST);

        if(!is_valid_email($mail)) {
            send_json_response([
                "errror" => "Email entré invalide."
            ]);
            return;
        }
        if(AccountModel::findOneById($id_compte) !== NULL) {
            send_json_response([
                "error" => "Le compte avec l'id '".$id_compte."' existe déjà."
            ]);
            return;
        }
        if(AccountModel::findOneByMail($mail, AccountType::OWNER) != NULL) {
            send_json_response([
                "error" => "L'email '" . $mail . "' est déjà associé à un compte."
            ]);
            return;
        }
        if($mot_de_passe !== $mot_de_passe_confirm) {
            send_json_response([
                "error" => "Les deux mots de passe ne correspondent pas."
            ]);
            return;
        }

        $nowYear = intval(date_create()->format("Y"));
        $selectedYear = intval(date_create($date_naissance)->format("Y")); 
        if($nowYear-$selectedYear < 18) {
            send_json_response([
                "error" => "Vous devez avoir 18 ans pour vous inscrire."
            ]);
            return;
        }

        unset($_POST["mot_de_passe_confirm"]);
        $accountModel = new AccountModel(AccountType::OWNER, null);
        $_POST["mot_de_passe"] =  hash_password($_POST["mot_de_passe"]);

        // assign picture
        $profilePicture = $_FILES["photo_profil"] ?? NULL;
        if(isset($profilePicture)) {
            FileProprietaire::save($profilePicture, $id_compte);
            $accountModel->set("photo_profil", $id_compte);            
        } else {
            $accountModel->set("photo_profil", "default");
        }

        // assign by default an identity card path
        $accountModel->set("piece_identite", "carte_identite");

        // otherwise assign all other fields
        foreach($_POST as $k => &$v)
            $accountModel->set($k, $v);
        // finally, save the model
        $accountModel->save();

        UserSession::set($accountModel);

        send_json_response(["success" => true]);
    }