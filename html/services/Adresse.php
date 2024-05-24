<?php
include_once "RequestBuilder.php";

class Adresse {

    public static function getDepartement($code_postal) {
        $num_dep = substr($code_postal, 0, 2);

        if (strlen($num_dep) == 2) {
            $nom_dep = RequestBuilder::select("pls._departement")
                        ->projection("nom_departement")
                        ->where("num_departement = ?", $num_dep)
                        ->execute()
                        ->fetchOne();

            return $nom_dep ? $nom_dep["nom_departement"] : false;
                        var_dump($commune);
        } else {
            return false;
        }
        
    }
}