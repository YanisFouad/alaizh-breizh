<?php
require_once("models/AccommodationModel.php");
require_once("services/Adresse.php");
require_once("services/session/UserSession.php");

class HomeBackofficeController {
    const NB_ITEM_HOME_BACK = 8;
    public $user;
    public $offset;
    public $currentPage;
    public $logements;
    public $nbLogement;

    public function __construct() {

        if(!UserSession::isConnected()) {
            require_once("views/backoffice/authentication/login.php");
            exit;
        } else {
            $this->setUser(UserSession::get());
        }
        
        if(!isset($_GET["page"]) || intval($_GET["page"]) <= 0) {
            $this->setOffset(0);
        } else {
            $this->setOffset((intval($_GET["page"]) - 1) * self::NB_ITEM_HOME_BACK);
        }
        
        if(isset($_GET["page"])) {
            $this->setCurrentPage(intval($_GET["page"]) == 0 ? 1 : intval($_GET["page"]));
        } else {
            $this->setCurrentPage(1);
        }

        $this->setLogements($this->getUser()->get("id_compte"), $this->getOffset());
        $this->setNbLogement(sizeof($this->getLogements()));

        if($this->getNbLogement() == 0) {
            $this->setOffset(0);
            $this->setLogements($this->getUser()->get("id_compte"), $this->getOffset(), );
        }
    }

    public function setLogements($userId, $offset) {
        $this->logements = AccommodationModel::findById($userId, $offset, self::NB_ITEM_HOME_BACK);
    }
    
    public function getLogements() {
        return $this->logements;
    }

    public function getNbLogement() {
        return $this->nbLogement;
    }

    public function setNbLogement($userId) {
        $this->nbLogement = sizeof(AccommodationModel::findAllById($this->getUser()->get("id_compte")));
    }
    
    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function setOffset($offset) {
        $this->offset = $offset;
    }

    public function setCurrentPage($page) {
        $this->currentPage = $page;
    }
    
    public function getPreviousPage() {
        if ($this->getCurrentPage() == 1) {
            return false;
        } else {
            return $this->getCurrentPage() - 1;
        }
    }
    
    public function getNextPage() {
        if ($this->getCurrentPage() * self::NB_ITEM_HOME_BACK > $this->getNbLogement()) {
            return false;
        } else {
            return $this->getCurrentPage() + 1;
        }
    }
    
    public function getNextPageUrl() {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . "?page=" . $this->getNextPage();
    }
    
    public function getPreviousPageUrl() {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . "?page=" . $this->getPreviousPage();
    }

    public function getIndexPageUrl($index) {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . "?page=" . $index;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }
}