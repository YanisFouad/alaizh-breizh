<?php
    require_once(__DIR__."/../../models/AccountModel.php");
    require_once(__DIR__."/../../controllers/account/profileController.php");

    $user_profile = null;
    if(isset($_GET) && isset($_GET["profil_id"])) {
        $user_profile = AccountModel::findOneById($_GET["profil_id"], AccountType::TENANT);
        if(!isset($user_profile))
            $user_profile = AccountModel::findOneById($_GET["profil_id"], AccountType::OWNER);
        $isOwnProfile = false;
    } else if(UserSession::isConnected()) {
        $user_profile = UserSession::get();
        $isOwnProfile = true;
    }
    
    require_once(__DIR__."/../layout/header.php"); 
?>

<div class="profile-area" role="main">
    <?php if(isset($user_profile)) { ?>
        <header>
            <a href="/" class="mdi mdi-arrow-left">
                Retour à l'accueil
            </a>
            <h1>
                <?php if($isOwnProfile) { ?>
                    Mes informations personnelles
                <?php } else { ?>
                    Informations de <?=$user_profile->get("displayname") ?>
                <?php } ?>
            </h1>
        </header>

        <section>
            <section class="left">
                <article class="profile">
                    <img src="<?=$user_profile->get("photo_profil"); ?>" alt="<?=$user_profile->get("nom"); ?>" />
                    <h3><?=$user_profile->get("displayname") ?></h3>
                </article>
                <article class="general">
                    <h2>Informations générales</h2>
                    
                    <div>
                        <div>
                            <h5>Adresse mail</h5>
                            <h4><?=$user_profile->get("mail") ?></h4>
                        </div>
                        <div>
                            <h5>Civilité</h5>
                            <h4><?=$user_profile->get("civilite") ?></h4>
                        </div>
                        <div>
                            <h5>Date de naissance</h5>
                            <h4><?=to_french_date($user_profile->get("date_naissance")) ?></h4>
                        </div>
                        <div>
                            <h5>Numéro de téléphone</h5>
                            <h4><?=phone_number_format($user_profile->get("telephone")) ?></h4>
                        </div>
                    </div>
                </article>
            </section>
            <section class="right">
                <article>
                    <h2>Informations sur l'adresse</h2>
                    
                    <div>
                        <div>
                            <h5>Numéro de rue</h5>
                            <h4><?=$user_profile->get("numero") ?></h4>
                        </div>
                        <?php if($user_profile->has("complement_numero")) { ?>
                            <div>
                                <h5>Complément numéro</h5>
                                <h4><?=$user_profile->get("complement_numero") ?></h4>
                            </div>
                        <?php } ?>
                        <div>
                            <h5>Rue</h5>
                            <h4><?=$user_profile->get("rue_adresse") ?></h4>
                        </div>
                        <div>
                            <h5>Ville</h5>
                            <h4><?=$user_profile->get("ville_adresse") ?></h4>
                        </div>
                        <div>
                            <h5>Code postal</h5>
                            <h4><?=$user_profile->get("code_postal_adresse") ?></h4>
                        </div>
                        <div>
                            <h5>Pays</h5>
                            <h4><?=$user_profile->get("pays_adresse") ?></h4>
                        </div>
                    </div>
                </article>
                <?php if($user_profile->get("accountType") === AccountType::OWNER->name) { ?>
                    <article>
                        <h2>Informations propriétaire</h2>
                        
                        <div>
                            <div>
                                <h5>Numéro d'identité</h5>
                                <h4><?=$user_profile->get("num_carte_identite")?></h4>
                            </div>
                            <div>
                                <h5>RIB</h5>
                                <h4><?=obsfuceRIB($user_profile->get("rib_proprietaire"))?></h4>
                            </div>
                        </div>
                    </article>
                <?php } ?>
            </section>
        </section>

    <?php } else { ?>
        <div class="no-profile-found">
            <h1>Aucun profil trouvé...</h1>
            <a href="/">Retour à l'accueil</a>
        </div>
    <?php } ?>
</div>

<?php require_once(__DIR__."/../layout/footer.php"); ?>