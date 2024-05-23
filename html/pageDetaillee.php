    <?php 
    require_once("./models/AccommodationModel.php");
    $accomodation = AccomodationModel::findOneById(1);

    // s'il n'est pas trouvé
    if(!isset($accomodation)) {
    echo "logement avec l'id machin introuvable";
    }

    // on peut ensuite récupérer les champs comme l'autre, pour voir tous les champs se référer au constructor de AccomodationModel
    echo $accomodation->get("id_logement");

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
                <h4><?php echo $logement["titre_logement"]?></h4>
            </div>
        </div>

        <div id="page">
            <section>
                <article id="blockIntro">
                    <img src="./images/rsz_1frames-for-your-heart-2d4laqalbda-unsplash.jpg" id="imgLogement">

                    <div>
                        <div id="titre">
                            <h1><?php echo $logement["titre_logement"]?></h1>
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
                    <h2>150€ par nuit</h2>

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
                                <span>150€</span>
                                <span>x 3 nuits</span>
                            </div>
                            <h4>450€</h4>
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