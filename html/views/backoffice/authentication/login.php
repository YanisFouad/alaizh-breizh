<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/materialdesignicons.min.css">

    <title>Alhaiz Breizh - Connexion au backoffice</title>
</head>
<body>
    <main id="login">
        <form action="#" onsubmit="handleLogin(event)">
            <h2>Connexion propriétaire</h2>

            <img src="/assets/images/logo/logo-alhaiz-breizh-fullsize.svg" alt="logo fullsize" />

            <div role="dialog" class="error-message" class="mdi mdi-information error-message"></div>

            <div class="form-field">
                <label class="required">Adresse mail</label>
                <input name="email" type="email" required />
            </div>
            <div class="form-field">
                <label class="required">Mot de passe</label>
                <input name="password" type="password" required />
            </div>

            <a href="#" class="forgot-password">
                Mot de passe oublié ?
            </a>

            <button type="submit" class="primary backoffice">Se connecter</button>

            <h5 class="another-choice">
                <span class="text">OU</span>
                <span class="line"></span>
            </h5>

            <button type="button" class="primary backoffice">
                S'inscrire
            </button>
        </form>
    </main>

    <?php 
        ScriptLoader::loadAndRender("backoffice/authentication/login.js"); 
    ?>
</body>
</html>