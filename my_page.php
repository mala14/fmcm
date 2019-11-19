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
        <table class="tableCase">
            <thead>
                <tr>
                    <th><?= $caseId ?></th>
                    <th><?= $created ?></th>
                    <th><?= $caseTitle ?></th>
                    <th><?= $assignedTo ?></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th><?= $caseId ?></th>
                    <th><?= $created ?></th>
                    <th><?= $caseTitle ?></th>
                    <th><?= $assignedTo ?></th>
                </tr>
            </tfoot>
            <tbody>
                <?= $todo->getMyCases() ?>
            </tbody>
        </table>
    </div>
<?php include ("view/footer.php"); ?>
