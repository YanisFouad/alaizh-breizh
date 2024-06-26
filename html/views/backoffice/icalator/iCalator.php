<?php
require_once("models/AccommodationModel.php");
require_once("models/ICalatorModel.php");
require_once("models/LogementICalatorModel.php");
require_once("services/session/UserSession.php");
require_once("services/Adresse.php");
require_once("services/fileManager/FileLogement.php");
require_once("services/ScriptLoader.php");
require_once("helpers/apiUtils.php");

$logements = AccommodationModel::findAllById(UserSession::get()->get("id_compte"));
ScriptLoader::load("icalator/iCalator.js");

if ($_POST) {
    try {
        $icalator = new ICalatorModel();

        $icalator->set("cle_api", generate_api_key());
        $icalator->set("start_date", date("Y-m-d", strtotime(htmlspecialchars($_POST["date-debut-souscription"]))));
        $icalator->set("end_date", date("Y-m-d", strtotime(htmlspecialchars($_POST["date-fin-souscription"]))));
        $icalator->set("id_compte", UserSession::get()->get("id_compte"));
        $icalator->save();
        
        $logements = $_POST["logements"];

        foreach ($logements as $logement) {
            $logementICalator = new LogementICalatorModel();
            $logementICalator->set("id_logement", $logement);
            $logementICalator->set("cle_api", $icalator->get("cle_api"));
            $logementICalator->save();
        }
        
        header("Location: /backoffice/calendrier/succes?key=" . $icalator->get("cle_api"));
    } catch (\Throwable $th) {
        http_response_code(500);
    }
} else {

    require_once(__DIR__ . "/../layout/header.php");
?>

    <main id="new-icalator-page">
        <div class="chemin-page-backoffice">
            <a href="/backoffice/calendrier">Mes calendriers</a>
            <span class="mdi mdi-chevron-right"></span>
            <p>Création d'un iCal</p>
        </div>
        <h1>Création d'un iCal</h1>
        <form action="/backoffice/calendrier/nouveau" id="form-icalator" method="post">
            <section class="date-inputs-container">
                <fieldset>
                    <label for="date-debut-souscription">Date début souscription</label>
                    <input type="date" id="date-debut-souscription" name="date-debut-souscription">
                </fieldset>
                <fieldset>
                    <label for="date-debut-souscription">Date fin souscription</label>
                    <input type="date" id="date-fin-souscription" name="date-fin-souscription">
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
                        <input type="checkbox" id="logement-<?= $logement["id_logement"] ?>" name="logements[]" value="<?= $logement["id_logement"] ?>">
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

            <button type="submit" class="primary backoffice">Générer l'iCal</button>
        </form>
    </main>

<?php require_once(__DIR__ . "/../../layout/footer.php");
}
