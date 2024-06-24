<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/materialdesignicons.min.css">

    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <title>Alhaiz Breizh</title>
</head>
<body>
    <?php 
        if(isset($_GET) && isset($_GET["inscription"])) {
            require_once(__DIR__."/register.php");
        } else {
            require_once(__DIR__."/login.php");
        }
    ?>
</body>
</html>