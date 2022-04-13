<?php
    require ("config.php");
    include ("view/header.php");
		$users = new User($pdo);
		$login = new Login($pdo);
    $admin = new Admin($pdo);
    $mailer = new Mailer($pdo);
    $admin->IsAdmin();
		$login->checkLogin();
		$login->getSessions();
?>
<?php include ("view/sidebar.php"); ?>

<div class="caseTbl">
    <table class="tableCase">
        <thead>
            <tr>
                <div class="titles"><?= $editTemplate ?></div>
                <th class="thCategories paddingBottom"></th>
                <?= $mailer->editTemplate() ?>
              </tr>
        </thead>
    </table>
</div>

<?php include ("view/footer.php"); ?>
