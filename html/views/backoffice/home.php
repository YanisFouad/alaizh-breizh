<?php
    include_once "controllers/backoffice/homeController.php";
    include_once "services/Adresse.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>
    <?php require_once(__DIR__."/../layout/header.php"); ?>
    <main id="page-home-backoffice">
    <section class="accueil-logements-header">
        <div class="title-container">
            <h1>Mes logements (<span class="nb-logement"><?= $nbLogement ?></span>)</h1>
        </div>
        <div class="nev-logement-container">
            <button class="primary">Ajouter un logement</button>
        </div>
    </section>
    <section>
    <div class="logements-container">
    <?php
    foreach ($logements as $key => $logement) {
        // Commencez une nouvelle div "row-logement" au début et après chaque 4 logements
        if ($key % 4 == 0) {
            // Fermez la div précédente si ce n'est pas le premier groupe de logements
            if ($key != 0) {
                echo '</div>';
            }
            echo '<div class="row-logement">';
        }
        ?>
        <article class="card-logement">
            <div class="img-logement-container">
                <img src="<?= $logement->get("photo_logement") ?>" alt="Image Logement" class="img-logement" width="100">
            </div>
            <div class="description-logement-container">
                <h2 class="title-logement"> <?= $logement->get("titre_logement") ?></h2>
                <p class="adresse-logement">
                    <?= $logement->get("ville_adresse") . ", " . Adresse::getDepartement($logement->get("code_postal_adresse")) ?>
                </p>
            </div>
        </article>
        <?php
    }
    // Fermez la dernière div "row-logement"
    echo '</div>';
    ?>
</div>
    </section>
    </main>
    
    <?php require_once(__DIR__."/../layout/footer.php"); ?>
</body>
</html>
