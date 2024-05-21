<?php
require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");
require_once(__DIR__."/../services/RequestBuilder.php");
require_once(__DIR__."/../services/FileProprietaire.php");
require_once(__DIR__."/../services/FileLocataire.php");

class AccountModel extends Model {

    private static $TABLE_NAME = "pls._compte";

    public function __construct($data = null, $isNew = true) {

        // define the model of an account
        parent::__construct(self::$TABLE_NAME, array(
            "id_compte" => array("primary" => true),
            "nom"  => array("required" => true),
            "prenom"  => array("required" => true),
            "mot_de_passe"  => array(),
            "telephone"  => array(),
            "date_naissance"  => array("type" => "date"),
            "mail"  => array("required" => true),
            "civilite"  => array(),
            "photo_profil"  => array(
                // computed profile picture
                "get" => array($this, "computeProfilePicture")
            ),
            "displayname" => array(
                // computed displayname
                "get" => array($this, "computeDisplayName")
            )
        ), $data, $isNew);
    }

    public function computeProfilePicture($model) {
        return FileLocataire::get($model->get("id_compte"));
    }

    public function computeDisplayName($model) {
        return strtoupper($model->get("nom")) . " " . $model->get("prenom");
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
        if($result == null)
            return;
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
            ->where("id_compte = ?", $id)
            ->execute()
            ->fetchOne();
        if($result == null)
            return;
        return new self($result, false);
    }
    
}