<?php
require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");

class AccountModel extends Model {

    public function __construct() {
        // define the model of an account
        parent::__construct("compte", array(
            "identifiant" => array(),
            "nom"  => array("required" => true),
            "prenom"  => array("required" => true),
            "mot_passe"  => array(),
            "telephone"  => array(),
            "date_naissance"  => array(),
            "mail"  => array("required" => true),
            "civilite"  => array(),
            "photo"  => array()
        ));
    }

    /**
     * Find an account by email address
     * @param $mail string a provided email address
     * @param $projection string|array (by default "*") selection of field (like nom or prenom) (optionnal)
     * @return array|null the row found or null if the row wasn't found
     */
    public static function findOneByMail($mail, $projection = "*") {
        if(is_array($projection))
            $projection = join(",", $projection);
        
        $request = Database::getConnection()->prepare("SELECT " . $projection . " FROM compte WHERE mail = ?");
        $request->bindParam(1, $mail);
        $request->execute();
        
        $row = $request->fetch(PDO::FETCH_ASSOC);
        if(sizeof($row) < 1)
            return null;
        return $row;
    }

    /**
     * Find an account by account id
     * @param $id string a provided account id
     * @param $projection string|array (by default "*") selection of field (like nom or prenom) (optionnal)
     * @return array|null the row found or null if the row wasn't found
     */
    public static function findOneById($id, $projection = "*") {
        // @todo try to avoid code duplication (with findOneByMail ....)
        if(is_array($projection))
            $projection = join(",", $projection);

        $request = Database::getConnection()->prepare("SELECT " . $projection . " FROM compte WHERE identifiant = ?");
        $request->bindParam(1, $id);
        $request->execute();
        
        $row = $request->fetch(PDO::FETCH_ASSOC);
        if(sizeof($row) < 1)
            return null;
        return $row;
    }
    
}