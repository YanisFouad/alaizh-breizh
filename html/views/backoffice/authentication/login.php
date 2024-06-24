<main id="login">
    <form action="#" onsubmit="handleLogin(event)">

        <img src="/assets/images/logo/logo-alhaiz-breizh-fullsize.svg" alt="logo fullsize" />
        <h2>Connexion propriétaire</h2>

        <div role="dialog" class="error-message" class="mdi mdi-information error-message"></div>

        <div class="form-field">
            <label for="email" class="required">Adresse mail</label>
            <input name="email" id="email" type="email" required />
        </div>
        <div class="form-field">
            <label for="password" class="required">Mot de passe</label>
            <input name="password" id="password" type="password" required />
        </div>

        <a href="#" class="forgot-password">
            Mot de passe oublié ?
        </a>

        <button type="submit" class="primary backoffice">Se connecter</button>

        <h5 class="another-choice">
            <span class="text">OU</span>
            <span class="line"></span>
        </h5>

        <a href="/backoffice?inscription">
            <button type="button" class="primary backoffice">
                S'inscrire
            </button>
        </a>
    </form>
</main>

<?php 
    ScriptLoader::loadAndRender("backoffice/authentication/login.js"); 
?>