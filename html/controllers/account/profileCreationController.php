<?php
    require_once(__DIR__."/../../models/AccountModel.php");

    if ($_POST){
        creation();
    }

    function creation(){
        $account = new AccountModel(AccountType::TENANT);

        if (!filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) {
            $_POST["error_mail"] = "Adresse e-mail invalide.";
            header("Location: /inscription");
        }
            
        if ($_POST["mot_de_passe"] !== $_POST["mot_de_passe_confirm"]) {
            $_POST["error_mdp"] = "Les mots de passe ne correspondent pas.";
        }
        
        foreach ($_POST as $field => $value) {
            if ($field == "date_naissance"){
                $value = date('Y-m-d', strtotime($value));
                $account->set($field, $value);
            }
            if ($field == "mot_de_passe"){
                $value = password_hash($value,PASSWORD_BCRYPT);
                $account->set($field, $value);
            }
            if ($field!="mot_de_passe_confirm" && $field!="error_mail" && $field!="error_mdp") {
                $account->set($field, $value);
            }
        }

        $account->save();
        echo "SUCCESS";
    }  
?>
