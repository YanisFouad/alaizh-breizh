<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/materialdesignicons.min.css">

    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
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