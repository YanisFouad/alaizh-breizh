<?php
require_once("services/session/UserSession.php");

if (isset($_GET['id'])){
    $id_reservation = intval($_GET['id']);
} else {
    $id_reservation = false;
}

if(!UserSession::isConnectedAsOwner()){
    header("Location: /backoffice");
}

if ($id_reservation == 0 || empty($id_reservation) || !is_numeric($id_reservation )){
    header("Location: /backoffice");
}

require_once("controllers/backoffice/bookings/BookingProprietaireController.php");

$controller = new BookingProprietaireController($id_reservation);

if($controller->getReservation() == null || $controller->getLogement()->get("id_proprietaire") != $controller->getUser()->get("id_compte")){
    header("Location: /backoffice");
}

require_once(__DIR__."/../layout/header.php");  
?>

<section id="finalize-booking">
    <header>
        <a href="/backoffice/reservations" id="finalize-booking-back-button">
            <button>
                <span class="mdi mdi-arrow-left"></span>
                Retour
            </button>
        </a>
        <h1>Récapitulatif de la réservation</h1>
    </header>
    
    <div role="main">
        <h2>
            <span>Son logement</span>
            <span></span>
        </h2>

        <article class="logement">
            <div class="img-logement-container">
                <img src="<?= $controller->getLogement()->get("photo_logement") ?>" alt="<?= $controller->getLogement()->get("titre_logement") ?>">
            </div>
            <article class="logement-container">
                <div class="description-logement">
                    <h3><?= $controller->getLogement()->get("titre_logement") ?></h3>
                    <h4>
                        <span class="mdi mdi-map-marker"></span>
                        <?= $controller->adresseToString() ?>
                    </h4>
                    <p><?= $controller->getLogement()->get("description_logement") ?></p>
                </div>
                <div class="information-proprio">
                    <div class="img-proprio-container">
                        <div class="img-container">
                            <img src="<?= $controller->getLocataire()->get("photo_profil")?>" alt="Propriétaire">
                        </div>
                        <div class="name-proprietaire">
                            <p><?= $controller->getLocataire()->get("nom") ?></p>
                            <p><?= $controller->getLocataire()->get("prenom") ?></p>
                        </div>
                    </div>
                    <div class="contact-proprio">
                        <p>Numéro : <?= $controller->getPhoneNumber() ?></p>
                        <p>Adresse mail : <?= $controller->getLocataire()->get("mail") ?></p>
                    </div>
                </div>
                <a href="/backoffice/logement/?id_logement=<?= $controller->getLogement()->get("id_logement") ?>">
                    <button class="primary backoffice">
                        Accéder à l'annonce
                        <span class="mdi mdi-chevron-right"></span>
                    </button>
                </a>

            </article>
        </article>
        
        <h2>
            <span>Son séjour</span>
            <span></span>
        </h2>
        <article class="sejour">
            <div>
                <div>
                    <h4>Dates:</h4>
                    <h4><?= to_french_date($controller->getReservation()->get("date_arrivee")) ?> - <?= to_french_date($controller->getReservation()->get("date_depart"))?></h4>
                </div>
                <div>
                    <h4>Voyageur(s):</h4>
                    <h4><?= $controller->getReservation()->get("nb_voyageur")?></h4>
                </div>
                <div class="container-amenagement">
                    <?php
                    if (sizeof($controller->getLogement()->get("amenagements")) != 0 && $controller->getLogement()->get("amenagements")[0]["name"] != NULL) { ?>
                        <h4>Aménagement(s):</h4>
                        <div class="list-amenagement">
                            <?php
                            foreach($controller->getLogement()->get("amenagements") as $amenagement) {
                                switch ($amenagement["name"]) {
                                    case 'jardin':
                                        echo "<p><span class='mdi mdi-tree-outline'></span>Jardin</p>";
                                        break;
                                    case 'piscine':
                                        echo "<p><span class='mdi mdi-pool-outline'></span>Piscine</p>";
                                        break;
                                    case 'jacuzzi':
                                        echo "<p><span class='mdi mdi-hot-tub'></span>Jacuzzi</p>";
                                        break;
                                    case 'terrasse':
                                        echo "<p><span class='mdi mdi-floor-plan'></span>Terrasse</p>";
                                        break;
                                    case 'balcon':
                                        echo "<p><span class='mdi mdi-balcony'></span>Balcon</p>";
                                        break;
                                    default:
                                        break;
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    
            </div>

                
                <h3>Prix total : <span><?= number_format($controller->getReservation()->get("prix_total"), 2, ",", ' ') ?> &#8364;</span></h3>
        </article>

        <h2>
            <span>Conditions d'annulation</span>
            <span></span>
        </h2>

        <article class="annulation">
            <p>
            <?php
                if (is_string($controller->getReservation()->get("date_arrivee"))) {
                    $dateArrivee = new DateTime($controller->getReservation()->get("date_arrivee"));
                } else {
                    $dateArrivee = $controller->getReservation()->get("date_arrivee");
                }

                $interval = new DateInterval('P' . $controller->getLogement()->get("delais_prevenance") . 'D');
                $dateRemboursement = $dateArrivee->sub($interval);
                $currentDate = new DateTime("now");

                if ($controller->getReservation()->get("date_arrivee") <= $currentDate) { ?>
                    Sa réservation est en cours ou passée. Il/Elle ne peut plus se faire rembourser sa réservation.
                <?php
                } else { ?>
                    En annulant avant le <b><?= $controller->getFormatDate($dateRemboursement) ?></b>, il/elle sera remboursé(e) <b>intégralement</b>. Passée cette date, il/elle sera remboursé(e) qu’à hauteur de 50%.
                <?php
                }
                
            ?>
            </p>
        </article>
    </div>
</section>

<a href="/backoffice/facture?id=<?= $controller->getReservation()->get("id_reservation") ?>"  target="_blank" id="btn-facture-detail">
    <button class="primary backoffice btn-facture">
        <span class="mdi mdi-eye-outline"></span>
        Facture
    </button>
</a>

<?php require_once("views/layout/footer.php"); ?>