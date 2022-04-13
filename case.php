<?php
    require ("config.php");
    include ("view/header.php");
    $todo = new Todo($pdo);
		$users = new User($pdo);
		$login = new Login($pdo);
    $admin = new Admin($pdo);
		$login->checkLogin();
		$login->getSessions();
?>
<?php include ("view/sidebar.php"); ?>

<div class="caseTbl">
    <div class="caseContactInfo">
          <div class="titles"><?= $caseContactInfo ?></div>
          <?= $todo->getCaseInfo() ?>
          <?= $todo->assignCaseEngineer() ?>
    </div>
    <div class="caseInfo">
        <div class="titles"><?= $caseInfo ?></div>
        <?= $todo->updateCase() ?>
    </div>
</div>

<?php include ("view/footer.php"); ?>
