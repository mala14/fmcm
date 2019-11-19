<?php
    require (__DIR__ . "/config.php");
    $login = new Login($pdo);
    $users = new User($pdo);
?>
<?php include("view/header.php"); ?>
    <div class="loginTable">
        <div class="tblLogin">
            <h2><?= $logoutTitle ?></h2>
            <form method="post">
              <?= $login->uLogout() ?>
            </form>
        </div>
    </div>

<?php include ("view/footer.php"); ?>
