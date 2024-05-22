<?php require_once("../services/UserSession.php"); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/materialdesignicons.min.css">
    <title><?php echo $pageTitle; ?></title>
</head>
<body>
    <?php if(!UserSession::isConnected()) { ?>
    <header>
        <nav>
            <span class="mdi mdi-menu"></span>
            <a class="fullsize-logo" href="#"><img src="../../../images/logo_alhaiz_breizh_fullsizeh.svg" alt="Logo ALHaiZ Breizh"></a>
            <a class="smallsize-logo" href="#"><img src="../../../images/logo_alhaiz_breizh_mini.svg" alt="Logo ALHaiZ Breizh"></a>
            <ul>
                <li><a href="../home.php">Accueil</a></li>
                <li><a href="../housing-list.php">Logements</a></li>
            </ul>
            <div id="sign-in-up-container" onclick="openLoginModal()">
                <span class="mdi mdi-account-circle-outline"></span>
                <button id="sign-in-up-button">Connexion/Inscription</button>
            </div>
        </nav>
        <div id="header-bottom-stroke"></div>
    </header>
    <?php
        // require the login modal with the default header
        require_once("authentication/login.php");
    } else {
    ?>
        <header>
        <nav>
            <span class="mdi mdi-menu"></span>
            <a class="fullsize-logo" href="#"><img src="../../../images/logo_alhaiz_breizh_fullsizeh.svg" alt="Logo ALHaiZ Breizh"></a>
            <a class="smallsize-logo" href="#"><img src="../../../images/logo_alhaiz_breizh_mini.svg" alt="Logo ALHaiZ Breizh"></a>
            <ul>
                <li><a href="../home.php">Accueil</a></li>
                <li><a href="../housing-list.php">Logements</a></li>
            </ul>
            <div id="sign-in-up-container" onclick="openLoginModal()">
                <span class="mdi mdi-account-circle-outline"></span> <!-- Photo du mec -->
                <form method="POST" action="/controllers/authentication/disconnectController.php">
                <button type="submit">Déconnexion</button>
            </form>
            </div>
        </nav>
        <div id="header-bottom-stroke"></div>
        </header>
    <?php } ?>
<body>
