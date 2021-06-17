<?php
    require ("config.php");
    include ("view/header.php");
		$users = new User($pdo);
		$login = new Login($pdo);
    $admin = new Admin($pdo);
    $myCases = new Graphs($pdo);
		$login->checkLogin();
		$login->getSessions();
?>
<?php include ("view/sidebar.php"); ?>

<div class="caseTbl">
    <div class="caseContactInfo">
        <canvas id='myChart' width='400' height='400'></canvas>
        <?= $myCases->getAmountCases() ?>
    </div>
</div>
<?php include ("view/footer.php"); ?>
