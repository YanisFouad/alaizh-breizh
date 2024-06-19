<?php
require_once(__DIR__."/../services/Model.php");
require_once(__DIR__."/../services/Database.php");
require_once(__DIR__."/../services/RequestBuilder.php");
require_once(__DIR__."/../services/fileManager/FileProprietaire.php");
require_once(__DIR__."/../services/fileManager/FileLocataire.php");

enum AccountType: string {
    case TENANT = "locataire";
    case OWNER = "proprietaire";
    case DEFAULT = "_compte";
}

class AccountModel extends Model {

    public function __construct($accountType, $data = null, $isNew = true) {
        $table = $accountType->value;
        
        $schema = array(
            "id_compte" => array("primary" => true),
            "nom"  => array("required" => true),
            "prenom"  => array("required" => true),
            "mot_de_passe"  => array(),
            "telephone"  => array(),
            "date_naissance"  => array("type" => "date"),
            "mail"  => array("required" => true),
            "civilite"  => array(),
            "id_addresse" => array(),
            "numero" => array(),
            "complement_numero" => array(),
            "rue_adresse" => array(),
            "complement_adresse" => array(),
            "ville_adresse" => array(),
            "code_postal_adresse" => array(),
            "pays_adresse" => array(),
            "accountType" => array("get" => $accountType->name),
            "photo_profil"  => array(
                // computed profile picture
                "get" => array($this, "computeProfilePicture")
            ),
            "displayname" => array(
                // computed displayname
                "get" => array($this, "computeDisplayName")
            )
        );

        if($accountType === AccountType::OWNER->name) {
            $schema = array_merge($schema, array(
                "piece_identite" => array(),
                "note_proprietaire" => array(),
                "num_carte_identite" => array(),
                "rib_proprietaire" => array(),
                "date_identite" => array()
            ));
        }

        // define the model of an account
        parent::__construct($table, $schema, $data, $isNew);
    }

    public function computeProfilePicture($data) {
        return FileLocataire::get($data["id_compte"]);
    }

    public function computeDisplayName($data) {
        return strtoupper($data["nom"]) . " " . $data["prenom"];
    }

    /**
     * Find an account by email address
     * @param $mail string a provided email address
     * @return AccountModel|null the row found or null if the row wasn't found
     */
    public static function findOneByMail(string $mail, AccountType $accountType = AccountType::DEFAULT) {
        $result = RequestBuilder::select($accountType->value)
            ->projection("*")
            ->where("mail = ?", $mail)
            ->execute()
            ->fetchOne();
        if($result == null)
            return;
        return new self($accountType, $result, false);
    }

    /**
     * Find an account by account id
     * @param $id string a provided account id
     * @return AccountModel|null the row found or null if the row wasn't found
     */
    public static function findOneById(string $id, $accountType  = AccountType::DEFAULT) {
        // we don't mind about account type here
        $result = RequestBuilder::select($accountType->value)
            ->projection("*")
            ->where("id_compte = ?", $id)
            ->execute()
            ->fetchOne();
        if($result == null)
            return;
        return new self($accountType, $result, false);
    }
    
}