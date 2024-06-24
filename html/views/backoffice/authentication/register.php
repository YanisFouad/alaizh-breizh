<?php
    ScriptLoader::loadAndRender("backoffice/authentication/register.js");
?>

<main id="backoffice-registration">
        <img src="/assets/images/logo/logo-alhaiz-breizh-fullsize.svg" alt="logo fullsize" />
        <h2>Inscription propriétaire</h2>
        
        <form id="formProfil" onsubmit="VerifyField(event)" method="POST" action="../../controllers/backoffice/account/registerController.php">
            <div class = "profile-container "> 
                <label for="photo">Photo de profil</label>
                <div class="profile-picture" id="profile-picture">
                    <img id="profile-image" src="/files/locataires/default.webp" alt="Votre photo de profil" />
                </div>
                <input type="file" id="photo_profil" name="photo_profil" accept="image/*" style="display:none;">
            </div>
            <section>
                
                <h2>Informations personnelles</h2>
                <div class="form-field">
                    <label for="id_compte" class="required">Pseudo</label>
                    <input type="text" id="id_compte" name="id_compte" maxlength="20" required >
                </div>
                <div class="form-field">
                    <label for="nom" class="required">Nom</label>
                    <input type="text" id="nom" name="nom" maxlength="50" required >
                </div>
                <div class="form-field">
                    <label for="prenom" class="required">Prénom</label>
                    <input type="text" id="prenom" name="prenom" maxlength="50" required>
                </div>
                <div class="form-field">
                    <label for="mail" class="required">Adresse mail</label>
                    <input type="email" id="mail" name="mail" maxlength="100" required>
                    <div id="error_mail" class="error"></div>
                </div>
                <div class="form-field">
                    <label for="civilite" class="required">Civilité : </label>
                    <select id="civilite" name="civilite">
                        <option value="Mme">Mme</option>
                        <option value="M">M.</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="date_naissance" class="required">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance" required>
                    <div id="error_date" class="error"></div>
                </div>
                <div class="form-field">
                    <label for="telephone" class="required">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" required>
                </div>
            </section>
            <section>
                <h2>Adresse</h2>

                <div class="form-field">
                    <label for="rue_adresse" class="required">Rue</label>
                    <input type="text" id="rue_adresse" name="rue_adresse" required>
                </div>
                <div class="form-field">
                    <label for="numero" class="required">Numéro de rue</label>
                    <input type="text" id="numero" name="numero" required>
                </div>
                <div class="form-field">
                    <label for="complement_numero" class="required">Complément de numéro</label>
                    <input type="text" id="complement_numero" name="complement_numero" >
                </div>
                <div class = "form-field">
                    <label for="complement_adresse" class="required">Complément d'adresse</label>
                    <input type="text" id="complement_adresse" name="complement_adresse" >
                </div>
                <div class="form-field">
                    <label for="ville_adresse" class="required">Ville</label>
                    <input type="text" id="ville_adresse" name="ville_adresse" required>
                </div>
                <div class="form-field">
                    <label for="code_postal_adresse" class="required">Code postal</label>
                    <input type="text" id="code_postal_adresse" name="code_postal_adresse" required>
                </div>
                <div class="form-field">
                    <label for="pays_adresse" class="required">Pays</label>
                    <input type="text" id="pays_adresse" name="pays_adresse" required>
                </div>
            </section>
            <section>
                <h2>Mot de passe</h2>
                <div class="form-field">
                    <h3><label for="mot_de_passe" class="required">Mot de passe</label></h3>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>
                <div class="form-field">
                    <label for="mot_de_passe_confirm" class="required">Confirmer le mot de passe</label>
                    <input type="password" id="mot_de_passe_confirm" name="mot_de_passe_confirm" required>
                    <div id="error_mdp" class="error"></div>
                </div>
            </section>
            <button type="submit" class="primary backoffice" id="valide">
                Inscription
            </button>
        </form>

</main>