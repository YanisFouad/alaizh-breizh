<?php
    require_once(__DIR__."/../../models/AccountModel.php");

    if ($_POST){
        creation();
    }

    function creation(){
        $account = new AccountModel("locataire");
        
        foreach ($_POST as $field => $value) {
            if ($field == "date_naissance"){
                $date = date_create($value);
                var_dump("date create = ",$date);
                $value = date('Y/m/d', strtotime($value));
                var_dump("strtotime = ",$value);
                die;
                $account->set($field, $value);
            }
            if ($field == "mot_de_passe"){
                $value = password_hash($value,PASSWORD_BCRYPT);
                $account->set($field, $value);
            }
            //var_dump($field,$value);
            $account->set($field, $value);
        }

        $account->save();
        echo "SUCCESS";
    }  
?>
