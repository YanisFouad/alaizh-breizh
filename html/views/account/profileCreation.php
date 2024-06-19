<?php
    require_once(__DIR__."/../../models/AccountModel.php");
    require_once(__DIR__."/../../services/ScriptLoader.php");
    require_once(__DIR__."/../layout/header.php");
    ScriptLoader::load("account/profileCreation.js");

?>

<div class="inscription" role="main">
        <h1>
            S'inscrire
        </h1>
        
        <form action="../../controllers/account/profileCreationController.php" method="post" enctype="multipart/form-data">
            <div class = "profile-container "> 
                <h3><label for="photo">Photo de profil : </label></h3>
                <div class="profile-picture" id="profile-picture">
                    <img id="profile-image" src="#" alt="Votre photo de profil" />
                </div>
            </div>
            <input type="file" id="photo_profil" name="photo_profil" accept="image/*">
            <section>
                
                <h2>Informations personnelles</h2>
                <div class = "info">
                    <h3><label for="id_compte">Pseudo : </label></h3>
                    <input type="text" id="id_compte" name="id_compte" maxlength="20" required>
                </div>
                <div class = "info">
                    <h3><label for="nom">Nom : </label></h3>
                    <input type="text" id="nom" name="nom" maxlength="50" required>
                </div>
                <div class = "info">
                    <h3><label for="prenom">Prénom :</label></h3>
                    <input type="text" id="prenom" name="prenom" maxlength="50" required>
                </div>
                <div class = "info">
                    <h3><label for="mail">Adresse mail : </label></h3>
                    <input type="email" id="mail" name="mail" maxlength="100" required>
                </div>
                <div class = "info">
                    <h3><label for="civilite">Civilité : </label></h3>
                    <select id="civilite" name="civilite" required>
                        <option value="Mme">Mme</option>
                        <option value="M">M.</option>
                    </select>
                </div>
                <div class = "info">
                    <h3><label for="date_naissance">Date de naissance : </label></h3>
                    <input type="date" id="date_naissance" name="date_naissance" required>
                </div>
                <div class = "info">
                    <h3><label for="telephone">Téléphone : </label></h3>
                    <input type="tel" id="telephone" name="telephone" required>
                </div>
            </section>
            <section>
                <h2>Adresse</h2>

                <div class = "info">
                    <h3><label for="rue_adresse">Rue :</label></h3>
                    <input type="text" id="rue_adresse" name="rue_adresse" required>
                </div>
                <div class = "info">
                    <h3><label for="numero">Numéro de rue : </label></h3>
                    <input type="text" id="numero" name="numero">
                </div>
                <div class = "info">
                    <h3><label for="complement_numero">Complément de numéro : </label></h3>
                    <input type="text" id="complement_numero" name="complement_numero">
                </div>
                <div class = "info">
                    <h3><label for="complement_adresse">Complément d'adresse : </label></h3>
                    <input type="text" id="complement_adresse" name="complement_adresse">
                </div>
                <div class = "info">
                    <h3><label for="ville_adresse">Ville : </label></h3>
                    <input type="text" id="ville_adresse" name="ville_adresse" required>
                </div>
                <div class = "info">
                    <h3><label for="code_postal_adresse">Code postal : </label></h3>
                    <input type="text" id="code_postal_adresse" name="code_postal_adresse" required>
                </div>
                <div class = "info">
                    <h3><label for="pays_adresse">Pays : </label></h3>
                    <input type="text" id="pays_adresse" name="pays_adresse" required>
                </div>
            </section>
            <section>
                <h2>Mot de passe</h2>
                <div class = "info">
                    <h3><label for="mot_de_passe">Mot de passe : </label></h3>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>
                <div class = "info">
                    <h3><label for="mot_de_passe">Confirmer le mot de passe : </label></h3>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>
            </section>
            <button type="submit" class="primary frontoffice" id="valide">
                Valider
            </button>
        </form>

</div>

<?php require_once(__DIR__."/../layout/footer.php"); ?>