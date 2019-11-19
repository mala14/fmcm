<?php
    require (__DIR__ . "/config.php");
    $login = new Login($pdo);
?>
<?php include("view/header.php"); ?>
    <div class="loginTable">
        <div class="tblLogin">
            <h2><?= $loginTitle ?></h2>
            <form method="post">
              <?= $login->uLogin() ?>
            </form>
        </div>
    </div>
<?php include ("view/footer.php"); ?>
