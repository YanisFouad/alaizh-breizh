<?php 
    ScriptLoader::load("backoffice/accomodations/newAccommodation.js");
    require_once(__DIR__."/../layout/header.php"); 

    $layouts = ["balcon", "piscine", "jacuzzi", "jardin", "terrasse"];
    $activities = [
        "baignade" => true,
        "canoë" => true,
        "voile" => true,
        "accrobranche" => true,
        "randonnée" => true,
        "equitation" => true,
        "golf" => true
    ];  
    $energiticClasses = ["A", "B", "C", "D", "E", "F", "G"];
    $types = ["appartement", "maison", "villa", "chalet", "bateau", "insolite"];
    $categories = ["Studio", "T1", "T2", "T3", "T4", "T5 et plus", "F1", "F2", "F3", "F4", "F5 et plus"];
    $distances = ["Sur place", "Moins de 5km", "Moins de 20km", "20km ou plus"];

    
?>

<div id="new-accommodation">
    <a href="/backoffice">
        <button class="back">
            <span class="mdi mdi-arrow-left"></span>
            Retour à la liste des logements
        </button>
    </a>
    <form id="new-accommodation-form" onsubmit="handleForm(event)" method="POST">
        <section>
            <h2>Informations générales du logement</h2>
            <section>
                <article>
                    <label for="photo_logement">
                        <span class="mdi mdi-image-plus"></span>
                    </label>
                    <span class="alert">
                        <span class="mdi mdi-alert"></span>
                        Faites attention aux photos que vous publiez
                    </span>
                    <input type="file" id="photo_logement" name="photo_logement">
                </article>
                <article>
                    <div class="form-field">
                        <label for="titre_logement" class="required">Titre du logement</label>
                        <input type="text" id="titre_logement" name="titre_logement">
                    </div>
                    <div class="form-field">
                        <label for="accroche_logement" class="required">Phrase d'accroche</label>
                        <textarea id="accroche_logement" name="accroche_logement"></textarea>
                    </div>
                </article>
            </section>
            <div class="form-field description">
                <label for="description_logement" class="required">Description du logement</label>
                <textarea id="description_logement" name="description_logement"></textarea>
            </div>
            <div class="attributes">
                <div class="form-field">
                    <label for="categorie_logement" class="required">Catégorie</label>
                    <select id="categorie_logement" name="categorie_logement">
                        <option value="">Choisissez</option>
                        <?php foreach($categories as $category) { ?>
                            <option value="<?=$category?>"><?=$category?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="type_logement" class="required">Type</label>
                    <select id="type_logement" name="type_logement">
                        <option value="">Choisissez</option>
                        <?php foreach($types as $type) { ?>
                            <option value="<?=$type?>"><?=$type?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="classe_energetique" class="required">Classe énergétique</label>
                    <select id="classe_energetique" name="classe_energetique">
                        <option value="">Choisissez</option>
                        <?php foreach($energiticClasses as $clazz) { ?>
                            <option value="<?=$clazz?>"><?=$clazz?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="surface_logement" class="required">Surface (en m²)</label>
                    <input type="number" id="surface_logement" min="9" value="9" name="surface_logement" />
                </div>
                <div class="form-field">
                    <label for="max_personne_logement" class="required">Nombre de personnes maximum</label>
                    <input type="number" id="max_personne_logement" min="1" value="1" name="max_personne_logement" />
                </div>
                <div class="form-field">
                    <label for="nb_lits_simples_logement" class="required">Nombre de lits simples</label>
                    <input type="number" id="nb_lits_simples_logement" min="0" value="0" name="nb_lits_simples_logement" />
                </div>
                <div class="form-field">
                    <label for="nb_lits_doubles_logement" class="required">Nombre de lits doubles</label>
                    <input type="number" id="nb_lits_doubles_logement" min="0" value="0" name="nb_lits_doubles_logement" />
                </div>
            </div>
            <footer>
                <button type="button" class="primary next backoffice">Suivant</button>
            </footer>
        </section>

        <section>
            <h2>Localisation du logement</h2>
            <h3>Adresse du logement</h3>

            <div class="form-field">
                <label for="pays_adresse" class="required">Pays</label>
                <select name="pays_adresse" id="pays_adresse">
                    <option>France</option>
                </select>
            </div>

            <div class="inline">
                <div class="form-field address-number">
                    <label for="numero" class="required">Numéro</label>
                    <input min="1" value="1" type="number" id="numero" name="numero">
                </div>
                <div class="form-field address-name">
                    <label for="rue_adresse" class="required">Nom de la rue</label>
                    <input type="text" id="rue_adresse" name="rue_adresse">
                </div>
            </div>

            <div class="form-field">
                <label for="complement_adresse">Complément d'adresse</label>
                <input type="text" id="complement_adresse" name="complement_adresse">
            </div>

            <div class="inline">
                <div class="form-field">
                    <label for="ville_adresse" class="required">Ville</label>
                    <input type="text" id="ville_adresse" name="ville_adresse">
                </div>
                <div class="form-field">
                    <label for="code_postal_adresse" class="required">Code postal</label>
                    <input type="text" id="code_postal_adresse" name="code_postal_adresse">
                </div>
            </div>

            <h3>Coordonnées GPS du logement</h3>

            <div class="inline">
                <div class="form-field">
                    <label for="gps_latitude_logement" class="required">Latitude</label>
                    <input type="text" id="gps_latitude_logement" name="gps_latitude_logement">
                </div>
                <div class="form-field">
                    <label for="gps_longitude_logement" class="required">Longitude</label>
                    <input type="text" id="gps_longitude_logement" name="gps_longitude_logement">
                </div>
            </div>

            <footer>
                <button type="button" class="secondary previous backoffice">Précédent</button>
                <button type="button" class="primary next backoffice">Suivant</button>
            </footer>
        </section>

        <section>
            <h2>Aménagements et activités</h2>

            <h3>Aménagements</h3>

            <div class="layouts">
                <?php foreach($layouts as $i => $layout) { ?>
                    <div class="form-field">
                        <input name="layout_<?=$layout?>" type="checkbox" class="backoffice" id="layout_<?=$i?>" name="<?=$layout?>">
                        <label class="mdi mdi-check" for="layout_<?=$i?>"><?=$layout?></label>
                    </div>
                <?php } ?>
            </div>

            <h3>Activités</h3>

            <?php foreach($activities as $activity => $hasDistance) { ?>
                <div class="form-field activities">
                    <input name="activity_<?=$activity?>" type="checkbox" class="backoffice" id="activity_<?=$activity?>" name="<?=$activity?>">
                    <label class="mdi mdi-check" for="activity_<?=$activity?>"><?=$activity?></label>

                    <?php if($hasDistance) { ?>
                        <select name="distance_for_<?=$activity?>" id="distance_for_<?=$activity?>" class="distance">
                            <?php foreach($distances as $dist) { ?>
                                <option value="<?=$dist?>"><?=$dist?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </div>
            <?php } ?>

            <footer>
                <button type="button" class="secondary previous backoffice">Précédent</button>
                <button type="button" class="primary next backoffice">Suivant</button>
            </footer>
        </section>

        <section>
            <h2>Prix et délais</h2>

            <div class="form-field">
                <label for="prix_ht_logement" class="required">Prix HT (en €)</label>
                <input type="number" step=".5" id="prix_ht_logement" name="prix_ht_logement" min="0.50">
            </div>
            <div class="price-ati">
                <span class="title">Prix TTC (10% de taxes)</span>
                <span id="price-ati">--,-- €</span>
            </div>

            <div class="form-field">
                <label for="duree_minimale_reservation" class="required">Durée minimale de réservation</label>
                <input type="number" id="duree_minimale_reservation" name="duree_minimale_reservation" min="1">
            </div>
            <div class="form-field">
                <label for="delais_minimum_reservation" class="required">Délai minimum de réservation</label>
                <input type="number" id="delais_minimum_reservation" name="delais_minimum_reservation" min="1">
            </div>
            <div class="form-field">
                <label for="delais_prevenance" class="required">Délai de prévenance</label>
                <input type="number" id="delais_prevenance" name="delais_prevenance" min="1">
            </div>

            <div class="error-message"></div>
            <footer>
                <button type="button" class="secondary previous backoffice">Précédent</button>
                <button type="button" id="preview" class="primary backoffice">Prévisualiser</button>
                <button type="submit" class="primary backoffice">Créer le logement</button>
            </footer>
        </section>
    </form>
</div>

<?php require_once(__DIR__."/../../layout/footer.php"); ?>