<?php 
    require_once(__DIR__."/../../models/AccommodationModel.php");
    require_once(__DIR__."/../../models/AccountModel.php");

    require_once(__DIR__."/../../services/RequestBuilder.php");
    require_once(__DIR__."/../../services/ScriptLoader.php");
    $id_logement = $_GET["id_logement"] ?? null;

    if(!isset($id_logement) || !is_numeric($id_logement))
        exit(header("Location: /"));

    $accomodation = AccommodationModel::findOneById($id_logement);
    
    if(!isset($accomodation)) {
        exit(header("Location: /"));
    }
    else{    
        
        $amenagements = array();
        $activites = array();

        $activites = $accomodation->get("activites");
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
            "terrasse" => "mdi mdi-land-plots"
        ];

        function getDepartmentName($postCode) {
            $postCode = substr($postCode,0,2);
            $result = RequestBuilder::select("pls._departement")
                ->projection("nom_departement")
                ->where("num_departement = ?", $postCode)
                ->execute()
                ->fetchOne();
            return $result["nom_departement"];
        }

        $codePostal = $accomodation->get("code_postal_adresse");
        $dep = getDepartmentName($codePostal);

        include 'devis.php';

        require_once(__DIR__."/../layout/header.php");

        ScriptLoader::load("acccommodations/logement.js");
?>
<div id="pageDetaillee">
    <div>
        <div id="cheminPage">
            <a href="/logements">Logements</a>
            <span class="mdi mdi-chevron-right"></span>
            <h4 title="<?php echo $accomodation->get("titre_logement");?>">
                <?php echo $accomodation->get("titre_logement");?>
            </h4>
        </div>
    </div>

    <div id="page">
        <section>
            <article id="blockIntro">
                <div class="img-container">
                <div>    
                    <img src="<?=$accomodation->get("photo_logement")?>" id="imgLogement">
                </div>
                <div id="caracteristiques-logement">
                        <ul>
                            <li>
                                <span class="mdi mdi-tag-multiple-outline"></span>
                                Catégorie
                            </li>
                            <li class="bulle-Rose" title="<?php echo $accomodation->get("categorie_logement");?>"><?php echo ucfirst($accomodation->get("categorie_logement"));?></li>

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
                            <li class="bulle-Rose" id="nbPersonneMax" title="<?php echo $accomodation->get("max_personne_logement");?>"><?php echo $accomodation->get("max_personne_logement");?></li>
                        </ul>
                    </div>
                </div>
                <div>
                    <div id="titre">
                        <h1><?php echo $accomodation->get("titre_logement");?></h1>
                    </div>

                    <h2><span class="mdi mdi-map-marker"></span><?php echo $accomodation->get("ville_adresse").", "; echo $dep;?></h2>
                    <p><?php echo $accomodation->get("accroche_logement");?>
                    </p>
                </div>
            </article>

            <article id="description">
                <h3>Description</h3>
                <p><?php echo $accomodation->get("description_logement");?></p>

                <div>
                    <h3>Classe énergétique</h3>
                    <img src="/assets/images/labels/energyLabel<?php echo $accomodation->get("classe_energetique");?>.png">
                </div>
            </article>

            <article id="box-Activites-Amenagement">
                <div>
                    <div class="container-activite">
                        <h3>Activités</h3>
                        <ul class="list-activite">
                            <?php foreach ($accomodation->get('activites') as $key => $value) { ?>
                            <li><span class="<?=$tabActivites[strtolower($value['name'])];?>"></span> 
                            <?= ucfirst($value['name']);?> - <?= $value['perimetre'];?></li>
                            <?php }?>
                        </ul>
                    </div>

                    <div class="container-amenagement">
                        <h3>Aménagements</h3>
                        <ul class="list-amenagement">
                        <?php foreach ($accomodation->get('amenagements') as $value) { ?>
                                    <li><span class="<?=$tabAmenagements[strtolower($value['name'])];?>"></span> <?= ucfirst($value['name']);?></li> 
                        <?php }?>
                        </ul>
                    </div>
                </div>

            </article>
        </section>

        <aside id="blockDevisSticky">
            <article id="profilPropriétaire">
                <img src="<?= AccountModel::findOneById($accomodation->get("id_proprietaire"))->get("photo_profil") ?>" alt="Photo de profil propriétaire">
                <div>
                    <h4><?php 
                        echo $accomodation->get("nom")." ";
                        echo $accomodation->get("prenom");
                    ?></h4>
<!-- 
                    <div class="note">
                        <span class="mdi mdi-star"></span>
                        <span class="mdi mdi-star"></span>
                        <span class="mdi mdi-star"></span>
                        <span class="mdi mdi-star"></span>
                        <span class="mdi mdi-star-outline"></span>
                        <span id="nbAvis">(5 avis)</span>
                    </div> -->

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
                        <button id="moins">-</button>
                        <span id="valeurVoyageurs">1</span>
                        <button id="plus">+</button>
                    </div>
                </div>

                <div class="line"></div>

                <div id="nbNuits">
                    <div>
                        <span>Nombre de nuits</span>
                        <h4 id="nbNuits">3</h4>
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
</div>
<?php }?>

<?php require_once(__DIR__."/../layout/footer.php") ?>