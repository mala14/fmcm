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
                    <th class="thCategories paddingBottom"><?= $created ?></th>
                    <th class="thCategories paddingBottom"><?= $caseId ?></th>
                    <th class="thCategories paddingBottom"><?= $contact ?></th>
                    <th class="thCategories paddingBottom"><?= $caseTitle ?></th>
                    <th class="thCategories paddingBottom"><?= $assignedTo ?></th>
                </tr>
            </thead>
            <tbody>
                <?= $todo->getClosedCases() ?>
            </tbody>
        </table>
    </div>
<?php include ("view/footer.php"); ?>
