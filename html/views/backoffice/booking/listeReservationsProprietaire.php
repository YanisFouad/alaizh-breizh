<?php
   
    require_once(__DIR__."/../../../models/BookingModel.php");
    include_once(__DIR__."/../layout/header.php");

    if(!UserSession::isConnected()){
        require_once("views/backoffice/authentication/login.php");
        exit;
    }

    $profile = UserSession::get();
    $id_proprietaire = $profile->get("id_compte");

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
    $nb_reservation_periode_en_cours = BookingModel::countByPeriod($tab, $id_proprietaire);

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
    $tab_reservation = BookingModel::find($id_proprietaire,$tab,($page-1)*$nb_elem_par_page,$nb_elem_par_page);

    $tab_toute_reservation_periode = BookingModel::findAll($id_proprietaire,$tab);


    ScriptLoader::load("backoffice/bookings.js");

?>

<main id="liste-reservation-proprietaire-main">
    <div id="liste-reservation-proprietaire-entete">
        <h1>Mes réservations</h1>

        <!--Rechercher une réservation-->
        <div class="liste-reservation-proprietaire-recherche">
            
            <!-- export des réservations -->
            <form action="/controllers/csvExport.php" method="POST">
                <!-- bouton d'export -->
                <button id="export-reservation" class="primary backoffice export-reservation" type="submit">
                    Exporter mes réservations
                    <!-- <span class="mdi mdi-export-variant"></span> -->
                </button> 
                <!-- non de fichier pour l'export -->
                <input type="hidden" name="name" value="export_reservations.csv" />
                <!-- donnnée pour fichier -->
                <input type="hidden" name="array" value="<?php echo htmlentities(serialize(array_map(function  ($res){
                return $res->getData();
                } ,$tab_toute_reservation_periode))); ?>" />
            </form>

            <div>
                <!-- Textarea Rechercher -->
                <textarea id="inputRechercher" name="inputRechercher" autocomplete="on" rows="1" disabled >Rechercher...</textarea>
                <!-- Textarea date -->
                <textarea id="inputDateReservations" name="inputDateReservations" autocomplete="on" rows="1" disabled >Dates de réservation...</textarea>
            </div>
            <button class="primary backoffice" disabled>
                <span class="mdi mdi-magnify"></span>
            </button>
        </div>
    </div>

    <!-- Onglets affichages des réservations -->
    <nav id="liste-reservation-proprietaire-onglet">
        <a class="<?php echo $tab === "a_venir" ? "active" : "" ;?>" href="?tab=a_venir">A venir (<?php echo BookingModel::countByPeriod("a_venir", $id_proprietaire)?>)</a>    
        <a class="<?php echo $tab === "en_cours" ? "active" : "" ;?>" href="?tab=en_cours">En cours (<?php echo BookingModel::countByPeriod("en_cours", $id_proprietaire)?>)</a>
        <a class="<?php echo $tab === "passe" ? "active" : "" ;?>" href="?tab=passe">Passées (<?php echo BookingModel::countByPeriod("passe", $id_proprietaire)?>)</a>
    </nav>
    <hr>

    <div id="liste-reservation-proprietaire-float-left">
        <!-- Bouton filtre -->
        <button class="primary backoffice liste-reservation-proprietaire-flex-row" disabled >
            <span class="mdi mdi-filter-variant"></span>
            Filtre
        </button>

        <!-- Bouton trie -->
        <!-- trie pas encore fonctionnel -->
        <!-- ?trie=<?php echo $trie === "croissant" ? "decroissant" : "croissant" ?> -->
        <a href=""><button class="liste-reservation-proprietaire-flex-row liste-reservation-proprietaire-bouton-filtre" disabled > 
            <span class="mdi mdi-sort-ascending"></span>
            trier par date    
        </button></a>
    </div>

    <!-- Traitement selon l'onglets réservations -->
    <?php

    // //Sélection du tableau à utilisé 
    // if ($tab === "a_venir"){
    //     $tab_reservation_filtrer_trier = $tab_reservation_a_venir;
    // }elseif ($tab === "passe" ) {
    //     $tab_reservation_filtrer_trier = $tab_reservation_passe;
    // } elseif ($tab === "en_cours") {
    //     $tab_reservation_filtrer_trier = $tab_reservation_en_cours;
    // }

            ?>
            <!-- Liste réservation -->
            <section id="liste-reservation-proprietaire">
                <!-- ************************** -->
                <!-- Traitement des réservation -->
                <!-- ************************** -->
                <?php
                foreach($tab_reservation as $reservation){
                    ?>
                    <a class="non-souligne" href="/backoffice/reservation?id=<?php echo $reservation->get("id_reservation")?>">
                        <article class="liste-reservation-proprietaire-logement">
                            <!-- Photo maison + nom maison -->
                            <div>
                                <div id='img-container'>
                                    <img src="<?php echo $reservation->get("photo_logement"); ?>" alt="Logement">
                                </div>
                                <h4><?php echo $reservation->get("titre_logement"); ?></h4>
                            </div>
                            

                    <!-- Description maison -->
                    <div class="liste-reservation-proprietaire-logement-detail">
                        <div>
                            <h5>Date de réservation</h5>
                            <h4><?php echo $reservation->get("date_reservation"); ?></h4>
                        </div>
                        <div>
                            <h5>Nombre de nuits</h5>
                            <h4><?php echo $reservation->get("nb_nuit"); ?></h4>
                        </div>
                        <div>
                            <h5>Prix total</h5>
                            <h4><?php echo $reservation->get("prix_total"); ?>€</h4>
                        </div>
                        <button class="primary backoffice liste-reservation-proprietaire-flex-row" disabled >
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

<?php require_once(__DIR__."/../../layout/footer.php") ?>