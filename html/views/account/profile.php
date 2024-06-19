<?php
    require_once(__DIR__."/../../models/AccountModel.php");

    $profile = UserSession::get();
    $isOwnProfile = true;

    if(isset($_GET) && array_key_exists("profil_id", $_GET)) {
        $profile = AccountModel::findOneById($_GET["profil_id"]);
        $isOwnProfile = false;
    // if the user isn't connected then redirect him to the home
    } else if($profile == null) {
        header("Location: /#connection=tenant");
        exit;
    }
    
    require_once(__DIR__."/../layout/header.php"); 
?>

<div class="profile-area" role="main">
    <?php if($profile !== null) { ?>
        <h1>
            <?php if($isOwnProfile) { ?>
                Mes informations personnelles
            <?php } else { ?>
                Informations de <?php echo $profile->get("displayname") ?>
            <?php } ?>
        </h1>


        <img src="<?php echo $profile->get("photo_profil"); ?>" alt="<?php echo $profile->get("nom"); ?>" />
        <h2><?php echo $profile->get("displayname") ?></h2>

        <h3>Nom: <span><?php echo $profile->get("nom") ?? "-" ?></span></h3>
        <h3>Prénom: <span><?php echo $profile->get("prenom") ?? "-" ?></span></h3>
        <h3>Adresse mail: <span><?php echo $profile->get("mail") ?? "-" ?></span></h3>
        <h3>Civilité: <span><?php echo $profile->get("civilite") ?? "-" ?></span></h3>
        <h3>Date de naissance: <span><?=date('d/m/Y', strtotime($profile->get("date_naissance"))) ?? "-" ?></span></h3>
        <h3>Téléphone: <span><?php echo $profile->get("telephone") ?? "-" ?></span></h3>

        <?php if($profile->get("accountType") === AccountType::OWNER->name && $isOwnProfile) { ?>
            <h2>Informations en tant que propriétaire</h2>
            
            <h3>Numéro d'identité <span><?=$profile->get("num_carte_identite")?></span></h3>
            <h3>RIB <span><?=$profile->get("rib_proprietaire")?></span></h3>
        <?php } ?>

    <?php } else { ?>
        <div>
            <h1>Profil introuvable</h1>
            <a href="/">Retour à l'accueil</a>
        </div>
    <?php } ?>

    <?php if($isOwnProfile) { ?>
        <button class="mdi mdi-pencil edit primary">
            Modifier mon profil
        </button>
    <?php } ?>
</div>

<?php require_once(__DIR__."/../layout/footer.php"); ?>