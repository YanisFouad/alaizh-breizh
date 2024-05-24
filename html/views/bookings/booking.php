<?php
//require_once(__DIR__."/../../controllers/BookingLocataireController.php");
include_once(__DIR__."/../../services/RequestBuilder.php");

include_once(__DIR__."/../../models/AccommodationModel.php");
include_once(__DIR__."/../../models/BookingModel.php");
include_once(__DIR__."/../../models/AccountModel.php");


setlocale(LC_TIME, "fr_FR");

if (isset($_GET['id'])){
    $id_reservation = intval($_GET['id']);
} else {
    $id_reservation = false;
}

if (!UserSession::isConnected() || $id_reservation == 0 || empty($id_reservation) || !is_numeric($id_reservation)){
    exit(header("Location: /"));
}


//$controller = new BookingLocataireController($id_reservation); 
$reservation = BookingModel::findOneById($id_reservation);

if(!isset($reservation) || $reservation->get("id_compte") != UserSession::get()->get("id_compte")){
    exit(header("Location: /"));
}

$logement = AccommodationModel::findOneById($reservation->get("id_logement"));
$prioprio = AccountModel::findOneById($logement->get("id_proprietaire"));
$adresse = RequestBuilder::select("_adresse")
    ->where("id_adresse = ?", $logement->get("id_adresse")) 
    ->execute()
    ->fetchOne();

require_once(__DIR__."/../layout/header.php");

function getFormatDate($date) {
    if (is_string($date)) {
        $date = new DateTime($date);
    }
    
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
?>

<section id="finalize-booking">
    <header>
        <a href="/reservations" id="finalize-booking-back-button">
            <button>
                <span class="mdi mdi-arrow-left"></span>
                Retour
            </button>
        </a>
        
        <h1>Récapitulatif de ma réservation</h1>
    </header>
    
    <div role="main">
        <h2>
            <span>Mon choix</span>
            <span></span>
        </h2>

        <article class="logement">
            <div class="img-logement-container">
                <img src="<?= $logement->get("photo_logement") ?>" alt="<?= $logement->get("titre_logement") ?>">
            </div>
            <article class="logement-container">
                <div class="description-logement">
                    <h3><?= $logement->get("titre_logement") ?></h3>
                    <h4>
                        <span class="mdi mdi-map-marker"></span>
                        <?=$logement->get("ville_adresse")?>, <?$logement->get("pays_adresse")?>
                    </h4>
                    <p><?= $logement->get("description_logement") ?></p>
                </div>
                <div class="information-proprio">
                    <div class="img-proprio-container">
                        <div class="img-container">
                            <img src="<?= $prioprio->get("photo_profil")?>" alt="Propriétaire">
                        </div>
                        <div class="name-proprietaire">
                            <p><?= $prioprio->get("nom") ?></p>
                            <p><?= $prioprio->get("prenom") ?></p>
                        </div>
                    </div>
                    <div class="contact-proprio">
                        <p>Numéro : <?= $prioprio->get("telephone") ?></p>
                        <p>Adresse mail : <?=$prioprio->get("mail") ?></p>
                    </div>
                </div>
                <a href="/logement?id_logement=<?= $logement->get("id_logement") ?>">
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
                    <h4><?= getFormatDate($reservation->get("date_arrivee"))?> - <?= getFormatDate($reservation->get("date_depart"))?></h4>
                </div>
                <div>
                    <h4>Voyageur(s):</h4>
                    <h4><?= $reservation->get("nb_voyageur")?></h4>
                </div>
                <div class="container-amenagement">
                    <h4>Aménagement(s):</h4>
                    <div class="list-amenagement">
                        <?php
                        foreach($logement->get("amenagements") as $amenagement) {
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
            <h3>Prix total: <span><?= round($reservation->get("prix_total"), 2) ?> &#8364;</span></h3>
        </article>

        <h2>
            <span>Conditions d'annulation</span>
            <span></span>
        </h2>

<article class="annulation">
            <p>
            <?php
                if (is_string($reservation->get("date_arrivee"))) {
                    $dateArrivee = new DateTime($reservation->get("date_arrivee"));
                } else {
                    $dateArrivee = $reservation->get("date_arrivee");
                }

                $interval = new DateInterval('P' . $logement->get("delais_prevenance") . 'D');
                $dateRemboursement = $dateArrivee->sub($interval);
                $currentDate = new DateTime("now");

                if ($currentDate >= $dateRemboursement) { ?>
                    Votre réservation est en cours ou passée. Vous ne pouvez plus vous la faire rembourser.
                <?php
                } else { ?>
                    En annulant avant le <b><?= getFormatDate($dateRemboursement) ?></b>, vous serez remboursé <b>intégralement</b>. Passée cette date, vous ne serez remboursé qu’à hauteur de 50%.
                <?php
                }
                
            ?>
            </p>
        </article>
    </div>
</section>

<?php require_once(__DIR__."/../layout/footer.php"); ?>