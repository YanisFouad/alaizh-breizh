<?php 
    if(!UserSession::isConnected()) {
        require_once(__DIR__."/../authentication/login.php");
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

    <title>Alhaiz Breizh - Backoffice</title>
</head>
<body>
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
                    <img src="<?php echo $profile->get("photo_profil"); ?>" alt="profile picture" />
                    <span><?php echo $profile->get("displayname"); ?></span>
                    <span class="mdi mdi-chevron-up"></span>
                </div>

                <div class="dropdown">
                    <ul>
                        <li>
                            <form method="POST" action="/controllers/authentication/disconnectController.php?redirecTo=/backoffice/">
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