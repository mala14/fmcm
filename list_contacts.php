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
                <th class="thCategories paddingBottom"><?= $userId ?></th>
                <th class="thCategories paddingBottom"><?= $contacts ?></th>
                <th class="thCategories paddingBottom"><?= $email ?></th>
                <th class="thCategories paddingBottom"><?= $jobTitle ?></th>
                <th class="thCategories paddingBottom"><?= $phoneNum ?></th>
            </tr>
        </thead>
        <?= $users->editContacts() ?>
    </table>
</div>

<?php include ("view/footer.php"); ?>
