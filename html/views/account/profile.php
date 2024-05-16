<?php
    require_once("../../models/AccountModel.php");

    $profile = UserSession::get();
    if(isset($_GET) && $_GET["account_id"]) {
        $profile = AccountModel::findOneById($_GET["account_id"]);
    }

    require_once("../layout/header.php"); 
?>

<?php $profile ?>

<?php require_once("../layout/footer.php"); ?>