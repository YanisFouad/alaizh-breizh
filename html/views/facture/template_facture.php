<!-- <head>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head> -->
<?php

function formatPrice($price)
{
    return number_format($price, 2, ',', ' ');
}
function formatPhoneNumber($phone)
{
    if(strlen($phone) == 9 && $phone[0] != '0') {
        $phone = '0' . $phone;
    }

    return preg_replace('/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $phone);
}

?>

<body id="facture-body">
    <header>
        <div class="header-left">
            <div class="enterprise-container">
                <div class="logo-container">
                    <img src="<?= $logo ?>" alt="logo">
                </div>
                <div class="enterprise-information">
                    <p class="name">Association des Loueurs d&apos;Habitations Individuelles en Zone Breizh </p>
                    <div class="adresse">
                        <p>IUT, rue &Eacute;douard Branly</p>
                        <p>22300 Lannion </p>
                        <p>France</p>
                    </div>
                    <div class="contact">
                        <p class="phone">T&eacute;l : 06 06 06 06 06</p>
                        <p class="email">E-mail : <a href="mailto:contact@alhaiz-breizh.bzh">contact@alhaiz-breizh.bzh</a></p>
                    </div>
                </div>
            </div>
            <div class="logement-information-container" style="float: left;">
                <h2>Logement</h2>
                <div class="client-information">
                    <p><?= htmlentities($reservation_nom) ?><p>
                </div>
                <div class="client-information">
                    <p>P&eacute;riode r&eacute;servation : <?= $reservation_date_arrivee ?> - <?= $reservation_date_depart ?></p>
                </div>
                <div class="client-information">
                    <p><?= $reservation_rue_adresse ?></p>
                    <p><?= $reservation_ville_adresse ?></p>
                    <p><?= $reservation_pays_adresse ?></p>
                </div>
                <div class="client-information">
                    <p>Nombre de personnes : <?= $reservation_nb_voyageur ?></p>
                </div>
            </div>
        </div>
        <div class="contact-container">
            <div class="title">
                <h1>FACTURE</h1>
            </div>
            <div class="facture-number">
                <p>Facture n&deg; <?= $id_facture ?></p>
            </div>
            <div class="facture-information">
                <p>Date : <?= $reservation_date ?></p>
                <p>Identifiant client : <?= $userId ?></p>
            </div>
            <div class="client-information-container">
                <h2>Locataire</h2>
                <div class="client-information">
                    <p><?= $user_display_name ?></p>
                </div>
                <div class="client-information">
                    <p><?= $rue_adresse ?></p>
                    <p><?= $ville_adresse ?></p>
                    <p><?= $pays_adresse ?></p>
                </div>
                <div class="client-information">
                    <p>T&eacute;l&eacute;phone : <?= formatPhoneNumber($telephone) ?></p>
                </div>
            </div>
            <div class="client-information-container">
                <h2>Propri&eacute;taire</h2>
                <div class="client-information">
                    <p><?= $proprio_display_name ?></p>
                </div>
                <div class="client-information">
                    <p><?= $proprio_rue_adresse ?></p>
                    <p><?= $proprio_ville_adresse ?></p>
                    <p><?= $proprio_pays_adresse ?></p>
                </div>
                <div class="client-information">
                    <p>T&eacute;l&eacute;phone : <?= formatPhoneNumber($proprio_telephone) ?></p>
                </div>
            </div>

        </div>
    </header>

    <section class="facture-price-section">
        <table class="facture-price-table">
            <thead>
                <tr>
                    <th>
                        <p>Description</p>
                    </th>
                    <th>
                        <p>Quantit&eacute;</p>
                    </th>
                    <th>
                        <p>Prix unitaire</p>
                    </th>
                    <th>
                        <p>Total HT</p>
                    </th>
                    <th>
                        <p>TVA</p>
                    </th>
                    <th>
                        <p>Total TTC</p>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-row-description">
                    <td>
                        <p>Nuit&eacute;e</p>
                    </td>
                    <td>
                        <p><?= $nb_nuit ?></p>
                    </td>
                    <td>
                        <p><?= formatPrice($prix_nuitee_ht) ?> &euro;</p>
                    </td>
                    <td>
                        <p><?= formatPrice($prix_total_nuitee_ht) ?> &euro;</p>
                    </td>
                    <td>
                        <p>10 %</p>
                    </td>
                    <td>
                        <p><?= formatPrice($prix_total_nuitee_ttc) ?> &euro;</p>
                    </td>
                </tr>
                <tr class="table-row-total">
                    <td>
                        <p>Frais de service ( 1% )</p>
                    </td>
                    <td></td>
                    <td>
                        <p><?= formatPrice($frais_service_nuitee_unitaire) ?> &euro;</p>
                    </td>
                    <td>
                        <p><?= formatPrice($total_frais_service_ht) ?> &euro;</p>
                    </td>
                    <td>
                        <p>20 %</p>
                    </td>
                    <td>
                        <p><?= formatPrice($total_frais_service_ttc) ?> &euro;</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Taxe de s&eacute;jour (Nombre voyageurs X Nombre jours)</p>
                    </td>
                    <td>
                        <p><?= $nb_voyageur * $nb_nuit ?> </p>
                    </td>
                    <td>
                        <p><?= formatPrice($tvaTaxeSejour) ?> &euro;</p>
                    </td>
                    <td></td>
                    <td></td>
                    <td>
                        <p><?= formatPrice($taxe_sejour) ?> &euro;</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="facture-total-price">
            <tbody>
                <tr>
                    <th>
                        <p>Total HT</p>
                    </th>
                    <td>
                        <p><?= formatPrice($totalHT) ?> &euro;</p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <p>TVA</p>
                    </th>
                    <td>
                        <p><?= formatPrice($totalTVA) ?> &euro;</p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <p>Total TTC</p>
                    </th>
                    <td>
                        <p><?= formatPrice($totalTTC) ?> &euro;</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
    <section>
        <p>Nous vous remercions de votre confiance,</p>
        <p>Cordialement,</p>
        <p>L'&eacute;quipe Al Haiz Breizh</p>
    </section>
    <footer>
        <p>Association Loi 1901 | N&deg; SIRET : 443 061 841 00047 | Code APE : 55.20Z | N&deg; TVA : FR 64 44 30 61 841</p>
    </footer>
</body>

</html>