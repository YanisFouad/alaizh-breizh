<?php require_once("../services/UserSession.php"); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/materialdesignicons.min.css">
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <title><?php echo $pageTitle; ?></title>
</head>
<body>
    <?php if(!UserSession::isConnected()) { ?>
    <header>
        <nav id="top-header">
            <button id="open-burger-button" class="displayed" onclick="openBurgerMenu(true)"><span class="mdi mdi-menu"></span></button>
            <button id="close-burger-button" class="hidden" onclick="closeBurgerMenu(false)"><span class="mdi mdi-close"></span></button>

            <a class="fullsize-logo" href="#"><img src="../../../images/logo_alhaiz_breizh_fullsizeh.svg" alt="Logo ALHaiZ Breizh"></a>
            <a class="smallsize-logo" href="#"><img src="../../../images/logo_alhaiz_breizh_mini.svg" alt="Logo ALHaiZ Breizh"></a>
            <ul>
                <li><a href="../views/home.php"></span>Accueil</a></li>
                <li><a href="../views/housing-list.php">Logements</a></li>
            </ul>
            <div id="sign-in-up-container" onclick="openLoginModal()">
                <span class="mdi mdi-account-circle-outline"></span>
                <button class="sign-in-up-buttons" type="submit">Connexion/Inscription</button>
            </div>
        </nav>
        <div id="header-bottom-stroke"></div>

        <nav id="open-burger-menu" class="hidden">
                <ul>
                    <a href="../views/home.php"><li><span class="mdi mdi-home"></span>Accueil</li></a>
                    <a href="../views/housing-list.php"><li><span class="mdi mdi-home-group"></span>Logements</li></a>
                    <a href="#"><li><span class="mdi mdi-login"></span>Connexion</li></a>
                    <a href="#"><li><span class="mdi mdi-account-plus"></span>Inscription</li></a>
                </ul>
            </nav>
    </header>
    <?php
        // require the login modal with the default header
        require_once("authentication/login.php");
    } else { 
    ?>
        <header>
            <!-- 🤔 OK 2 id si jamais affichés en même temps ? --> 
            <nav id="top-header">
                <span class="mdi mdi-menu"></span>
                <a class="fullsize-logo" href="#"><img src="../../../images/logo_alhaiz_breizh_fullsizeh.svg" alt="Logo ALHaiZ Breizh"></a>
                <a class="smallsize-logo" href="#"><img src="../../../images/logo_alhaiz_breizh_mini.svg" alt="Logo ALHaiZ Breizh"></a>
                <ul>
                    <li><a href="../home.php">Accueil</a></li>
                    <li><a href="../housing-list.php">Logements</a></li>
                </ul>
                <!-- 🤔 onclick ou submit ?-->
                <div id="sign-in-up-container" onclick="openLoginModal()">
                    <span class="mdi mdi-account-circle-outline"></span> <!-- Photo du mec -->
                    <form method="POST" action="/controllers/authentication/disconnectController.php">
                    <button class="sign-in-up-buttons" type="submit">Déconnexion</button>
                </form>
                </div>
            </nav>
            <div id="header-bottom-stroke"></div>

            <nav id="open-burger-menu" class="hidden">
                <ul>
                    <a href="../home.php"><li><span class="mdi mdi-home"></span>Accueil</li></a>
                    <a href="../housing-list.php"><li><span class="mdi mdi-home-group"></span>Logements</li></a>
                    <a href="../housing-list.php"><li><span class="mdi mdi-account"></span>Mon compte</li></a>
                    <a href="#"><li><span class="mdi mdi-logout"></span>Déconnexion</li></a>
                </ul>
            </nav>
        </header>
    <?php } ?>

    <script>
let menuBurger = document.getElementById("open-burger-menu");
let openButton = document.getElementById("open-burger-button");
let closeButton = document.getElementById("close-burger-button");

function openBurgerMenu(open) {
    console.log("OUVRE");
    if (open) {
        menuBurger.classList.remove("hidden");
        menuBurger.classList.add("displayed");
        openButton.classList.remove("displayed");
        openButton.classList.add("hidden");
        closeButton.classList.remove("hidden");
        closeButton.classList.add("displayed");
    } else {
        menuBurger.classList.add("hidden");
        menuBurger.classList.remove("displayed");
        openButton.classList.add("displayed");
        openButton.classList.remove("hidden");
        closeButton.classList.add("hidden");
        closeButton.classList.remove("displayed");
    }
}

    </script>
<body>
