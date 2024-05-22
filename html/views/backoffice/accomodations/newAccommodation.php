<?php 
    ScriptLoader::load("backoffice/accomodations/newAccommodation.js");
    require_once(__DIR__."/../layout/header.php"); 
?>

<div id="new-accommodation">
    <button>
        <span class="mdi mdi-arrow-left"></span>
        Retour à la liste des logements
    </button>
    <form>
        <h2>Informations générales du logement</h2>
        <div>
            <div>
                <label for="upload-file">
                    <span class="mdi mdi-image-plus"></span>
                </label>
                <span class="alert">
                    <span class="mdi mdi-alert"></span>
                    Faites attention aux photos que vous publiez
                </span>
                <input type="file" id="upload-file" name="photo_logement" />
            </div>
            <div>
                <div class="form-field">
                    <label for="title" class="required">Titre du logement</label>
                    <input type="text" id="title" name="titre_logement" />
                </div>
                <div class="form-field">
                    <label for="catch_phrase" class="required">Phrase d'accroche</label>
                    <textarea id="catch_phrase" name="accroche_logement"></textarea>
                </div>
            </div>
        </div>
        <div class="form-field">
            <label for="description" class="required">Description du logement</label>
            <textarea id="description" name="description_logement"></textarea>
        </div>
        <div class="attrs">
            <div class="form-field">
                <label for="category" class="required">Catégorie</label>
                <select id="category" name="categorie_logement">
                    <option value="">choisissez</option>
                </select>
            </div>
            <div class="form-field">
                <label for="type" class="required">Type</label>
                <select id="type" name="type_logement">
                    <option value="">choisissez</option>
                </select>
            </div>
            <div class="form-field">
                <label for="energic_class" class="required">Classe énergitique</label>
                <select id="energic_class" name="classe_energitique">
                    <option value="">choisissez</option>
                </select>
            </div>
            <div class="form-field">
                <label for="surface" class="required">Surface (en m²)</label>
                <input type="number" id="surface" name="surface_logement" />
            </div>
            <div class="form-field">
                <label for="max_pp_count" class="required">Surface (en m²)</label>
                <input type="number" id="max_pp_count" name="max_personne_logement" />
            </div>
            <div class="form-field">
                <label for="single_bed_count" class="required">Surface (en m²)</label>
                <input type="number" id="single_bed_count" name="nb_lits_simples_logement" />
            </div>
            <div class="form-field">
                <label for="double_bed_count" class="required">Surface (en m²)</label>
                <input type="number" id="double_bed_count" name="nb_lits_doubles_logement" />
            </div>
        </div>
        <div class="action">
            <button class="secondary backoffice" disabled>Précédent</button>
            <button class="primary backoffice">Suivant</button>
        </div>
    </form>
</div>

<?php require_once(__DIR__."/../../layout/footer.php"); ?>