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
    <div class="email-lnks paddingBottom"><?= $mailer->backToActiveCase() ?></div>
    <tr>
    <table class="tableCase">
        <thead>
            <tr>
				<th class="thCategories paddingBottom colHide"><?= $mailId ?></th>
                <th class="thCategories paddingBottom"><?= $mailSent ?></th>
                <th class="thCategories paddingBottom colHide"><?= $sender ?></th>
                <th class="thCategories paddingBottom"><?= $caseTitle ?></th>
                <th class="thCategories paddingBottom"><?= $assignedTo ?></th>
            </tr>
        </thead>
		<?= $mailer->getMailMessages() ?>
    </table>
</div>

<?php include ("view/footer.php"); ?>