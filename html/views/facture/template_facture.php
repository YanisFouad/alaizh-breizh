<!-- <head>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head> -->

<?php
    function formatNumber($nb) {
        return number_format($nb, 2, ',', ' ');
    }
?>

<body id="facture-body">
    <header>
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
        <div class="contact-container">
            <div class="title">
                <h1>FACTURE</h1>
            </div>
            <div class="facture-number">
                <p>Facture n&deg; <?= $id_facture ?></p>
            </div>
            <div class="facture-information">
                <p>Date : <?= date("d/m/Y") ?></p>
                <p>Identifiant client : <?= $userId ?></p>
            </div>
            <div class="client-information-container">
                <div class="client-information">
                    <p><?= $user_display_name ?></p>
                </div>
                <div class="client-information">
                    <p><?= $rue_adresse ?></p>
                    <p><?= $ville_adresse ?></p>
                    <p><?= $pays_adresse ?></p>
                </div>
                <div class="client-information">
                    <p>T&eacute;l&eacute;phone : <?= $telephone ?></p>
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
                        <p>Location : <?= formatNumber($prix_nuitee_ht) ?>&euro; X <?= $nb_nuit ?> nuits</p>
                    </td>
                    <td>
                        <p><?= formatNumber($prix_total_nuitee_ht) ?> &euro;</p>
                    </td>
                    <td>
                        <p>10 %</p>
                    </td>
                    <td>
                        <p><?= formatNumber($prix_total_nuitee_ttc) ?> &euro;</p>
                    </td>
                </tr>
                <tr class="table-row-total">
                    <td>
                        <p>Frais de service ( 1% )</p>
                    </td>
                    <td>
                        <p><?= formatNumber($total_frais_service_ht) ?> &euro;</p>
                    </td>
                    <td>
                        <p>0 %</p>
                    </td>
                    <td>
                        <p><?= formatNumber($total_frais_service_ttc) ?> &euro;</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Taxe de s&eacute;jour ( <?= $nb_voyageur ?> Voyageur(s) X <?= $nb_nuit ?> Nuit(s) )</p>
                    </td>
                    <td>
                        <p><?= $taxe_sejour ?> &euro;</p>
                    </td>
                    <td>
                        <p>0 %</p>
                    </td>
                    <td>
                        <p><?= $taxe_sejour ?> &euro;</p>
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
                        <p><?= formatNumber($totalHT) ?> &euro;</p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <p>TVA</p>
                    </th>
                    <td>
                        <p><?= formatNumber($totalTVA) ?> &euro;</p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <p>Total TTC</p>
                    </th>
                    <td>
                        <p><?= formatNumber($totalTTC) ?> &euro;</p>
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