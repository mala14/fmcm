<?php
    require ("config.php");
    include ("view/header.php");
		$users = new User($pdo);
		$login = new Login($pdo);
    $admin = new Admin($pdo);
		$login->checkLogin();
		$login->getSessions();
?>

<?php include ("view/sidebar.php"); ?>

<div class="newCaseTbl">
    <div class="caseContactInfo">
        <?= $users->addContact() ?>
    </div>
</div>

<?php include ("view/footer.php"); ?>
