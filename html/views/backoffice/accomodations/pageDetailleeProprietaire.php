<?php 
    require_once(__DIR__."/../../../models/AccommodationModel.php");
    $logement_id = $_GET['id_logement'] ?? null;
    if(!isset($logement_id))
        exit(header("Location: /"));
    $logement = AccommodationModel::findOneById($logement_id);
    $tabActivites = [
        "voile" => "mdi mdi-sail-boat",
        "accrobranche" => "mdi mdi-pine-tree-variant",
        "golf" => "mdi mdi-golf",
        "canoë" => "mdi mdi-kayaking",
        "randonnée" => "mdi mdi-hiking",
        "baignade" => "mdi mdi-umbrella",
        "équitation" => "mdi mdi-horse-human"
    ];
    $tabAmenagements = [
        "jardin" => "mdi mdi-tree-outline",
        "jacuzzi" => "mdi mdi-hot-tub",
        "piscine" => "mdi mdi-pool",
        "balcon" => "mdi mdi-balcony",
        "terrase" => "mdi mdi-land-plots"
    ];

    require_once(__DIR__."/../layout/header.php");
?>
    <main id ="mainProprietaireLogement">
        <div id="cheminPage">
            <button><span class="mdi mdi-arrow-left"></span>Retour</button>
            <a href="#Liste">Logements</a>
            <span class="mdi mdi-chevron-right"></span>
            <h4><?=$logement->get('titre_logement');?></h4>
        </div>
       
        <div id="page">
            <section>
                <article id="blockIntro">
                    <img src="<?=$logement->get("photo_logement")?>" id="imgLogement">

                    <div>
                        <div id="titre">
                            <h1><?=$logement->get('titre_logement');?></h1>
                        </div>

                        <h2><span class="mdi mdi-map-marker"></span>Locmariaquer, Morbihan</h2><!--A remplir quand on aura la base de communes et departements-->
                        <p><?=$logement->get('accroche_logement')?></p>

                        <div id="caracteristiques-logement">
                            <ul>
                                <li>
                                    <span class="mdi mdi-tag-multiple-outline"></span>
                                    Categorie
                                </li>
                                <li class="bulle-Rose">
                                    <?=$logement->get('categorie_logement')?>
                                </li>

                                <li>
                                    <span class="mdi mdi-tag-text-outline"></span>
                                    Type
                                </li>
                                <li class="bulle-Rose">
                                    <?=$logement->get('type_logement')?>
                                </li>

                                <li>
                                    <span class="mdi mdi-texture-box"></span>
                                    Surface
                                </li>
                                <li class="bulle-Rose">
                                    <?=$logement->get('surface_logement')?>m²
                                </li>

                                <li>
                                    <span class="mdi mdi-bed-outline"></span>
                                    Nombre de lits simples
                                </li>
                                <li class="bulle-Rose">
                                    <?=$logement->get('nb_lits_simples_logement')?>
                                </li>

                                <li>
                                    <span class="mdi mdi-bunk-bed-outline"></span>
                                    Nombre de lits doubles
                                </li>
                                <li class="bulle-Rose">
                                    <?=$logement->get('nb_lits_doubles_logement')?>
                                </li>

                                <li>
                                    <span class="mdi mdi-account-group-outline"></span>
                                    Nombre de personnes max
                                </li>
                                <li class="bulle-Rose">
                                    <?=$logement->get('max_personne_logement')?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </article>

                <article id="description">
                    <h3>Description</h3>
                    <p>
                        <?=$logement->get('description_logement')?>
                    </p>

                    <div>
                        <?php if($logement->get('classe_energetique')){?>
                            <h3>Classe energetique</h3>
                            <img src="../../../images/labels/energyLabel<?=$logement->get('classe_energetique');?>.png">
                        <?php } ?>
                    </div>
                </article>

                <article id="box-Activites-Amenagement">
                    <div>
                        <div>
                            <h3>Activités</h3>
                            <ul>
                                <?php foreach ($logement->get('activites') as $key => $value) { ?>
                                    <li><span class="<?=$tabActivites[strtolower($value['name'])];?>"></span> <?= ucfirst($value['name']);?> - <?= $value['perimetre'];?></li>
                                <?php }?>
                                
                            </ul>
                        </div>

                        <div>
                            <h3>Aménagements</h3>
                            <ul>
                                <?php foreach ($logement->get('amenagements') as $key => $value) { ?>
                                    <li><span class="<?=$tabAmenagements[strtolower($value['name'])];?>"></span> <?= ucfirst($value['name']);?></li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>

                </article>
                <div id="modified-button">
                    <button type="button" class ="primary backoffice" >
                        Modifier
                    </button>
                </div>
            </section>
            
        </div>
    </main>

<?php require_once(__DIR__."/../../layout/footer.php") ?>