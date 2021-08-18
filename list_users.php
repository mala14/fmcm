<?php
    require ("config.php");
    include ("view/header.php");
    $todo = new Todo($pdo);
		$users = new User($pdo);
		$login = new Login($pdo);
    $admin = new Admin($pdo);
    $admin->IsAdmin();
		$login->checkLogin();
		$login->getSessions();
?>
<?php include ("view/sidebar.php"); ?>

<div class="caseTbl">
    <table class="tableCase">
        <thead>
            <tr>
                <th class="thCategories paddingBottom"><?= $userId ?></th>
                <th class="thCategories paddingBottom"><?= $username ?></th>
                <th class="thCategories paddingBottom"><?= $fullname ?></th>
                <th class="thCategories paddingBottom"><?= $type ?></th>
                <th class="thCategories paddingBottom"><?= $status ?></th>
                <th class="thCategories paddingBottom colHide"><?= $lastLogin ?></th>
            </tr>
        </thead>
        <?= $admin->editUsers() ?>
    </table>
</div>

<?php include ("view/footer.php"); ?>
