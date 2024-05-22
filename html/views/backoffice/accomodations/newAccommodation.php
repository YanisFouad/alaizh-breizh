<?php 
    ScriptLoader::load("backoffice/accomodations/newAccommodation.js");
    require_once(__DIR__."/../layout/header.php"); 

    $layouts = ["Balcon", "Piscine", "Jacuzzi", "Jardin", "Terrasse"];
    $activities = [
        "Baignade" => true,
        "Canoë" => true,
        "Voile" => true,
        "Accrobranche" => true,
        "Randonné" => false,
        "Equitation" => false,
        "Terrasse" => false,
        "Golf" => false
    ];  
    $energiticClasses = ["A", "B", "C", "D", "E", "F", "G"];
    $types = ["Appartement", "Maison", "Villa d'exception", "Chalet", "Bateau", "Logement insolite"];
    $categories = ["Studio", "T1", "T2", "T3", "T4", "T5 et plus", "F1", "F2", "F3", "F4", "F5 et plus"];
    $distances = ["Sur place", "Moins de 5km", "Moins de 20km", "20km ou plus"];
?>

<div id="new-accommodation">
    <button class="back">
        <span class="mdi mdi-arrow-left"></span>
        Retour à la liste des logements
    </button>
    <form onsubmit="handleForm" method="POST">
        <section>
            <h2>Informations générales du logement</h2>
            <section>
                <article>
                    <label for="upload-file">
                        <span class="mdi mdi-image-plus"></span>
                    </label>
                    <span class="alert">
                        <span class="mdi mdi-alert"></span>
                        Faites attention aux photos que vous publiez
                    </span>
                    <input type="file" id="upload-file" name="photo_logement">
                </article>
                <article>
                    <div class="form-field">
                        <label for="title" class="required">Titre du logement</label>
                        <input type="text" id="title" name="titre_logement">
                    </div>
                    <div class="form-field">
                        <label for="catch_phrase" class="required">Phrase d'accroche</label>
                        <textarea id="catch_phrase" name="accroche_logement"></textarea>
                    </div>
                </article>
            </section>
            <div class="form-field description">
                <label for="description" class="required">Description du logement</label>
                <textarea id="description" name="description_logement"></textarea>
            </div>
            <div class="attributes">
                <div class="form-field">
                    <label for="category" class="required">Catégorie</label>
                    <select id="category" name="categorie_logement">
                        <option value="">choisissez</option>
                        <?php foreach($categories as $category) { ?>
                            <option value="<?=$category?>"><?=$category?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="type" class="required">Type</label>
                    <select id="type" name="type_logement">
                        <option value="">choisissez</option>
                        <?php foreach($types as $type) { ?>
                            <option value="<?=$type?>"><?=$type?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="energic_class" class="required">Classe énergitique</label>
                    <select id="energic_class" name="classe_energitique">
                        <option value="">choisissez</option>
                        <?php foreach($energiticClasses as $clazz) { ?>
                            <option value="<?=$clazz?>"><?=$clazz?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="surface" class="required">Surface (en m²)</label>
                    <input type="number" id="surface" min="0" value="0" name="surface_logement" />
                </div>
                <div class="form-field">
                    <label for="max_pp_count" class="required">Nombre de personne maximum</label>
                    <input type="number" id="max_pp_count" min="0" value="0" name="max_personne_logement" />
                </div>
                <div class="form-field">
                    <label for="single_bed_count" class="required">Nombre de lits simples</label>
                    <input type="number" id="single_bed_count" min="0" value="0" name="nb_lits_simples_logement" />
                </div>
                <div class="form-field">
                    <label for="double_bed_count" class="required">Nombre de lits doubles</label>
                    <input type="number" id="double_bed_count" min="0" value="0" name="nb_lits_doubles_logement" />
                </div>
            </div>
            <footer>
                <button type="button" class="primary backoffice">Suivant</button>
            </footer>
        </section>

        <section>
            <h2>Localisation du logement</h2>
            <h3>Adresse du logement</h3>

            <div class="form-field">
                <label for="country" class="required">Pays</label>
                <select name="pays_adresse" id="country">
                    <option>France</option>
                </select>
            </div>

            <div class="form-field">
                <label for="adresse" class="required">Adresse</label>
                <input id="adresse" name="pays_adresse">
            </div>

            <div class="form-field">
                <label for="comp_addr" class="required">Complément d'adresse</label>
                <input id="comp_addr" name="complement_adresse">
            </div>

            <div class="inline">
                <div class="form-field">
                    <label for="city" class="required">Ville</label>
                    <input id="city" name="ville_adresse">
                </div>
                <div class="form-field">
                    <label for="postal_code" class="required">Code postal</label>
                    <input id="postal_code" name="code_postal_adresse">
                </div>
            </div>

            <h3>Coordonnées GPS du logement</h3>

            <div class="inline">
                <div class="form-field">
                    <label for="lat" class="required">Latitude</label>
                    <input id="lat" name="gps_latitude_logement">
                </div>
                <div class="form-field">
                    <label for="long" class="required">Longitude</label>
                    <input id="long" name="gps_longitude_logement">
                </div>
            </div>

            <footer>
                <button type="button" class="secondary backoffice">Précédent</button>
                <button type="button" class="primary backoffice">Suivant</button>
            </footer>
        </section>

        <section>
            <h2>Aménagements et activités</h2>

            <h3>Aménagements</h3>

            <div class="layouts">
                <?php foreach($layouts as $i => $layout) { ?>
                    <div class="form-field">
                        <input type="checkbox" class="backoffice" id="layout_<?=$i?>" name="<?=$layout?>">
                        <label class="mdi mdi-check" for="layout_<?=$i?>"><?=$layout?></label>
                    </div>
                <?php } ?>
            </div>

            <h3>Activités</h3>

            <?php foreach($activities as $activity => $hasDistance) { ?>
                <div class="form-field activities">
                    <input type="checkbox" class="backoffice" id="activity_<?=$activity?>" name="<?=$activity?>">
                    <label class="mdi mdi-check" for="activity_<?=$activity?>"><?=$activity?></label>

                    <?php if($hasDistance) { ?>
                        <select>
                            <option value="">Distance</option>
                            <?php foreach($distances as $dist) { ?>
                                <option><?=$dist?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </div>
            <?php } ?>

            <footer>
                <button type="button" class="secondary backoffice">Précédent</button>
                <button type="button" class="primary backoffice">Suivant</button>
            </footer>
        </section>

        <section>
            <h2>Prix et delais</h2>

            <div class="form-field">
                <label for="price_wt" class="required">Prix HT (en €)</label>
                <input type="number" step=".5" id="prix_wt" name="prix_ht_logement">
            </div>
            <div class="price-ati">
                <span class="title">Prix TTC (10% de taxes)</span>
                <span id="price-ati">--,-- €</span>
            </div>

            <div class="form-field">
                <label for="minimal_booking_duration" class="required">Durée minimal réservation</label>
                <input type="number" id="minimal_booking_duration" name="duree_minimal_reservation">
            </div>
            <div class="form-field">
                <label for="min_booking_delai" class="required">Délai minimum de réservation</label>
                <input type="number" id="min_booking_delai" name="delais_minimum_reservation">
            </div>
            <div class="form-field">
                <label for="delais_prevenance" class="required">Delais de prevenance</label>
                <input type="number" id="delais_prevenance" name="delais_prevenance">
            </div>

            <footer>
                <button type="button" class="secondary backoffice">Précédent</button>
                <button type="button" class="primary backoffice">Créer le logement</button>
            </footer>
        </section>
    </form>
</div>

<?php require_once(__DIR__."/../../layout/footer.php"); ?>