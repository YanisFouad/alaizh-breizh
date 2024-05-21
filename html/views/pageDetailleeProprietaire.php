<?php 
    require_once(__DIR__."/../models/AccommodationsModel.php");
    $logement_id = $_GET['logement_id'];
    $logement = AccommodationsModel::findOneById($logement_id);
    print_r($logement);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <title>Page détaillée d'un logement</title>
</head>

<body>
    <main id ="mainProprietaireLogement">
        <div>
            <button><span class="mdi mdi-arrow-left"></span>Retour</button>
            <div id="cheminPage">
                <a href="#Liste">Logements</a>
                <span class="mdi mdi-chevron-right"></span>
                <h4>Villa Bretonne</h4>
            </div>
        </div>

        <div id="page">
            <section>
                <article id="blockIntro">
                    <img src="./images/rsz_1frames-for-your-heart-2d4laqalbda-unsplash.jpg" id="imgLogement">

                    <div>
                        <div id="titre">
                            <h1>Villa Bretonne</h1>

                        </div>

                        <h2><span class="mdi mdi-map-marker"></span>Locmariaquer, Morbihan</h2>
                        <p>Somptueuse villa bretonne, située en bord de mer. Parfaite pour profiter de la Manche
                            (très
                            froide en
                            ce moment) et faire la fête. Proche du port (attention à ne pas tomber dedans !).
                        </p>

                        <div id="caracteristiques-logement">
                            <ul>
                                <li>
                                    <span class="mdi mdi-tag-multiple-outline"></span>
                                    Categorie
                                </li>
                                <li class="bulle-Rose">Villa</li>

                                <li>
                                    <span class="mdi mdi-tag-text-outline"></span>
                                    Type
                                </li>
                                <li class="bulle-Rose">T5</li>

                                <li>
                                    <span class="mdi mdi-texture-box"></span>
                                    Surface
                                </li>
                                <li class="bulle-Rose">100m²</li>

                                <li>
                                    <span class="mdi mdi-bed-outline"></span>
                                    Nombre de lits simples
                                </li>
                                <li class="bulle-Rose">2</li>

                                <li>
                                    <span class="mdi mdi-bunk-bed-outline"></span>
                                    Nombre de lits doubles
                                </li>
                                <li class="bulle-Rose">5</li>

                                <li>
                                    <span class="mdi mdi-account-group-outline"></span>
                                    Nombre de personnes max
                                </li>
                                <li class="bulle-Rose">12</li>
                            </ul>
                        </div>
                    </div>
                </article>

                <article id="description">
                    <h3>Description</h3>
                    <p>Bienvenue à la somptueuse Villa des Vagues, une résidence bretonne majestueuse idéalement perchée
                        en
                        bord de mer. Avec ses vues imprenables sur la Manche, cette demeure offre un refuge luxueux pour
                        ceux en quête d'évasion et de divertissement. Imaginez-vous plonger dans les eaux fraîches de la
                        mer, ressentant la brise marine caresser votre peau pendant que vous vous adonnez à des
                        activités
                        nautiques palpitantes.
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

        </div>
        <div id="modified-button">
            <button type="button" class ="primary" >
                Modifier
            </button>
        </div>
        
    </main>
</body>

</html>