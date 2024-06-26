<?php 
    $id_logement = $_GET['id_logement'] ?? null;

    if(!isset($id_logement))
        exit(header("Location: /backoffice"));
    
    ScriptLoader::load("backoffice/accomodations/modificationAccomodation.js");
    
    require_once(__DIR__."/../layout/header.php"); 
    require_once(__DIR__."/../../../models/AccommodationModel.php");
    require_once(__DIR__."/../../../services/RequestBuilder.php");

    $logement = AccommodationModel::findOneById($id_logement);
    $logement_layouts = [];
    $logement_activites = [];

    //ajout des aménagements du logement
    foreach($logement->get('amenagements') as $key => $value) {
        if($value["name"])
            array_push($logement_layouts,strtolower($value["name"]));
    }

    //ajout des activites
    foreach($logement->get('activites') as $key => $value) {
        if($value["name"])
            array_push($logement_activites,strtolower($value["name"]));
    }

    $layouts = ["Balcon", "Piscine", "Jacuzzi", "Jardin", "Terrasse"];
    $activities = [
        "baignade" => true,
        "canoë" => true,
        "voile" => true,
        "accrobranche" => true,
        "randonnée" => true,
        "équitation" => true,
        "golf" => true
    ];  
    $energiticClasses = ["A", "B", "C", "D", "E", "F", "G"];
    $categories = ["Appartement", "Maison", "Villa", "Chalet", "Bateau", "Insolite"];
    $types = ["Studio", "T1", "T2", "T3", "T4", "T5 et plus", "F1", "F2", "F3", "F4", "F5 et plus"];
    $distances = ["Sur place", "Moins de 5km", "Moins de 20km", "20km ou plus"];

    // Fonction renvoie la distance de l'activité
    function distanceActivite($nom_activite,$liste_activite) {
        foreach ($liste_activite as $activity => $distance) {
            if (strtolower($activity) === $nom_activite) {
                return $distance;
            }
        }
    }

?>

<div id="modification-logement">
    <a href="/backoffice/logement?id_logement=<?php echo $logement->get('id_logement')?>">
        <button class="back">
            <span class="mdi mdi-arrow-left"></span>
            Retour à la liste des logements
        </button>
    </a>
    <form onsubmit="handleForm(event)" method="POST">
        <input type="hidden" id="id_logement" name="id_logement" value="<?=$id_logement?>">
        <section>
            <h2>Informations générales du logement</h2>
            <section>
                <article>
                    <div id="conteneur_image">
                        <label id="image_logement" for="photo_logement" data-image="<?php echo $logement->get("photo_logement") ?>">
                            <span class="mdi mdi-plus-circle"></span>
                        </label>
                    </div>
                    <span class="alert">
                        <span class="mdi mdi-alert"></span>
                        Faites attention aux photos que vous publiez
                    </span>
                    <input type="file" id="photo_logement" name="photo_logement">
                </article>
                <article>
                    <div class="form-field">
                        <label for="titre_logement" class="required">Titre du logement</label>
                        <input type="text" id="titre_logement" name="titre_logement" value="<?echo $logement->get('titre_logement');?>">
                    </div>
                    <div class="form-field">
                        <label for="accroche_logement" class="required">Phrase d'accroche</label>
                        <textarea id="accroche_logement" name="accroche_logement"><?php echo $logement->get('accroche_logement');?></textarea>
                    </div>
                </article>
            </section>
            <div class="form-field description">
                <label for="description_logement" class="required">Description du logement</label>
                <textarea id="description_logement" name="description_logement"><?echo $logement->get('description_logement');?></textarea>
            </div>
            <div class="attributes">
                <div class="form-field">
                    <label for="categorie_logement" class="required">Catégorie</label>
                    <select id="categorie_logement" name="categorie_logement">
                        <?php foreach($categories as $category) {?>
                            <option value="<?=$category?>" <?php if (strcasecmp($category,$logement->get('categorie_logement')) == 0) echo 'selected'; ?>>
                                    <?=$category?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="type_logement" class="required">Type</label>
                    <select id="type_logement" name="type_logement">
                        <?php foreach($types as $type) { ?>
                            <option value="<?=$type?>" <?php if (strcasecmp($type,$logement->get('type_logement')) == 0) echo 'selected'; ?>>
                                    <?=$type?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="classe_energetique" class="required">Classe énergitique</label>
                    <select id="classe_energetique" name="classe_energetique">
                        <?php foreach($energiticClasses as $clazz) { ?>
                            <option value="<?=$clazz?>" <?php if (strcasecmp($clazz,$logement->get('classe_energetique')) == 0) echo 'selected'; ?>>
                                    <?=$clazz?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-field">
                    <label for="surface_logement" class="required">Surface (en m²)</label>
                    <input type="number" id="surface_logement" min="9" value="<?echo htmlspecialchars($logement->get('surface_logement'), ENT_QUOTES, 'UTF-8');?>" name="surface_logement" />
                </div>
                <div class="form-field">
                    <label for="max_personne_logement" class="required">Nombre de personne maximum</label>
                    <input type="number" id="max_personne_logement" min="1" value="<?echo $logement->get('max_personne_logement');?>" name="max_personne_logement" />
                </div>
                <div class="form-field">
                    <label for="nb_lits_simples_logement" class="required">Nombre de lits simples</label>
                    <input type="number" id="nb_lits_simples_logement" min="0" value="<?echo $logement->get('nb_lits_simples_logement');?>" name="nb_lits_simples_logement" />
                </div>
                <div class="form-field">
                    <label for="nb_lits_doubles_logement" class="required">Nombre de lits doubles</label>
                    <input type="number" id="nb_lits_doubles_logement" min="0" value="<?echo $logement->get('nb_lits_doubles_logement');?>" name="nb_lits_doubles_logement" />
                </div>
            </div>
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
                    <label for="numero" class="required">Numero</label>
                    <input min="1" value="<?echo $logement->get('numero');?>" type="number" id="numero" name="numero">
                </div>
                <div class="form-field address-name">
                    <label for="rue_adresse" class="required">Nom de la rue</label>
                    <input type="text" value="<?echo $logement->get('rue_adresse');?>" id="rue_adresse" name="rue_adresse">
                </div>
            </div>

            <div class="form-field">
                <label for="complement_adresse">Complément d'adresse</label>
                <input type="text" value="<?echo $logement->get('complement_adresse');?>" id="complement_adresse" name="complement_adresse">
            </div>

            <div class="inline">
                <div class="form-field">
                    <label for="ville_adresse" class="required">Ville</label>
                    <input type="text" value="<?echo $logement->get('ville_adresse');?>" id="ville_adresse" name="ville_adresse">
                </div>
                <div class="form-field">
                    <label for="code_postal_adresse" class="required">Code postal</label>
                    <input type="text" value="<?echo $logement->get('code_postal_adresse');?>" id="code_postal_adresse" name="code_postal_adresse">
                </div>
            </div>

            <h3>Coordonnées GPS du logement</h3>

            <div class="inline">
                <div class="form-field">
                    <label for="gps_latitude_logement" class="required">Latitude</label>
                    <input type="text" value="<?echo $logement->get('gps_latitude_logement');?>" id="gps_latitude_logement" name="gps_latitude_logement">
                </div>
                <div class="form-field">
                    <label for="gps_longitude_logement" class="required">Longitude</label>
                    <input type="text" value="<?echo $logement->get('gps_longitude_logement');?>" id="gps_longitude_logement" name="gps_longitude_logement">
                </div>
            </div>
        </section>

        <section>
            <h2>Aménagements et activités</h2>

            <h3>Aménagements</h3>

            <div class="layouts">
                <?php foreach($layouts as $i => $layout) { ?>
                    <div class="form-field">
                        <input 
                            name="layout_<?=$layout?>" 
                            type="checkbox" 
                            class="backoffice" 
                            id="layout_<?=$i?>" 
                            name="<?=$layout?>" 
                            <?php if (in_array(strtolower($layout),$logement_layouts)) echo 'checked'; ?>
                        >
                        <label class="mdi mdi-check" for="layout_<?=$i?>"><?php echo $layout?></label>
                    </div>
                <?php } ?>
            </div>

            <h3>Activités</h3>

            <?php foreach($activities as $activity => $hasDistance) { ?>
                <div class="form-field activities">
                    <input 
                        name="activity_<?=$activity?>" 
                        type="checkbox" 
                        class="backoffice" 
                        id="activity_<?=$activity?>" 
                        name="<?=$activity?>" 
                        <?php if (in_array(strtolower($activity),$logement_activites)) echo 'checked'; ?>
                        >
                    <label class="mdi mdi-check" for="activity_<?=$activity?>"><?=$activity?></label>

                    <?php if($hasDistance) { ?> 
                        <!-- REVOIR LE HOVER -->
                        <select name="distance_for_<?=$activity?>" id="distance_for_<?=$activity?>" class="distance">
                        <?php foreach($distances as $dist) { ?>
                                <option value="<?=$dist?>" <?php if ($dist==distanceActivite($activity,$activities)) echo 'selected'; ?>><?=$dist?></option>
                                <?php } ?>
                        </select>
                    <?php } ?>
                </div>
            <?php } ?>
        </section>

        <section>
            <h2>Prix et delais</h2>

            <div class="form-field">
                <label for="prix_ht_logement" class="required">Prix HT (en €)</label>
                <input type="number" value="<?echo $logement->get('prix_ht_logement');?>" step=".5" id="prix_ht_logement" name="prix_ht_logement">
            </div>
            <div class="price-ati">
                <span class="title">Prix TTC (10% de taxes)</span>
                <span id="price-ati"><?php echo $logement->get('prix_ht_logement') + $logement->get('prix_ht_logement')*0.10 . "€" ?></span>
            </div>

            <div class="form-field">
                <label for="duree_minimale_reservation" class="required">Durée minimal réservation</label>
                <input type="number" value="<?echo $logement->get('duree_minimale_reservation');?>" id="duree_minimale_reservation" name="duree_minimale_reservation">
            </div>
            <div class="form-field">
                <label for="delais_minimum_reservation" class="required">Délai minimum de réservation</label>
                <input type="number" value="<?echo $logement->get('delais_minimum_reservation');?>" id="delais_minimum_reservation" name="delais_minimum_reservation">
            </div>
            <div class="form-field">
                <label for="delais_prevenance" class="required">Delais de prevenance</label>
                <input type="number" value="<?echo $logement->get('delais_prevenance');?>" id="delais_prevenance" name="delais_prevenance">
            </div>

            <div class="error-message"></div>
            <footer>
                <a href="/backoffice/logement?id_logement=<?php echo $logement->get('id_logement')?>">
                    <button type="button" class="secondary previous backoffice">Annuler</button>
                </a>
                <button type="submit" class="primary backoffice">Modifier le logement</button>
            </footer>
        </section>
    </form>
</div>
<?php require_once(__DIR__."/../../layout/footer.php") ?>