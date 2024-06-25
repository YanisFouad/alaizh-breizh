<?php
require_once("models/AccommodationModel.php");
require_once("models/ICalatorModel.php");
require_once("models/LogementICalatorModel.php");
require_once("services/session/UserSession.php");
require_once("services/Adresse.php");
require_once("services/fileManager/FileLogement.php");
require_once("services/ScriptLoader.php");

if (isset($_GET["key"])) {
    try {
        $apiKey = htmlspecialchars($_GET["key"]);
        $logementIdsCalendar = LogementICalatorModel::findAllById($apiKey);
        $logements = AccommodationModel::findAllById(UserSession::get()->get("id_compte"));
        $calendar = ICalatorModel::findByKey($apiKey);
    } catch (\Throwable $th) {
        http_response_code(404);
    }
} else {
    http_response_code(500);
}

if (isset($_POST)) {
    if (isset($_POST["date-debut-souscription"]) && isset($_POST["date-fin-souscription"]) && isset($_POST["logements"])) {
        try {
            $apiKey = htmlspecialchars($_GET["key"]);

            $calendar = ICalatorModel::findByKey($apiKey);
            $calendar->set("start_date", $_POST["date-debut-souscription"]);
            $calendar->set("end_date", $_POST["date-fin-souscription"]);
            $calendar->set("id_compte", UserSession::get()->get("id_compte"));
            $calendar->save();

            $logementsIcalator = LogementICalatorModel::findAllById($apiKey);
            foreach ($logementsIcalator as $key => $logement) {
                $logement = LogementICalatorModel::findOneByLogementIdAndKey($logement["id_logement"], $apiKey);
                $logement->delete();
            }

            foreach ($_POST["logements"] as $logementId) {
                $logementICalator = new LogementICalatorModel();
                $logementICalator->set("id_logement", $logementId);
                $logementICalator->set("cle_api", $calendar->get("cle_api"));
                $logementICalator->save();
            }

            header("Location: /backoffice/calendrier/succes?key=" . $apiKey . "&action=edit");
        } catch (\Throwable $th) {
            http_response_code(500);
        }
    }
}
require_once(__DIR__ . "/../layout/header.php");
ScriptLoader::load("icalator/iCalator.js");
?>

<main id="new-icalator-page">
    <div class="chemin-page-backoffice">
        <a href="/backoffice/calendrier">Mes calendriers</a>
        <span class="mdi mdi-chevron-right"></span>
        <p>Modification d'un iCal</p>
    </div>
    <h1>Modification d'un iCal</h1>
    <form action="/backoffice/calendrier/editer?key=<?= $apiKey ?>" id="form-icalator" method="post">
        <section class="date-inputs-container">
            <fieldset>
                <label for="date-debut-souscription">Date début souscription</label>
                <input type="date" id="date-debut-souscription" name="date-debut-souscription" value="<?= $calendar->get("start_date") ?>">
            </fieldset>
            <fieldset>
                <label for="date-debut-souscription">Date fin souscription</label>
                <input type="date" id="date-fin-souscription" name="date-fin-souscription" value="<?= $calendar->get("end_date") ?>">
            </fieldset>
        </section>

        <fieldset class="logements-container backoffice">
            <label for="logements">Sélection des logements</label>
            <?php

            foreach ($logements as $key => $logement) {
                if ($key % 4 == 0) {
                    if ($key != 0) {
                        echo '</div>';
                    }
                    echo '<div class="row-logement">';
                }
            ?>
                <div class="input-logement">
                    <input type="checkbox" id="logement-<?= $logement["id_logement"] ?>" name="logements[]" value="<?= $logement["id_logement"] ?>" 
                    <?php
                    if(in_array($logement["id_logement"], array_column($logementIdsCalendar, "id_logement"))){
                        echo "checked";
                    }
                    ?>
                    >
                    <label for="logement-<?= $logement["id_logement"] ?>" class="card-logement">
                        <div class="img-logement-container">
                            <img src="<?= FileLogement::get($logement["photo_logement"]) ?>" alt="Image Logement" class="img-logement">
                        </div>
                        <div class="description-logement-container">
                            <h2 class="title-logement"> <?= $logement["titre_logement"] ?></h2>
                            <p class="adresse-logement">
                                <span class="mdi mdi-map-marker"></span>
                                <?= $logement["ville_adresse"] . ", " . Adresse::getDepartement($logement["code_postal_adresse"]) ?>
                            </p>
                        </div>
                    </label>
                </div>
            <?php
            }
            ?>
        </fieldset>

        <button type="submit" class="primary backoffice">Modifier l'iCal</button>
    </form>
</main>

<?php require_once(__DIR__ . "/../../layout/footer.php"); ?>