<<<<<<< HEAD
<?php
    
    //notification si l'utilisateur'est pas connecté
    if(!UserSession::isConnected()){
        header("Location: /?notification-message=Vous devez être connecté pour visualiser cette page&notification-type=ERROR");
        exit;
    }

    include_once(__DIR__."/layout/header.php");
    require_once(__DIR__."/../models/BookingModel.php");

    // ***********************
    // Partie session de l'utilisateur
    // ***********************

    

    $profile = UserSession::get();
    $id_locataire = $profile->get("id_compte");

    function trie_date($date1, $date2){
        if ($date1 == $date2) return 0;
        return ($date1 < $date2) ? -1 : 1;
    }

    //Gestion de l'onglet en cours
    $tab = "a_venir";
    if (isset($_GET['tab'])) {
        $tab = $_GET['tab'];
    }else if (isset($_GET['tab-form'])){
        $tab = $_GET['tab-form'];
    }

    //Gestion du trie en cours (à repréciser)
    $trie = "croissant";
    if(isset($_GET['trie'])) {
        $trie = $_GET['trie'];
    }

    //**************************** */
    // Traitement pour pagination
    //**************************** */

    //nombre d'élément afficher par page
    $nb_elem_par_page = 4;

    //nombre de réservation total pour la période choisi
    $nb_reservation_periode_en_cours = BookingModel::countByPeriodLocataire($tab, $id_locataire);
    
    //nombre de page pour la période choisi
    $nb_page = ceil($nb_reservation_periode_en_cours/$nb_elem_par_page);

    //gestion de la page actuelle
    $page = '1';
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if($nb_page<$page || $page<1){
        $page = '1';
    }

    //tableau de réservation pour la période
    $tab_reservation = BookingModel::findBookingsLocataire($id_locataire,$tab,($page-1)*$nb_elem_par_page,$nb_elem_par_page);
?>

<main id="liste-reservation-locataire-main">
    <div id="liste-reservation-locataire-entete">
        <h1>Mes réservations</h1>

        <!--Rechercher une réservation-->
        <div class="liste-reservation-locataire-recherche">

            <div>
                <!-- Textarea Rechercher -->
                <textarea id="inputRechercher" name="inputRechercher" autocomplete="on" rows="1" disabled >Rechercher...</textarea>
                <!-- Textarea date -->
                <textarea id="inputDateReservations" name="inputDateReservations" autocomplete="on" rows="1" disabled >Dates de réservation...</textarea>
            </div>
            <button class="primary frontoffice" disabled>
                <span class="mdi mdi-magnify"></span>
            </button>
        </div>
    </div>

    <!-- Onglets affichages des réservations -->
    <nav id="liste-reservation-locataire-onglet">
        <a class="<?php echo $tab === "a_venir" ? "active" : "" ;?>" href="?tab=a_venir">A venir (<?php echo BookingModel::countByPeriodLocataire("a_venir", $id_locataire)?>)</a>    
        <a class="<?php echo $tab === "en_cours" ? "active" : "" ;?>" href="?tab=en_cours">En cours (<?php echo BookingModel::countByPeriodLocataire("en_cours", $id_locataire)?>)</a>
        <a class="<?php echo $tab === "passe" ? "active" : "" ;?>" href="?tab=passe">Passées (<?php echo BookingModel::countByPeriodLocataire("passe", $id_locataire)?>)</a>
    </nav>
    <hr>

    <div id="liste-reservation-locataire-float-left">
        <!-- Bouton filtre -->
        <button class="primary frontoffice liste-reservation-locataire-flex-row" disabled >
            <span class="mdi mdi-filter-variant"></span>
            Filtre
        </button>

        <!-- Bouton trie -->
        <!-- trie pas encore fonctionnel -->
        <!-- ?trie=<?php echo $trie === "croissant" ? "decroissant" : "croissant" ?> -->
        <a href=""><button class="liste-reservation-locataire-flex-row liste-reservation-locataire-bouton-filtre" disabled > 
            <span class="mdi mdi-sort-ascending"></span>
            trier par date    
        </button></a>
    </div>

    <!-- Liste réservation -->
    <section id="liste-reservation-locataire">
        <!-- ************************** -->
        <!-- Traitement des réservation -->
        <!-- ************************** -->
        <?php
        foreach($tab_reservation as $reservation){
            ?>
            <a class="non-souligne" href="/reservation?id=<?php echo $reservation->get("id_reservation");?>">
                <article class="liste-reservation-locataire-logement">
                    <!-- Photo maison + nom maison -->
                    <div>
                        <div id='img-container'>
                            <img src="<?php echo $reservation->get("photo_logement"); ?>" alt="Photo logement">
                        </div>
                        <h4><?php echo $reservation->get("titre_logement"); ?></h4>
                    </div>
                    

                    <!-- Description maison -->
                    <div class="liste-reservation-locataire-logement-detail">
                        <div>
                            <h5 class='titreDetail'>Date de réservation</h5>
                            <h5><?php echo date('d/m/Y', strtotime($reservation->get("date_reservation"))); ?></h5>
                        </div>
                        <div>
                            <h5 class='titreDetail'>Date d'arrivée</h5>
                            <h5><?php echo date('d/m/Y', strtotime($reservation->get("date_arrivee"))); ?></h5>
                        </div>
                        <div>
                            <h5 class='titreDetail'>Nombre de nuits</h5>
                            <h5><?php echo $reservation->get("nb_nuit"); ?></h5>
                        </div>
                        <div>
                            <h5 class='titreDetail'>Prix total</h5>
                            <h5><?php echo $reservation->get("prix_total"); ?>€</h5>
                        </div>
                        <button class="primary frontoffice liste-reservation-locataire-flex-row" disabled >
                            <span class="mdi mdi-eye-outline"></span>
                            Facture
                        </button>
                    </div>
                </article>
            </a>
        <?php } ?>
    </section>

    <!-- Changement de page de réservation -->
    <form method="GET" action="#" class="pagination">

        <!-- Premier bouton chevron -->           
        <button <?php if ($page == 1) {echo "disabled";}?> name="page" class="secondary" value="<?php echo $page - 1 ?>">
        <span class="mdi mdi-chevron-left"></span>
        </button>

        <!-- Bouton contenant les numéros de pages -->
        <?php 

        //gestion du min pour bouton pagination 
        if($page == $nb_page){
            $min = $page-2;
        }else{
            $min = $page-1;
        }
        if($min<1){
            $min = 1;
        }

        //gestion du max pour bouton pagination 
        if($page == 1){
            $max = 3;
        }else{
            $max = $page+1;
        }
        if($max > $nb_page){
            $max = $nb_page;
        }

        for($i = $min; $i <= $max; $i++) { ?>
            <button class="<?= $i==$page ? "bouton-select" : "secondary"?>" name="page" value="<?php echo $i?>">
                <span><?php echo $i?></span>
            </button>
        <?php } ?>

        <!-- Dernier bouton chevron -->
        <button <?php if ($page == $nb_page) {echo "disabled";}?> class="secondary" name="page" value="<?php echo $page + 1 ?>">
        <span class="mdi mdi-chevron-right"></span>
        </button>

        <!-- champs caché contenant l'onglet en cours -->
        <input type="hidden" id="tab-form" name="tab-form" value="<?php echo $tab;?>" />
    </form>
</main>
<?php require_once("layout/footer.php"); ?>
=======
<?php
    include_once(__DIR__."/layout/header.php");
    require_once(__DIR__."/../models/BookingModel.php");

    // ***********************
    // Partie session de l'utilisateur
    // ***********************

    // if(!UserSession::isConnected()){
    //     header("Location: /");
    // }

    // $profile = UserSession::get();
    // $id_locataire = $profile->get("id_compte");
    $id_locataire = 'MarLe';

    function trie_date($date1, $date2){
        if ($date1 == $date2) return 0;
        return ($date1 < $date2) ? -1 : 1;
    }

    //Gestion de l'onglet en cours
    $tab = "a_venir";
    if (isset($_GET['tab'])) {
        $tab = $_GET['tab'];
    }else if (isset($_GET['tab-form'])){
        $tab = $_GET['tab-form'];
    }

    //Gestion du trie en cours (à repréciser)
    $trie = "croissant";
    if(isset($_GET['trie'])) {
        $trie = $_GET['trie'];
    }

    //**************************** */
    // Traitement pour pagination
    //**************************** */

    //nombre d'élément afficher par page
    $nb_elem_par_page = 4;

    //nombre de réservation total pour la période choisi
    $nb_reservation_periode_en_cours = BookingModel::countByPeriod($tab, $id_locataire);

    //nombre de page pour la période choisi
    $nb_page = ceil($nb_reservation_periode_en_cours/$nb_elem_par_page);

    //gestion de la page actuelle
    $page = '1';
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    }
    if($nb_page<$page || $page<1){
        $page = '1';
    }

    //tableau de réservation pour la période
    $tab_reservation = BookingModel::find($id_locataire,$tab,($page-1)*$nb_elem_par_page,$nb_elem_par_page);

    $tab_toute_reservation_periode = BookingModel::findAll($id_locataire,$tab);

?>

<!-- <!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../../assets/css/main.css">
        <link rel="stylesheet" href="../../../assets/css/materialdesignicons.min.css">
        <title>Liste réservation</title>
    </head>
    <body> -->
        <main id="liste-reservation-locataire-main">
            <div id="liste-reservation-locataire-entete">
                <h1>Mes réservations</h1>

                <!--Rechercher une réservation-->
                <div class="liste-reservation-locataire-recherche">

                    <div>
                        <!-- Textarea Rechercher -->
                        <textarea id="inputRechercher" name="inputRechercher" autocomplete="on" rows="1" disabled >Rechercher...</textarea>
                        <!-- Textarea date -->
                        <textarea id="inputDateReservations" name="inputDateReservations" autocomplete="on" rows="1" disabled >Dates de réservation...</textarea>
                    </div>
                    <button class="primary frontoffice" disabled>
                        <span class="mdi mdi-magnify"></span>
                    </button>
                </div>
            </div>

            <!-- Onglets affichages des réservations -->
            <nav id="liste-reservation-locataire-onglet">
                <a class="<?php echo $tab === "a_venir" ? "active" : "" ;?>" href="?tab=a_venir">A venir (<?php echo BookingModel::countByPeriod("a_venir", $id_locataire)?>)</a>    
                <a class="<?php echo $tab === "en_cours" ? "active" : "" ;?>" href="?tab=en_cours">En cours (<?php echo BookingModel::countByPeriod("en_cours", $id_locataire)?>)</a>
                <a class="<?php echo $tab === "passe" ? "active" : "" ;?>" href="?tab=passe">Passées (<?php echo BookingModel::countByPeriod("passe", $id_locataire)?>)</a>
            </nav>
            <hr>

            <div id="liste-reservation-locataire-float-left">
                <!-- Bouton filtre -->
                <button class="primary frontoffice liste-reservation-locataire-flex-row" disabled >
                    <span class="mdi mdi-filter-variant"></span>
                    Filtre
                </button>

                <!-- Bouton trie -->
                <!-- trie pas encore fonctionnel -->
                <!-- ?trie=<?php echo $trie === "croissant" ? "decroissant" : "croissant" ?> -->
                <a href=""><button class="liste-reservation-locataire-flex-row liste-reservation-locataire-bouton-filtre" disabled > 
                    <span class="mdi mdi-sort-ascending"></span>
                    trier par date    
                </button></a>
            </div>

            <!-- Liste réservation -->
            <section id="liste-reservation-locataire">
                <!-- ************************** -->
                <!-- Traitement des réservation -->
                <!-- ************************** -->
                <?php
                foreach($tab_reservation as $reservation){
                    ?>
                    <a class="non-souligne" href="/reservation?id=<?php echo $reservation->get("id_reservation");?>">
                        <article class="liste-reservation-locataire-logement">
                            <!-- Photo maison + nom maison -->
                            <div>
                                <div id='img-container'>
                                    <img src="<?php echo $reservation->get("photo_logement"); ?>" alt="Logement">
                                </div>
                                <h4><?php echo $reservation->get("titre_logement"); ?></h4>
                            </div>
                            

                            <!-- Description maison -->
                            <div class="liste-reservation-locataire-logement-detail">
                                <div>
                                    <h5 class='titreDetail'>Date de réservation</h5>
                                    <h5><?php echo $reservation->get("date_reservation"); ?></h5>
                                </div>
                                <div>
                                    <h5 class='titreDetail'>Nombre de nuits</h5>
                                    <h5><?php echo $reservation->get("nb_nuit"); ?></h5>
                                </div>
                                <div>
                                    <h5 class='titreDetail'>Prix total</h5>
                                    <h5><?php echo $reservation->get("prix_total"); ?>€</h5>
                                </div>
                                <button class="primary frontoffice liste-reservation-locataire-flex-row" disabled >
                                    <span class="mdi mdi-eye-outline"></span>
                                    Facture
                                </button>
                            </div>
                        </article>
                    </a>
                <?php } ?>
            </section>

            <!-- Changement de page de réservation -->
            <form method="GET" action="#" class="pagination">

                <!-- Premier bouton chevron -->           
                <button <?php if ($page == 1) {echo "disabled";}?> name="page" class="secondary" value="<?php echo $page - 1 ?>">
                <span class="mdi mdi-chevron-left"></span>
                </button>

                <!-- Bouton contenant les numéros de pages -->
                <?php 
                for($i = $page-1; $i < $page+2; $i++) { 
                if($i>0 && $i <= $nb_page){ 
                        ?>
                        <button class="<?= $i==$page ? "bouton-select" : "secondary"?>" name="page" value="<?php echo $i?>">
                            <span><?php echo $i?></span>
                        </button>
                <?php }
                } ?>

                <!-- Dernier bouton chevron -->
                <button <?php if ($page == $nb_page) {echo "disabled";}?> class="secondary" name="page" value="<?php echo $page + 1 ?>">
                <span class="mdi mdi-chevron-right"></span>
                </button>

                <!-- champs caché contenant l'onglet en cours -->
                <input type="hidden" id="tab-form" name="tab-form" value="<?php echo $tab;?>" />
            </form>
        </main>
<<<<<<< HEAD
    </body>
</html>
>>>>>>> 7f94e8a (updated things)
=======
        <?php require_once("layout/footer.php"); ?>
    <!-- </body>
</html> -->
>>>>>>> a2ac5a4 (fix-pagination liste réservation locataire)
