<?php
// ligne temporaire (donner fictive)
require_once("liste_reservation_proprietaire_donnee_test.php");

// print_r($tab_reservation);

// Liste réservation trier ou filtrer
$tab_reservation_filtrer_trier = [];

// valeur pour test

    function trie_date($date1, $date2){
        if ($date1 == $date2) return 0;
        return ($date1 < $date2) ? -1 : 1;
    }

    //Gestion de l'onglet en cours
    $tab = "a_venir";
    if (isset($_GET['tab'])) {
        $tab = $_GET['tab'];
    }

    //Gestion du trie en cours (à repréciser)
    $trie = "croissant";
    if(isset($_GET['trie'])) {
        $trie = $_GET['trie'];
    }

    $page = "1";
    if(isset($_GET['page'])) {
        $page = $_GET['page'];
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/materialdesignicons.min.css">
    <title>Liste réservation</title>
</head>
<body id="liste-reservation-proprietaire-body">
    
    <div id="liste-reservation-proprietaire-entete">
        <h1>Mes réservations</h1>
        <!--Rechercher une réservation-->
        <div class="liste-reservation-proprietaire-recherche">
            <div>
                <!-- Textarea Rechercher -->
                <textarea id="inputRechercher" name="inputRechercher" autocomplete="on" rows="1">Rechercher...</textarea>
                <!-- Textarea date -->
                <textarea id="inputDateReservations" name="inputDateReservations" autocomplete="on" rows="1">Dates de réservation...</textarea>
            </div>
            <button class="button primary">
                <span class="mdi mdi-magnify"></span>
            </button>
        </div>
    </div>

    <!-- Onglets affichages des réservations -->
    <nav id="liste-reservation-proprietaire-onglet">
        <a class="<?php echo $tab === "a_venir" ? "active" : "" ;?>" href="?tab=a_venir">A venir</a>    
        <a class="<?php echo $tab === "en_cours" ? "active" : "" ;?>" href="?tab=en_cours">En cours</a>
        <a class="<?php echo $tab === "passe" ? "active" : "" ;?>" href="?tab=passe">Passée</a>
    </nav>
    <hr>

    <div id="liste-reservation-proprietaire-float-left">
        <!-- Bouton filtre -->
        <button class="button primary liste-reservation-proprietaire-flex-row">
            <span class="mdi mdi-filter-variant"></span>
            Filtre
        </button>

        <!-- Bouton trie -->
        <!-- trie pas encore fonctionnel -->
        <!-- ?trie=<?php echo $trie === "croissant" ? "decroissant" : "croissant" ?> -->
        <a href=""><button class="liste-reservation-proprietaire-flex-row liste-reservation-proprietaire-bouton-filtre">
            <span class="mdi mdi-sort-ascending"></span>
            trier par date    
        </button></a>
    </div>

    <!-- Traitement selon l'onglets réservations -->
    <?php
    $tab_reservation_filtrer_trier = $tab_reservation;

    $date_du_jour = new DateTime();

    //Pour chaque réservation
    //Suppression des réservation ne corresppondant pas à l'onglet

    //Suppression si la date d'arriver est déjà passé
    if ($tab === "a_venir"){
        $tab_reservation_filtrer_trier = array_filter($tab_reservation_filtrer_trier, function($reservation) use($date_du_jour) {
            return DateTime::createFromFormat('d-m-Y', $reservation['date_arrive']) > $date_du_jour;
        });

    //Suppression si la date d'arriver est pas encore passé 
    }elseif ($tab === "passe" ) {
        $tab_reservation_filtrer_trier = array_filter($tab_reservation_filtrer_trier, function($reservation) use($date_du_jour) {
            return DateTime::createFromFormat('d-m-Y', $reservation['date_depart']) < $date_du_jour;
        });

    //Suppression si la date date du jour, n'est pas entre la date d'arriver et celle de départ
    } elseif ($tab === "en_cours") {
        $tab_reservation_filtrer_trier = array_filter($tab_reservation_filtrer_trier, function($reservation) use($date_du_jour) {
            return (DateTime::createFromFormat('d-m-Y', $reservation['date_arrive']) < $date_du_jour)
                && (DateTime::createFromFormat('d-m-Y', $reservation['date_depart']) > $date_du_jour);
        });
    }
    

    // Traitement pour pagination
    $nb_elem_par_page = 2;
    
    ?>
    <!-- Liste réservation -->
    <section id="liste-reservation-proprietaire">
        <!-- ************************** -->
        <!-- Traitement des réservation -->
        <!-- ************************** -->
        <?php
        for($i = $nb_elem_par_page*$page-1;$i<$nb_elem_par_page*$page;$i++) {
            echo "i=" . $i;
            echo "i<" . $nb_elem_par_page*$page;
            
            
            $reservation = $tab_reservation_filtrer_trier[$i];
            ?>
            <article class="liste-reservation-proprietaire-logement">
            <!-- Photo maison + nom maison -->
            <div>
                <img src="\images_test\crown-8FTlOb9PnbY-unsplash.jpg" alt="Logement">
                <h4><?php echo $reservation["nom_logement"]; ?></h4>
            </div>
            <!-- Description maison -->
            <div class="liste-reservation-proprietaire-logement-detail">
                <div>
                    <h5>Date de réservation</h5>
                    <h4><?php echo $reservation["date_reservation"]; ?></h4>
                </div>
                <div>
                    <h5>Nombre de nuit</h5>
                    <h4><?php echo $reservation["nb_nuits"]; ?></h4>
                </div>
                <div>
                    <h5>Prix total</h5>
                    <h4><?php echo $reservation["prix_total"]; ?></h4>
                </div>
                <button class="button primary liste-reservation-proprietaire-flex-row">
                    <span class="mdi mdi-eye-outline"></span>
                    Facture
                </button>
            </div>
        </article>
        <?php } ?>
    </section>

    <!-- Changement de page de réservation -->
    <div id="liste-reservation-proprietaire-pagination">
        <a href="&page=<?php echo $page-1;?>"><span class="mdi mdi-chevron-left"></span></a>
        <a href=""><h4>1</h4></a>
        <a href=""><h4>49</h4></a>
        <a href=""><span class="mdi mdi-chevron-right"></span></a>
    </div>

</body>
</html>