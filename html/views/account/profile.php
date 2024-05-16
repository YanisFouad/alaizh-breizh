<?php
    require_once(__DIR__."/../../models/AccountModel.php");

    $profile = array_merge(UserSession::get(), array("my_profile" => true));
    if(isset($_GET) && array_key_exists("account_id", $_GET)) {
        $profile = AccountModel::findOneById($_GET["account_id"]);
    // if the user isn't connected then redirect him to the home
    } else if($profile == null) {
        header("Location: /");
        exit;
    }

    require_once(__DIR__."/../layout/header.php"); 
?>

<div class="profile-area" role="main">
    <?php if($profile !== null) { ?>
        <h1>
            <?php if(array_key_exists("my_profile", $profile)) { ?>
                Mes informations personnelles
            <?php } else { ?>
                Informations de <?php echo $profile["nom"] . " " . $profile["prenom"] ?>
            <?php } ?>
        </h1>

        <img src="<?php echo $profile["photo"] ?? "../../assets/images/default-user.jpg"; ?>" alt="<?php echo $profile["nom"]; ?>" />
            <h2><?php echo $profile["nom"] ?> <?php echo $profile["prenom"] ?></h2>

        <h3>Nom: <span><?php echo $profile["nom"] ?></span></h3>
        <h3>Prénom: <span><?php echo $profile["prenom"] ?? "-" ?></span></h3>
        <h3>Adresse mail: <span><?php echo $profile["mail"] ?? "-" ?></span></h3>
        <h3>Civilité: <span><?php echo $profile["civilite"] ?? "-" ?></span></h3>
        <h3>Date de naissance: <span><?php echo $profile["date_naissance"] ?? "-" ?></span></h3>
        <h3>Téléphone: <span><?php echo $profile["telephone"] ?? "-" ?></span></h3>

    <?php } else { ?>
        <div>
            <h1>Profil introuvable</h1>
            <a href="/">Retour à l'accueil</a>
        </div>
    <?php } ?>

    <?php if(array_key_exists("my_profile", $profile)) { ?>
        <button class="mdi mdi-pencil edit primary">
            Modifier mon profil
        </button>
    <?php } ?>
</div>

<?php require_once(__DIR__."/../layout/footer.php"); ?>