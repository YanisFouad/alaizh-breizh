<?php
require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");
require_once(__DIR__."/../services/RequestBuilder.php");

class AccountModel extends Model {

    private static $TABLE_NAME = "compte";

    public function __construct($data = null, $isNew = true) {
        // define the model of an account
        parent::__construct(self::$TABLE_NAME, array(
            "identifiant" => array("primary" => true),
            "nom"  => array("required" => true),
            "prenom"  => array("required" => true),
            "mot_passe"  => array(),
            "telephone"  => array(),
            "date_naissance"  => array(),
            "mail"  => array("required" => true),
            "civilite"  => array(),
            "photo"  => array()
        ), $data, $isNew);
    }

    /**
     * Find an account by email address
     * @param $mail string a provided email address
     * @return AccountModel|null the row found or null if the row wasn't found
     */
    public static function findOneByMail($mail) {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("*")
            ->where("mail = ?", $mail)
            ->execute()
            ->fetchOne();
        return new self($result, false);
    }

    /**
     * Find an account by account id
     * @param $id string a provided account id
     * @return AccountModel|null the row found or null if the row wasn't found
     */
    public static function findOneById($id) {
        $result = RequestBuilder::select(self::$TABLE_NAME)
            ->projection("*")
            ->where("identifiant = ?", $id)
            ->execute()
            ->fetchMany();
        return new self($result, false);
    }
    
}