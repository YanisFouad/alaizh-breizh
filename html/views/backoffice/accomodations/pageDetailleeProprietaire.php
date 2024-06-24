<?php 
    require_once(__DIR__."/../../../models/AccommodationModel.php");
    require_once(__DIR__."/../../../services/fileManager/FileLogement.php");
    require_once(__DIR__."/../../../services/RequestBuilder.php");
    include_once(__DIR__."/../layout/header.php");
    
    $logement_id = $_GET['id_logement'] ?? null;
    if(!isset($logement_id))
        exit(header("Location: /"));

    $logement_id = $_GET['id_logement'];
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
        "terrasse" => "mdi mdi-land-plots"
    ];

    function getDepartmentName($postCode) {
        $postCode= substr($postCode,0,2);
        $result = RequestBuilder::select("pls._departement")
            ->projection("nom_departement")
            ->where("num_departement = ?", $postCode)
            ->execute()
            ->fetchOne();
        return $result["nom_departement"];
    }

    ScriptLoader::load("backoffice/accomodations/boutonActiveDesactive.js");

    include 'popUpValidation.php';
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
        
        <div id="menu">
            <div id="cheminPage">
                <h4><a href="/backoffice">Logements</a></h4>
                <span class="mdi mdi-chevron-right"></span>
                <h4><?=$logement->get('titre_logement');?></h4>
            </div>
            <a id="modifierLogement" href="/backoffice/modification-logement?id_logement=<?php echo $logement->get('id_logement')?>">
                <button class="primary backoffice modifierLogement" type="submit">
                    <span class="mdi mdi-pencil"></span>    
                    Modifier
                </button> 
            </a>
        </div>
        
        <div id="page">
            <section>
                <article id="block-intro">
                    <div id="image-conteneur">
                        <img src="<?=$logement->get('photo_logement');?>" id="img-logement">
                    </div>

                    <div id="intro">
                        <div id="titre">
                            <h1><?=$logement->get('titre_logement');?></h1>

                        </div>

                        <h2><span class="mdi mdi-map-marker"></span><?=$logement->get('ville_adresse').", ";?> <?php echo getDepartmentName($logement->get('code_postal_adresse'));?></h2>
                        <p><?=$logement->get('accroche_logement')?></p>

                        <div id="caracteristiques-logement">
                            <ul>
                                <li>
                                    <span class="mdi mdi-tag-multiple-outline"></span>
                                    Catégorie
                                </li>
                                <li class="bulle-Rose">
                                    <?=ucfirst($logement->get('categorie_logement'))?>
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
                            <h3>Classe énergétique</h3>
                            <img src="/assets/images/labels/energyLabel<?=$logement->get('classe_energetique');?>.png">
                        <?php } ?>
                    </div>
                </article>

                <article id="box-Activites-Amenagement">
                    <div>
                        <div>
                            <h3>Activités</h3>
                            <ul>
                                <?php if ($logement->get('activites')[0]['name'] != null){
                                        foreach ($logement->get('activites') as $key => $value) { ?>
                                            <li><span class="<?=$tabActivites[$value['name']];?>"></span> <?= ucfirst($value['name']);?> - <?= $value['perimetre'];?></li>
                                <?php }}?>
                                
                                
                            </ul>
                        </div>

                        <div>
                            <h3>Aménagements</h3>
                            <ul>
                                <?php if($logement->get('amenagements')[0]['name'] != null){
                                    foreach ($logement->get('amenagements') as $key => $value) { ?>
                                        <li><span class="<?=$tabAmenagements[$value['name']];?>"></span> <?= ucfirst($value['name']);?></li>
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
</body>

</html>