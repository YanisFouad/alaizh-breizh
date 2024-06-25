<?php 
    require_once(__DIR__."/../../../services/session/UserSession.php");
    require_once(__DIR__."/../../../services/ScriptLoader.php");

    // allow users to go in inscription page so they can create an account, otherwise just require the login page
    if(!UserSession::isConnectedAsOwner()) {
        require_once(__DIR__."/../authentication/authentication.php");
        exit;
    }

    $profile = UserSession::get();

    ScriptLoader::load("backoffice/layout/header.js");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/materialdesignicons.min.css">

    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <title>Alhaiz Breizh - Backoffice</title>
</head>
<body id="frontoffice">
    <header id="backoffice">
        <a href="/backoffice/">
            <img alt="AlhaizBreizh's logo" src="/assets/images/logo/logo-alhaiz-breizh-fullsize.svg" />
        </a>
        <nav>
            <ul>
                <li>
                    <a href="/backoffice/">
                        Mes logements
                    </a>
                </li>
                <li>
                    <a href="/backoffice/calendrier">
                        Mes calendriers
                    </a>
                </li>
            </ul>
        </nav>
        <aside>
            <a href="/backoffice/reservations">
                <button>
                    Mes réservations
                    <span class="mdi mdi-arrow-top-right"></span>
                </button>
            </a>
            <div id="profile">
                <div class="infos">
                    <img src="<?=$profile->get("photo_profil"); ?>" alt="<?=$profile->get("displayname")?>" />
                    <span><?=$profile->get("displayname"); ?></span>
                    <span class="mdi mdi-chevron-up"></span>
                </div>

                <div class="dropdown">
                    <ul>
                        <li>
                            <a href="/backoffice/profil">Mon profil</a>
                        </li>
                        <li>
                            <form method="POST" action="/controllers/authentication/disconnectController.php?redirectTo=%2Fbackoffice">
                                <button type="submit">Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <span class="owner">Propriétaire</span>
        </aside>
    </header>
    <main>