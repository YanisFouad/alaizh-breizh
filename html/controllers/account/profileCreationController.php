<?php
    require_once(__DIR__."/../../models/AccountModel.php");
    require_once(__DIR__."/../../services/session/UserSession.php");
    require_once(__DIR__."/../../helpers/globalUtils.php");
    require_once(__DIR__."/../../helpers/passwordUtils.php");

    if(isset($_POST)) {
        extract($_POST);

        // verifications
        if(!is_valid_email($mail)) {
            send_json_response([
                "error" => "L'email saisi n'est pas valide."
            ]);
            return;
        }

        if(AccountModel::findOneById($id_compte) != NULL) {
            send_json_response([
                "error" => "Le compte '".$id_compte."' existe déjà."
            ]);
            return;
        }

        if(AccountModel::findOneByMail($mail, AccountType::TENANT) != NULL) {
            send_json_response([
                "error" => "L'email '".$mail."' est déjà associé à un compte."
            ]);
            return;
        }

        $now = intval(date_create()->format("Y"));
        $selectedYear = intval(date_create($date_naissance)->format("Y"));
        if($now-$selectedYear < 18) {
            send_json_response([
                "error" => "Vous devez avoir 18 ans pour vous inscrire."
            ]);
            return;
        }

        if($mot_de_passe !== $mot_de_passe_confirm) {
            send_json_response([
                "error" => "Les deux mots de passe ne correspondent pas."
            ]);
            return;
        }

        // set model
        try {
            $accountModel = new AccountModel(AccountType::TENANT);
            unset($_POST["mot_de_passe_confirm"]);

            $_POST["mot_de_passe"] =  hash_password($_POST["mot_de_passe"]);

            // assign picture
            $profilePicture = $_FILES["photo_profil"] ?? NULL;
            if(isset($profilePicture) && trim($profilePicture["name"]) !== "") {
                FileLocataire::save($profilePicture, $id_compte);
                $accountModel->set("photo_profil", $id_compte);            
            } else {
                $accountModel->set("photo_profil", "default");
            }

            foreach($_POST as $k => &$v) {
                $accountModel->set($k, $v);
            }
            $accountModel->save();

            UserSession::set($accountModel);
            send_json_response(["success" => true]);
        } catch(Exception $e) {
            send_json_response([
                "error" => $e->getMessage()
            ]);
        }
    }