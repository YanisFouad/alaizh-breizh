<?php
    require_once(__DIR__."/../../../models/AccommodationModel.php");
    ScriptLoader::load("backoffice/acccommodations/boutonActiveDesactive.js");
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
            </div>
        </div>
    </section>
</div>
