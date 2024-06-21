<?php
    require_once(__DIR__."/../../../models/AccommodationModel.php");
    ScriptLoader::load("backoffice/accomodations/boutonActiveDesactive.js");
?>


<div id="modalValidation">
    <section id="modalValidationContent">
        <div>
            <span class="close">&times;</span>

            <h2>Confirmation</h2>

            <p id="dispo"></p>
            <div>
                <button id="boutonNon" class="backoffice primary">Non</button>
                <button id="boutonOui" class="backoffice primary">Oui</button>
                <!-- <form method="POST" action="/controllers/backoffice/accommodations/DispoLogementController.php">
                    <input type="hidden" name="estVisible" value="<?php echo $logement->get("est_visible") ?>"/>
                    <input type="submit" id="logementActuel" name="logementActuel" value="<?php echo $logement->get("id_logement");?>"/>
                </form> -->
            </div>
        </div>
    </section>
</div>
