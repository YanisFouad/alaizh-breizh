<?php 
include_once "../services/ScriptLoader.php";
ScriptLoader::load("authentication/login.js"); ?>
<div role="dialog" class="modal" id="authentication-modal">
    <div class="center">
        <h3>Connexion locataire</h3>
        <button onclick="closeLoginModal()" class="close">&times;</button>

        <img src="/assets/images/logo/logo-alhaiz-breizh-fullsize.svg" alt="logo fullsize" />

        <div role="dialog" class="error-message" class="mdi mdi-information error-message"></div>

        <form action="#" onsubmit="handleLogin(event)">
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

            <button type="submit" class="primary">Se connecter</button>

            <h5 class="another-choice">
                <span class="text">OU</span>
                <span class="line"></span>
            </h5>

            <button type="button" class="primary">
                S'inscrire
            </button>
        </form>
    </div>
</div>