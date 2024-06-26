<?php 
    require_once(__DIR__."/../../../models/AccommodationModel.php");
    require_once(__DIR__."/../../../services/fileManager/FileLogement.php");
    require_once(__DIR__."/../../../services/RequestBuilder.php");
    require_once(__DIR__."/../../../services/ScriptLoader.php");

    $data = NULL;
    if(isset($_GET["data"])) {
        $data = base64_decode($_GET["data"]);
        $data = urldecode(html_entity_decode($data));
        $data = json_decode($data, true);
    }
    if(!isset($data)) {
        header("Location: /backoffice?notification-message=Impossible de faire la prévisualisation du logement&notification-type=ERROR");
        exit;
    }

    include_once(__DIR__."/../layout/header.php");

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
        $postCode= substr($postCode,0,2);
        $result = RequestBuilder::select("pls._departement")
            ->projection("nom_departement")
            ->where("num_departement = ?", $postCode)
            ->execute()
            ->fetchOne();
        if(!isset($result))
            return "ERROR";
        return $result["nom_departement"];
    }

    ScriptLoader::load("backoffice/accomodations/boutonActiveDesactive.js");
?>
<main id ="mainProprietaireLogement">
    <div id="page">
        <section>
            <article id="block-intro">
                <div id="image-conteneur">
                    <img src="<?=$data["photo_logement"];?>" id="img-logement">
                </div>

                <div id="intro">
                    <div id="titre">
                        <h1><?=$data["titre_logement"];?></h1>

                    </div>


                    <h2><span class="mdi mdi-map-marker"></span><?=$data["ville_adresse"].", ";?> <?php echo getDepartmentName($data['code_postal_adresse']);?></h2>
                    <p><?=$data["accroche_logement"]?></p>

                    <div id="caracteristiques-logement">
                        <ul>
                            <li>
                                <span class="mdi mdi-tag-multiple-outline"></span>
                                Catégorie
                            </li>
                            <li class="bulle-Rose">
                                <?=ucfirst($data["categorie_logement"])?>
                            </li>

                            <li>
                                <span class="mdi mdi-tag-text-outline"></span>
                                Type
                            </li>
                            <li class="bulle-Rose">
                                <?=$data['type_logement']?>
                            </li>

                            <li>
                                <span class="mdi mdi-texture-box"></span>
                                Surface
                            </li>
                            <li class="bulle-Rose">
                                <?=$data['surface_logement']?>m²
                            </li>

                            <li>
                                <span class="mdi mdi-bed-outline"></span>
                                Nombre de lits simples
                            </li>
                            <li class="bulle-Rose">
                                <?=$data['nb_lits_simples_logement']?>
                            </li>

                            <li>
                                <span class="mdi mdi-bunk-bed-outline"></span>
                                Nombre de lits doubles
                            </li>
                            <li class="bulle-Rose">
                                <?=$data['nb_lits_doubles_logement']?>
                            </li>

                            <li>
                                <span class="mdi mdi-account-group-outline"></span>
                                Nombre de personnes max
                            </li>
                            <li class="bulle-Rose">
                                <?=$data['max_personne_logement']?>
                            </li>
                        </ul>
                    </div>
                </div>
            </article>

            <article id="description">
                <h3>Description</h3>
                <p>
                    <?=$data['description_logement']?>
                </p>

                <div>
                    <?php if($data['classe_energetique']){?>
                        <h3>Classe énergétique</h3>
                        <img src="/assets/images/labels/energyLabel<?=$data['classe_energetique'];?>.png">
                    <?php } ?>
                </div>
            </article>

            <article id="box-Activites-Amenagement">
                <div>
                    <div>
                        <h3>Activités</h3>
                        <ul>
                            <?php if (count($data['activites']) > 0) {
                                    foreach ($data['activites'] as $key => $value) { ?>
                                        <li><span class="<?=$tabActivites[$value['name']]??"";?>"></span> <?= ucfirst($value['name']);?> - <?= ($value['perimetre']??"");?></li>
                            <?php }}?>
                        </ul>
                    </div>

                    <div>
                        <h3>Aménagements</h3>
                        <ul>
                            <?php if(count($data['amenagements']) > 0) {
                                foreach ($data['amenagements'] as $key => $value) { ?>
                                    <li><span class="<?=$tabAmenagements[$value['name']]??"";?>"></span> <?= ucfirst($value['name']);?></li>
                            <?php }}?>
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
<?php include_once(__DIR__."/../../layout/footer.php");?>