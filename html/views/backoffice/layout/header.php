<?php 
    if(!UserSession::isConnected()) {
        header("Location: /backoffice/connexion");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../../../assets/css/main.css">
    <link rel="stylesheet" href="../../../assets/css/materialdesignicons.min.css">

    <title>Alhaiz Breizh - Backoffice</title>
</head>
<body>
    <header>
        CONNECTED HEADER

        <form method="POST" action="/controllers/authentication/disconnectController.php">
            <button type="submit">Déconnexion</button>
        </form>
    </header>
    <main>