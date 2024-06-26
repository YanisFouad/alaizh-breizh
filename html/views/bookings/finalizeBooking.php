<?php 
    require_once(__DIR__."/../../models/AccommodationModel.php");
    require_once(__DIR__."/../../services/session/PurchaseSession.php");
    
    if(!UserSession::isConnectedAsTenant()) {
        header("Location: /?redirectTo=finaliser-ma-reservation#connection");
        exit;
    }

    $session = PurchaseSession::get();

    if(!PurchaseSession::isDefined() || !isset($session["accommodationId"])) {
        header("Location: /?notification-message=Données invalides, veuillez essayer à nouveau.&notification-type=ERROR");
        exit;
    }

    $accommodation = AccommodationModel::findOneById($session["accommodationId"]);
    if(!isset($accommodation)) {
        header("Location: /?notification-message=Logement introuvable.&notification-type=ERROR");
        exit;
    }

    require_once(__DIR__."/../layout/header.php");
?>

<section id="finalize-booking">
    <header>
        <button id="finalize-booking-back-button">
            <span class="mdi mdi-arrow-left"></span>
            Retour
        </button>
        <h1>Finaliser ma réservation</h1>
    </header>
    
    <div role="main">
        <h2>
            <span>Mon choix</span>
            <span></span>
        </h2>

        <article>
            <img src="/files/logements/1_appartement.jpg" alt="appartement 1">
            <article>
                <div>
                    <h3><?=$accommodation->get("titre_logement")?></h3>
                    <h4>
                        <span class="mdi mdi-map-marker"></span>
                        <?=$accommodation->get("ville_adresse")?>, <?=$accommodation->get("pays_adresse")?>
                    </h4>
                    <p><?=$accommodation->get("description_logement")?></p>
                </div>

                <a href="/logement?id_logement=<?=$accommodation->get("id_logement")?>">
                    <button class="primary">
                        Accéder à l'annonce
                        <span class="mdi mdi-chevron-right"></span>
                    </button>
                </a>
            </article>
        </article>
        
        <h2>
            <span>Mon séjour</span>
            <span></span>
        </h2>
        <article>
            <div>
                <div>
                    <h4>Dates:</h4>
                    <h4>12 juillet 2024 - 15 juillet 2024</h4>
                </div>
                <div>
                    <h4>Voyageur(s):</h4>
                    <h4><?=$session["nb_voyageurs"]?></h4>
                </div>
            </div>
            <h3>Prix total: <span><?=$session["total_ati"]?> €</span></h3>
        </article>

        <h2>
            <span>Moyen de paiement</span>
            <span></span>
        </h2>

        <article>
            <div>
                <input checked type="radio" name="methode_paiement" id="credit_card">
                <label for="credit_card">
                    <h4>Carte bancaire</h4>
                    <img src="/assets/images/paymentMethods/credit_card.svg" alt="cb">
                </label>
            </div>
            <div>
                <input type="radio" name="methode_paiement" id="paypal">
                <label for="paypal">
                    <h4>Paypal</h4>
                    <img src="/assets/images/paymentMethods/paypal.svg" alt="paypal">
                </label>
            </div>
        </article>

        <h2>
            <span>Conditions d'annulation</span>
            <span></span>
        </h2>

        <article>
            <p>
            En annulant avant le <b>5 juillet</b>, vous serez remboursé <b>intégralement</b>. Passée cette date, vous ne serez remboursé qu’à hauteur de 50%.
            </p>

            <div class="form-field">
                <input type="checkbox" id="cgv" name="cgv_accepted">
                <label class="mdi mdi-check" for="cgv">En cochant cette case, j'accepte les conditions générales de vente.</label>
            </div>
        </article>
    </div>

    <button class="primary finalize">
        Finaliser ma réservation
        <span class="mdi mdi-chevron-right"></span>
    </button>
</section>

<?php require_once(__DIR__."/../layout/footer.php"); ?>