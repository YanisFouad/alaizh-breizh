<?php
include_once("services/RequestBuilder.php");
include_once("models/AccommodationModel.php");
include_once("models/BookingModel.php");
include_once("models/AccountModel.php");

include_once("services/session/UserSession.php");

class BookingProprietaireController
{
    const TABLE_NAME = "pls._reservation";

    public $reservation;
    public $reservationId;
    public $user;
    public $adresse;
    public $locataire;
    public $logement;

    public function __construct($reservationId) {
        $this->reservationId = $reservationId;
        $this->setUser(UserSession::get());

        $this->setReservation();
        $this->setLogement();
        $this->setLocataire();
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
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getAdresse() {
        $result = RequestBuilder::select("pls._adresse")
            ->projection("*")
            ->where("id_adresse = ?", $this->getLogement()->get("id_adresse"))
            ->execute()
            ->fetchOne();

        $this->adresse = $result;
    }
    
    public function getLocataire() {
        return $this->locataire;
    }

    public function setLocataire() {
        $this->locataire = AccountModel::findOneById($this->getReservation()->get("id_locataire"));;
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
        $phone = $this->getLocataire()->get("telephone");
        $phone = str_replace(' ', '', $phone);

        if ($phone[0] != '0') {
            $phone = '0'.$phone;
        }
        
        return wordwrap($phone, 2, ' ', true);
    }
}
?>