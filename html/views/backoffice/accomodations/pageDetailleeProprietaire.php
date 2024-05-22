<?php 
    require_once(__DIR__."/../../../models/AccommodationModel.php");
    require_once(__DIR__."/../../../services/FileLogement.php");
    include_once(__DIR__."/../layout/header.php");
    $logement_id = $_GET['logement_id'];
    $logement = AccommodationModel::findOneById($logement_id);
    //print_r($logement->get('id_logement'));
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../assets/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../../assets/css/main.css">
    <title>Page détaillée d'un logement</title>
</head>

<body>

    

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
                    <img src="../../../files/logements/<? echo $logement->get('photo_logement')?>.jpg" id="imgLogement">

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
                        <h3>Classe energetique</h3>
                        <img src="./images/labels/energyLabelF.png">
                    </div>
                </article>

                <article id="box-Activites-Amenagement">
                    <div>
                        <div>
                            <h3>Activités</h3>
                            <ul>
                                <li><span class="mdi mdi-sail-boat"></span> Voile - moins de 5Km</li>
                                <li><span class="mdi mdi-golf"></span> Golf - 20Km ou plus</li>
                                <li><span class="mdi mdi-kayaking"></span> Canoë - Moins de 20Km</li>
                                <li><span class="mdi mdi-hiking"></span> Randonnée - Moins de 20Km</li>
                                <li><span class="mdi mdi-umbrella-beach"></span> Baignade - Moins de 5Km</li>
                                <li><span class="mdi mdi-pine-tree-variant"></span> Accrobranche - 20Km ou plus</li>
                                <li><span class="mdi mdi-horse-human"></span>Equitation - 20Km ou plus</li>
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
            <div id="modified-button">
                <button type="button" class ="primary" >
                    Modifier
                </button>
            </div>
        </div>
        
        
    </main>
</body>

</html>