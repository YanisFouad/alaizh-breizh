<?php


if (!isset($_GET) || $_GET["key"] == null) {
    header("Location: /backoffice/calendrier");
}

require_once(__DIR__ . "/../layout/header.php");
$api_key = $_GET["key"];
ScriptLoader::load("icalator/calendarLink.js");
?>

<main id="icalator-success-page">
    <div class="chemin-page-backoffice">
        <a href="/backoffice/calendrier">Mes calendriers</a>
        <span class="mdi mdi-chevron-right"></span>
        <p>Création d'un iCal</p>
    </div>
    <h1>Création d'un iCal</h1>
    <section class="success-container">
        <div class="icalator-success-title">
            <span class="mdi mdi-check"></span>
            <h2>Succès</h2>
        </div>
        <div class="success-description">
            <p>Votre calendrier a été créé avec succès</p>
            <p>Vous pouvez maintenant l'utiliser pour synchroniser vos réservations dans votre agenda numérique</p>
            <p><strong>Votre URL de calendrier est : <a href="<?= $_SERVER["HTTP_HOST"] . '/icalator?key=' . $api_key ?>" class="calendar-link"><?= $_SERVER["HTTP_HOST"] . '/icalator?key=' . $api_key ?></a></strong></p>
        </div>
    </section>
</main>

<?php
require_once(__DIR__ . "/../../layout/footer.php");
?>