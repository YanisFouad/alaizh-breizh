<?php require_once(__DIR__."/../layout/header.php");  ?>

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
                    <h3>Villa sur la port</h3>
                    <h4>
                        <span class="mdi mdi-map-marker"></span>
                        Locmariaquer, Morbihan
                    </h4>
                    <p>
                        Somptueuse villa bretonne, située en bord de mer. Parfaite pour profiter de la Manche (très froide en ce moment) et faire la fête. Proche du port (attention à ne pas tomber dedans !).
                    </p>
                </div>

                <button class="primary">
                    Accéder à l'annonce
                    <span class="mdi mdi-chevron-right"></span>
                </button>
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
                    <h4>2</h4>
                </div>
            </div>
            <h3>Prix total: <span>450 €</span></h3>
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