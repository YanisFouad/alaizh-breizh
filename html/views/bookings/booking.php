<?php
require_once("services/session/UserSession.php");
setlocale(LC_TIME, "fr_FR");

if (isset($_GET['id'])){
    $id_reservation = intval($_GET['id']);
} else {
    $id_reservation = false;
}

if(!UserSession::isConnected()){
    header("Location: /backoffice");
}

if ($id_reservation == 0 || empty($id_reservation) || !is_numeric($id_reservation )){
    header("Location: /");
}

require_once("controllers/BookingLocataireController.php");

$controller = new BookingLocataireController($id_reservation);

if($controller->getReservation() == null || $controller->getReservation()->get("id_locataire") != $controller->getUser()->get("id_compte")){
    header("Location: /");
}

require_once(__DIR__."/../layout/header.php");  
?>

<section id="finalize-booking">
    <header>
        <button id="finalize-booking-back-button">
            <span class="mdi mdi-arrow-left"></span>
            Retour
        </button>
        <h1>Récapitulatif de ma réservation</h1>
    </header>
    
    <div role="main">
        <h2>
            <span>Mon choix</span>
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
                            <img src="<?= $controller->getProprietaire()->get("photo_profil")?>" alt="Propriétaire">
                        </div>
                        <div class="name-proprietaire">
                            <p><?= $controller->getProprietaire()->get("nom") ?></p>
                            <p><?= $controller->getProprietaire()->get("prenom") ?></p>
                        </div>
                    </div>
                    <div class="contact-proprio">
                        <p>Numéro : <?= $controller->getProprietaire()->get("telephone") ?></p>
                        <p>Adresse mail : <?= $controller->getProprietaire()->get("mail") ?></p>
                    </div>
                </div>
                <!-- TO DO -->
                <a href="/">
                    <button class="primary">
                        Accéder à l'annonce
                        <span class="mdi mdi-chevron-right"></span>
                    </button>
                </a>

            </article>
        </article>
        
        <h2>
            <span>Mon séjour</span>
            <span></span>
        </h2>
        <article class="sejour">
            <div>
                <div>
                    <h4>Dates:</h4>
                    <h4><?= $controller->getFormatDate($controller->getReservation()->get("date_arrivee")) ?> - <?= $controller->getFormatDate($controller->getReservation()->get("date_depart"))?></h4>
                </div>
                <div>
                    <h4>Voyageur(s):</h4>
                    <h4><?= $controller->getReservation()->get("nb_voyageur")?></h4>
                </div>
                <div class="container-amenagement">
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
            </div>
            <h3>Prix total: <span><?= round($controller->getReservation()->get("prix_total"), 2) ?> &#8364;</span></h3>
        </article>

        <h2>
            <span>Conditions d'annulation</span>
            <span></span>
        </h2>

        <article class="annulation">
            <p>
            <?php
                $interval = new DateInterval('P' . $controller->getLogement()->get("delais_prevenance") . 'D');
                $dateRemboursement = $controller->getReservation()->get("date_arrivee")->sub($interval);
                $currentDate = new DateTime("now");

                if ($controller->getReservation()->get("date_arrivee") <= $currentDate) { ?>
                    Votre réservation est en cours ou passée. Vous ne pouvez plus vous faire rembourser votre réservation.
                <?php
                } else { ?>
                    En annulant avant le <b><?= $controller->getFormatDate($dateRemboursement) ?></b>, vous serez remboursé <b>intégralement</b>. Passée cette date, vous ne serez remboursé qu’à hauteur de 50%.
                <?php
                }
                
            ?>
            </p>
        </article>
    </div>
</section>

<?php require_once(__DIR__."/../layout/footer.php"); ?>