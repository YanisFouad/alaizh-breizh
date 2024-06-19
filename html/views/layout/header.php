<?php 
require_once(__DIR__."/../../services/session/UserSession.php"); 

$profile = UserSession::get();

ScriptLoader::load("layout/header.js");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/materialdesignicons.min.css">
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <title>AlhaizBreizh</title>
</head>
<body>
    <header id="frontoffice">
        <nav id="top-header">
            <button id="open-burger-button" class="displayed" onclick="openBurgerMenu(true)"><span class="mdi mdi-menu"></span></button>
            <button id="close-burger-button" class="hidden" onclick="openBurgerMenu(false)"><span class="mdi mdi-close"></span></button>

            <a class="fullsize-logo" href="/"><img src="/assets/images/logo/logo-alhaiz-breizh-fullsize.svg" alt="Logo ALHaiZ Breizh"></a>
            <a class="smallsize-logo" href="/"><img src="/assets/images/logo/logo-alhaiz-breizh-mini.svg" alt="Logo ALHaiZ Breizh"></a>
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/logements">Logements</a></li>
                <?php if(UserSession::isConnected()) { ?>
                    <li><a href="/reservations">Mes réservations</a></li>
                <?php } ?>
            </ul>
            
            <?php if(UserSession::isConnected()) { ?>
                <!-- 🤔 onclick ou submit ?-->
                <div id="sign-in-up-container">
                    <a href="/profil">
                        <img src="<?=$profile->get("photo_profil")?>">
                        <span><?=$profile->get("displayname")?></span>
                    </a>
                    <form method="POST" action="/controllers/authentication/disconnectController.php">
                        <button class="sign-in-up-buttons" type="submit">Déconnexion</button>
                    </form>
                </div>
            <?php } else { ?>
                <div id="sign-in-up-container" onclick="openLoginModal()">
                    <button id="sign-in-up-button">
                        <span class="mdi mdi-account-circle-outline"></span>
                        Connexion/Inscription
                    </button>
                </div>
            <?php } ?>
        </nav>
        <div id="header-bottom-stroke"></div>

        <?php if(!UserSession::isConnected()) { ?>
            <nav id="open-burger-menu" class="hidden">
                    <ul>
                        <a href="/"><li><span class="mdi mdi-home"></span>Accueil</li></a>
                        <a href="/logements"><li><span class="mdi mdi-home-group"></span>Logements</li></a>
                        <a href="#" onclick="openLoginModal()">
                            <li>
                                <span class="mdi mdi-login"></span>
                                Connexion / Inscription
                            </li>
                        </a>
                    </ul>
            </nav>
        <?php } else { ?>
            <nav id="open-burger-menu" class="hidden">
                <ul>
                    <a href="/"><li><span class="mdi mdi-home"></span>Accueil</li></a>
                    <a href="/logements"><li><span class="mdi mdi-home-group"></span>Logements</li></a>
                    <a href="/profil"><li><span class="mdi mdi-account"></span>Mon compte</li></a>
                    <a href="#"><li><span class="mdi mdi-logout"></span>Déconnexion</li></a>
                </ul>
            </nav>
        <?php } ?>
        <?php  require_once(__DIR__."/../authentication/login.php"); ?>
    </header>
