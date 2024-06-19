<?php

require_once(__DIR__ . "/../vendor/autoload.php");
require_once(__DIR__ . "/../services/session/UserSession.php");
require_once(__DIR__ . "/../models/BookingModel.php");
require_once(__DIR__ . "/../models/AccountModel.php");

use mikehaertl\wkhtmlto\Pdf;

function getReservation($id)
{
    return BookingModel::findOneById($id);
}

function checkUser($reservation)
{
    $user = UserSession::get();
    if($user->get("accountType") == AccountType::TENANT->name) {
        return $user->get("id_compte") == $reservation->get("id_locataire");
    }

    if($user->get("accountType") == AccountType::OWNER->name) {
        return $user->get("id_compte") == $reservation->get("id_proprietaire");
    }

    return false;
}

$currentUrl = $_SERVER['REDIRECT_URL'];

if(str_contains($currentUrl, '/backoffice/')) {
    $redirectTo = "/backoffice";
} else {
    $redirectTo = "/";
}

if (UserSession::isConnected()) {
    if (isset($_GET['id'])) {
        $id_facture = intval($_GET['id']);
        if ($id_facture != 0) {
            $reservation = getReservation($id_facture);
            if ($reservation != null) {
                if(checkUser($reservation)) {
                    $logo = "data:image/svg+xml;base64," . base64_encode(file_get_contents(__DIR__ . "/../assets/images/logo/logo-alhaiz-breizh-fullsize.svg"));

                    $id_facture = $reservation->get("id_reservation") . "-" . $reservation->get("id_logement");
                    $user = UserSession::get();
                    $userId = $user->get("id_compte");
                    $user_display_name = $user->get("prenom") . " " . $user->get("nom");
                    $rue_adresse = $user->get("numero") . $user->get("coomplement_numero") . ', ' . $user->get('rue_adresse');
                    $ville_adresse = $user->get("code_postal_adresse") . ' ' . $reservation->get('ville_adresse');
                    $pays_adresse = $user->get("pays_adresse");
                    $telephone = $user->get("telephone");
    
                    $date_arrivee = date('d/m/Y', strtotime($reservation->get("date_arrivee")));
                    $date_depart = date('d/m/Y', strtotime($reservation->get("date_depart")));
                    $nb_nuit = $reservation->get("nb_nuit");
                    $nb_voyageur = $reservation->get("nb_voyageur");
                    $prix_nuitee_ht = number_format((float)$reservation->get("prix_nuitee_ttc") * 0.9, 2, ',', ' ');
                    $prix_total_nuitee_ht = number_format((float)$prix_nuitee_ht * $nb_nuit, 2, ',', ' ');;
                    $prix_total_nuitee_ttc = number_format((float)$reservation->get("prix_nuitee_ttc") * $nb_nuit, 2, ',', ' ');;
                    $prix_total = number_format((float)$reservation->get("prix_total"), 2, ',', ' ');;
                    $frais_de_service = number_format((float)$reservation->get("frais_de_service"), 2, ',', ' ');;
                    $est_payee = $reservation->get("est_payee");
                    $nb_voyageur = $reservation->get("nb_voyageur");
                    $taxe_sejour = number_format((float) $nb_voyageur * (float) $nb_nuit, 2, ',', ' ');;
                    $totalTTC = number_format((float) $prix_total_nuitee_ttc + (float) $frais_de_service + (float) $taxe_sejour, 2, ',', ' ');;
                    $totalHT = number_format((float) $prix_total_nuitee_ht + (float) $frais_de_service + (float) $taxe_sejour, 2, ',', ' ');;
                    $totalTVA = number_format((float) $totalTTC - (float) $totalHT, 2, ',', ' ');;
    
                    ob_start();
                    include __DIR__ . "/../views/facture/template_facture.php";
    
                    $html = ob_get_clean();
    
                    $pdf = new Pdf(["user-style-sheet" => __DIR__ . "/../assets/css/template-facture.css"]);
    
                    $pdf->addPage($html);
    
                    $content = $pdf->toString();
                    if ($content == false) {
                        header("Location: $redirectTo" . "?notification-message=Erreur lors de la génération de la facture&notification-type=ERROR");
                    } else {
                        header('Content-Type: application/pdf');
                        echo $content;
                    }
                } else {
                    header("Location: $redirectTo" . "?notification-message=Vous n'êtes pas autorisé à visualiser cette facture&notification-type=ERROR");
                }
            } else {
                header("Location: $redirectTo" . "?notification-message=Il n'y a pas de réservation pour cette facture&notification-type=ERROR");
            }
        } else {
            header("Location: $redirectTo" . "?notification-message=Il n'y a pas de facture pour cette URL&notification-type=ERROR");
        }
    } else {
        header("Location: $redirectTo" . "?notification-message=Il n'y a pas de facture pour cette réservation&notification-type=ERROR");
    }
} else {
    header("Location: $redirectTo" . "?notification-message=Vous devez être connecté pour visualiser cette page&notification-type=ERROR");
}
