<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="html/assets/css/layout/header.css">
    
    <link rel="stylesheet" href="html/assets/css/layout/footer.css">
    <link rel="stylesheet" href="../../assets/css/materialdesignicons.min.css">
    <title>Page d'accueil</title>
</head>
<body>
    <header>
        <nav>
            <a href=""><img src="../../../images/logo_alhaiz_breizh_fullsizeh.svg" alt="Logo ALHaiZ Breizh"></a>
            <ul>
                <li><a href="">Accueil</a></li>
                <li><a href="">Logements</a></li>
            </ul>
            <ul>
                <!-- Deux boutons distincts ! -->
                <li><span class="mdi mdi-account-circle-outline"></span></li>
                <li id="sign-in-button"><a href="">Connexion</a></li>
                <li id="sign-up-button"><a href="">Inscription</a></li>
            </ul>
        </nav>
        <div></div>
    </header>
    
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/materialdesignicons.min.css">

    <title>Alhaiz Breizh</title>
</head>
<body>
    <?php if(!UserSession::isConnected()) { ?>
        <header>
            BLABLABLA
            <button onclick="openLoginModal()">Connexion/Inscription</button>
        </header>
    <?php
        // require the login modal with the default header
        require_once("views/authentication/login.php");
    } else {
    ?>
        <header>
            CONNECTED HEADER

            <form method="POST" action="/controllers/authentication/disconnectController.php">
                <button type="submit">Déconnexion</button>
            </form>
        </header>
    <?php } ?>
    <main>