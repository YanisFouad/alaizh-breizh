<?php

require_once("models/ICalatorModel.php");
require_once("models/LogementICalatorModel.php");
require_once("models/AccommodationModel.php");
require_once("services/Adresse.php");


$key = $_GET["key"];
try {
    $calendar = ICalatorModel::findByKey($key);
    $logementsIds = LogementICalatorModel::findAllById($key);
    $logements = [];

    foreach ($logementsIds as $logementId) {
        $logements[] = AccommodationModel::findOneById($logementId["id_logement"]);
    }
} catch (\Throwable $th) {
    if (!$calendar || !$logementsIds || !$logements) {
        header("Location: /backoffice/calendrier?notification-message=Impossible de récupérer votre agenda&notification-type=ERROR");
    }
}
require_once(__DIR__ . "/../layout/header.php");
ScriptLoader::load("icalator/calendarLink.js");

?>

<main id="read-calendar-page">
    <div class="chemin-page-backoffice">
        <a href="/backoffice/calendrier">Mes calendriers</a>
        <span class="mdi mdi-chevron-right"></span>
        <p>Mon calendrier</p>
    </div>
    <h1>Mon calendrier</h1>
    <section>
        <div class="calendar-informations">
            <div>
                <h2>Date de début :</h2>
                <p><?= date('d/m/Y', strtotime($calendar->get("start_date"))) ?></p>
            </div>
            <div>
                <h2>Date de fin :</h2>
                <p><?= date('d/m/Y', strtotime($calendar->get("end_date"))) ?></p>
            </div>
        </div>
        <div class="url-informations">
            <h2>URL :</h2>
            <p><a href="https://<?= $_SERVER['HTTP_HOST'] . '/icalator?key' . $calendar->get("cle_api") ?>" class="calendar-link">https://<?= $_SERVER['HTTP_HOST'] . '/icalator?key=' . $calendar->get("cle_api") ?></a></p>
        </div>
        <div>
            <div class="logements-container backoffice">
                <h2>Mes logements</h2>
                <?php
                foreach ($logements as $key => $logement) {
                    if ($key % 4 == 0) {
                        if ($key != 0) {
                            echo '</div>';
                        }
                        echo '<div class="row-logement">';
                    }
                ?>

                    <a href="<?= "/backoffice/logement/?id_logement=" . $logement->get("id_logement") ?>" class="link-logement">
                        <article class="card-logement">
                            <div class="img-logement-container">
                                <img src="<?= $logement->get("photo_logement") ?>" alt="Image Logement" class="img-logement">
                            </div>
                            <div class="description-logement-container">
                                <h2 class="title-logement"> <?= $logement->get("titre_logement") ?></h2>
                                <p class="adresse-logement">
                                    <span class="mdi mdi-map-marker"></span>
                                    <?= $logement->get("ville_adresse") . ", " . Adresse::getDepartement($logement->get("code_postal_adresse")) ?>
                                </p>
                            </div>
                        </article>
                    </a>
                <?php
                }
                echo '</div>';
                ?>
            </div>
        </div>
    </section>
</main>

<?php require_once(__DIR__ . "/../../layout/footer.php"); ?>