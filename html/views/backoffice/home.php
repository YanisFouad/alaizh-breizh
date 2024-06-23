<?php
    include_once "controllers/backoffice/HomeBackofficeController.php";
    include_once "services/Adresse.php";

    $controller = new HomeBackofficeController();
?>

<body>
    <?php require ("views/backoffice/layout/header.php"); ?>
    <main id="page-home-backoffice">
    <section class="accueil-logements-header">
        <div class="title-container">
            <h1>Mes logements (<span class="nb-logement"><?= $controller->getNbLogement() ?></span>)</h1>
        </div>
        <a href="/backoffice/nouveau-logement" class="link-new-logement">
            <div class="new-logement-container">
                <button class="tertiary backoffice"><span class="plus-icon mdi mdi-plus"></span>Ajouter un logement</button>
            </div>
        </a>
    </section>
    <section>
    <div class="logements-container backoffice">
        <?php
        if($controller->getNbLogement() == 0) {
            ?>
            <div class="no-logement-container">
                <p>Vous n'avez pas encore de logement.</p>
            </div>
            <?php
        } else {
            foreach ($controller->getLogements() as $key => $logement) {
                if ($key % 4 == 0) {
                    if ($key != 0) {
                        echo '</div>';
                    }
                    echo '<div class="row-logement">';
                }
                ?>
    
                <a href="<?= "/backoffice/logement/?id_logement=" . $logement->get("id_logement")?>" class="link-logement">
                    <article class="card-logement">
                        <div class="img-logement-container">
                            <img src="<?= $logement->get("photo_logement") ?>" alt="Image Logement" class="img-logement">
                        </div>
                        <div class="description-logement-container">
                            <h2 class="title-logement"> <?= $logement->get("titre_logement") ?></h2>
                            <p class="adresse-logement">
                                <span class="mdi mdi-map-marker"></span>
                                <?= $logement->get("ville_adresse") . ", " . Adresse::getDepartement($logement->get("code_postal_adresse")) ?>
                            </p>
                        </div>
                    </article>
                </a>
                <?php
            }
        }
        
        echo '</div>';
        ?>
        </div>
        <nav class="nav-page">
            <ul>
                <li>
                    <?php
                    if (!$controller->getPreviousPage()) { ?>
                        <button class="btn-page btn-nav previous-btn disabled">
                            <span class="mdi mdi-chevron-left"></span>
                        </button>
                    <?php
                    } else { ?>
                        <a href="<?= $controller->getPreviousPageUrl() ?>">
                            <button class="btn-page btn-nav previous-btn">
                                <span class="mdi mdi-chevron-left"></span>
                            </button>
                        </a>
                    <?php
                    }
                    ?>
                </li>
            <?php
                for ($i=0; $i < ceil($controller->getNbLogement() / $controller::NB_ITEM_HOME_BACK); $i++) {
                    ?>
                    <li>
                        <a href="<?=$controller->getIndexPageUrl($i+1)?>">
                        <?php
                            if(($i+1) * $controller::NB_ITEM_HOME_BACK >= $controller->getNbLogement() && $i+1 != $controller->getCurrentPage()) { ?>
                                <button class="btn-page last-btn "><?= $i+1?></button>
                            <?php
                            } elseif (($i+1) * $controller::NB_ITEM_HOME_BACK >= $controller->getNbLogement() && $i+1 == $controller->getCurrentPage()) { ?>
                                <button class="btn-page last-btn is-active"><?= $i+1?></button>
                            <?php
                            } elseif ($i+1 == $controller->getCurrentPage()) { ?>
                                <button class="<?="btn-page btn-" . $i+1 ?> is-active"><?= $i+1?></button>
                            <?php
                            } else {?>
                                <button class="<?="btn-page btn-" . $i+1 ?>"><?= $i+1?></button>
                            <?php
                            }
                        ?>
                        </a>
                    </li>
                    <?php
                }
            ?>
                <li>
                <?php
                    if (!$controller->getNextPage()) { ?>
                        <button class="btn-page btn-nav next-btn disabled">
                            <span class="mdi mdi-chevron-right"></span>
                        </button>
                    <?php
                    } else { ?>
                        <a href="<?= $controller->getNextPageUrl() ?>">
                            <button class="btn-page btn-nav next-btn">
                                <span class="mdi mdi-chevron-right"></span>
                            </button>
                        </a>
                    <?php
                    } ?>
                </li>
            </ul>
            
        </nav>
    </section>
    </main>
    
    <?php 
    
    require_once(__DIR__."/../layout/footer.php"); ?>
</body>
</html>
