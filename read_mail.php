<?php
    require ("config.php");
    include ("view/header.php");
    $todo = new Todo($pdo);
	$users = new User($pdo);
	$login = new Login($pdo);
    $admin = new Admin($pdo);
    $mailer = new Mailer($pdo);
	$login->checkLogin();
	$login->getSessions();
?>
<?php include ("view/sidebar.php"); ?>

<div class="caseTbl">
    <div class="email-lnks paddingBottom"><td><?= $mailer->backToActiveCase() ?></td><td><?= $mailer->backToMailList() ?></td></div>
    <tr>
    <table class="tableCase">
		<?= $mailer->readMailMessage() ?>
	</table>
</div>

<?php include ("view/footer.php"); ?>