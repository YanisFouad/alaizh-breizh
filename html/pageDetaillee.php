<?php 
    require_once(__DIR__."/models/AccommodationModel.php");
    $accomodation = AccommodationModel::findOneById(9);

    // s'il n'est pas trouvé
    if(!isset($accomodation)) {
        echo "logement avec l'id machin introuvable";
    }

    $amenagements = array();


    while($accomodation has $accomodation->get("amenagement_$i") != null){
        $amenagements[$i] = $accomodation->get("amenagement_$i");
    }

    $tabActivites = [
        "voile" => "mdi mdi-sail-boat",
        "accrobranche" => "mdi mdi-pine-tree-variant",
        "golf" => "mdi mdi-golf",
        "canoë" => "mdi mdi-kayaking",
        "randonnée" => "mdi mdi-hiking",
        "baignade" => "mdi mdi-umbrella",
        "équitation" => "mdi mdi-horse-human"
    ];
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/main.css">
    <link rel="stylesheet" href="./assets/css/materialdesignicons.min.css">
    <title>Page détaillée d'un logement</title>
</head>

<body>
    <main id="pageDetaille">
        <div>
            <button><span class="mdi mdi-arrow-left"></span>Retour</button>
            <div id="cheminPage">
                <a href="#Liste">Logements</a>
                <span class="mdi mdi-chevron-right"></span>
                <h4><?php echo $accomodation->get("titre_logement");?></h4>
            </div>
        </div>

        <div id="page">
            <section>
                <article id="blockIntro">
                    <img src="./images/rsz_1frames-for-your-heart-2d4laqalbda-unsplash.jpg" id="imgLogement">

                    <div>
                        <div id="titre">
                            <h1><?php echo $accomodation->get("titre_logement");?></h1>
                        </div>

                        <h2><span class="mdi mdi-map-marker"></span>Locmariaquer, Morbihan</h2>
                        <p><?php echo $accomodation->get("description_logement");?>
                        </p>

                        <div id="caracteristiques-logement">
                            <ul>
                                <li>
                                    <span class="mdi mdi-tag-multiple-outline"></span>
                                    Categorie
                                </li>
                                <li class="bulle-Rose" title="<?php echo $accomodation->get("categorie_logement");?>"><?php echo $accomodation->get("categorie_logement");?></li>

                                <li>
                                    <span class="mdi mdi-tag-text-outline"></span>
                                    Type
                                </li>
                                <li class="bulle-Rose" title="<?php echo $accomodation->get("type_logement");?>"><?php echo $accomodation->get("type_logement");?></li>

                                <li>
                                    <span class="mdi mdi-texture-box"></span>
                                    Surface
                                </li>
                                <li class="bulle-Rose" title="<?php echo $accomodation->get("surface_logement");?>"><?php echo $accomodation->get("surface_logement");?>m²</li>

                                <li>
                                    <span class="mdi mdi-bed-outline"></span>
                                    Nombre de lits simples
                                </li>
                                <li class="bulle-Rose" title="<?php echo $accomodation->get("nb_lits_simples_logement");?>"><?php echo $accomodation->get("nb_lits_simples_logement");?></li>

                                <li>
                                    <span class="mdi mdi-bunk-bed-outline"></span>
                                    Nombre de lits doubles
                                </li>
                                <li class="bulle-Rose" title="<?php echo $accomodation->get("nb_lits_doubles_logement");?>"><?php echo $accomodation->get("nb_lits_doubles_logement");?></li>

                                <li>
                                    <span class="mdi mdi-account-group-outline"></span>
                                    Nombre de personnes max
                                </li>
                                <li class="bulle-Rose" title="<?php echo $accomodation->get("max_personne_logement");?>"><?php echo $accomodation->get("max_personne_logement");?></li>
                            </ul>
                        </div>
                    </div>
                </article>

                <article id="description">
                    <h3>Description</h3>
                    <p><?php echo $accomodation->get("description_logement");?></p>
                </article>

                <article id="box-Activites-Amenagement">
                    <div>
                        <div>
                            <h3>Activités</h3>
                            <ul>
                                <li>
                                    <span class="<?php 
                                    for
                                    $activite = $accomodation->get("activite_1");
                                    echo $tabActivites[$activite]?>"></span>
                                    <?php echo $activite;?>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3>Aménagements</h3>
                            <ul>
                                <li><span class="mdi mdi-tree-outline"></span> Jardin</li>
                                <li><span class="mdi mdi-hot-tub"></span> Jacuzzi</li>
                                <li><span class="mdi mdi-pool"></span> Piscine</li>
                                <li><span class="mdi mdi-balcony"></span> Balcon</li>
                                <li><span class="mdi mdi-land-plots"></span> Terrasse</li>
                            </ul>
                        </div>
                    </div>

                </article>
            </section>

            <aside>
                <article id="profilPropriétaire">
                    <img src="./images/lucasAupry.jpg" alt="Photo de profil propriétaire">
                    <div>
                        <h4>AUPRY Lucas</h4>

                        <div class="note">
                            <span class="mdi mdi-star"></span>
                            <span class="mdi mdi-star"></span>
                            <span class="mdi mdi-star"></span>
                            <span class="mdi mdi-star"></span>
                            <span class="mdi mdi-star-outline"></span>
                            <span id="nbAvis">(5 avis)</span>
                        </div>

                    </div>
                </article>

                <article id="box-reservation">
                    <h2><?php echo $accomodation->get("prix_ht_logement");?>€ par nuit</h2>

                    <div class="line"></div>

                    <button>
                        <span>12 juillet 2024 - 15 juillet 2024</span>
                        <span class="mdi mdi-calendar-month"></span>
                    </button>

                    <div>
                        <div>
                            <h4>Arrivée</h4>
                            <h3>12 juillet 2024</h3>
                        </div>


                        <div>
                            <h4>Départ</h4>
                            <h3>15 juillet 2024</h3>
                        </div>
                    </div>
                    <div class="line"></div>

                    <div id="nbVoyageurs">
                        <span>Nombre de voyageurs</span>

                        <div>
                            <button>-</button>
                            <span>3</span>
                            <button>+</button>
                        </div>
                    </div>

                    <div class="line"></div>

                    <div id="nbNuits">
                        <div>
                            <span>Nombre de nuits</span>
                            <h4>3</h4>
                        </div>

                        <div>
                            <div>
                                <span><?php echo $accomodation->get("prix_ht_logement");?>€</span>
                                <span>x 3 nuits</span>
                            </div>
                            <h4><?php echo $accomodation->get("prix_ht_logement")*3;?>€</h4>
                        </div>
                    </div>

                    <div class="line"></div>

                    <div id="boutonDevis">
                        <button>Demander un devis<span class="mdi mdi-chevron-right"></span></button>

                    </div>
                </article>
            </aside>
        </div>
    </main>
</body>

</html>