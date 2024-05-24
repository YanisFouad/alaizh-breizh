<?php
include_once(__DIR__."/../services/RequestBuilder.php");
include_once(__DIR__."/../models/AccommodationModel.php");
include_once(__DIR__."/../models/BookingModel.php");
include_once(__DIR__."/../models/AccountModel.php");
    
class BookingLocataireController
{
    const TABLE_NAME = "pls._reservation";

    public $reservation;
    public $reservationId;
    public $user;
    public $adresse;
    public $proprietaire;
    public $logement;

    public function __construct($reservationId) {
        $this->reservationId = $reservationId;

        $this->setReservation();
        $this->setLogement();
        $this->setProprietaire();
        $this->setAdresse();
    }

    public function getReservationId() {
        return $this->reservationId;
    }

    public function setReservation() {
        $this->reservation = BookingModel::findOneById($this->getReservationId());
    }

    public function getReservation() {
        return $this->reservation;
    }

    public function getUser() {
        return UserSession::get();
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function setAdresse() {
        var_dump($this->getLogement());
        $result = RequestBuilder::select("pls._adresse")
            ->projection("*")
            ->where("id_adresse = ?", $this->getLogement()->get("id_adresse"))
            ->execute()
            ->fetchOne();

        $this->adresse = $result;
    }
    
    public function getProprietaire() {
        return $this->proprietaire;
    }

    public function setProprietaire() {
        $this->proprietaire = AccountModel::findOneById(UserSession::get()->get("id_compte"));
    }

    public function getLogement() {
        return $this->logement;
    }

    public function setLogement() {
        $this->logement = AccommodationModel::findOneById($this->getReservation()->get("id_logement"));
    }

    public function adresseToString() {
        $code_postal = $this->getAdresse()["code_postal_adresse"];
        $num_dep = substr($code_postal, 0, 2);

        if (strlen($num_dep) == 2) {
            $nom_dep = RequestBuilder::select("pls._departement")
                        ->projection("nom_departement")
                        ->where("num_departement = ?", $num_dep)
                        ->execute()
                        ->fetchOne();
        }

        return  $this->getAdresse()["numero"] . $this->getAdresse()["complement_adresse"] . ", ". $this->getAdresse()["rue_adresse"].", ".$this->getAdresse()["code_postal_adresse"].", ".$this->getAdresse()["ville_adresse"] . ", " . $nom_dep["nom_departement"];
    }

    public function getFormatDate($date) {
        $months = [
            '01' => 'Janvier',
            '02' => 'Février',
            '03' => 'Mars',
            '04' => 'Avril',
            '05' => 'Mai',
            '06' => 'Juin',
            '07' => 'Juillet',
            '08' => 'Août',
            '09' => 'Septembre',
            '10' => 'Octobre',
            '11' => 'Novembre',
            '12' => 'Décembre',
        ];
        
        $day = $date->format('d');
        $month = $months[$date->format('m')];
        $year = $date->format('Y');
        
        return "$day $month $year";
    }

    public function getPhoneNumber() {
        $phone = $this->getProprietaire()->get("telephone");
        $phone = preg_replace('/\s+/', '', $phone);

        if ($phone[0] != '0') {
            $phone = '0'.$phone;
        }
        
        return wordwrap($this->getProprietaire()->get("telephone"), 2, ' ', true);
    }
}
?>