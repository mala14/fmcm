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
        <div class="titles"><?= $searchContact ?></div>
        <?= $todo->searchUserForm() ?>
    </div>
    <div class="caseInfo">
        <div class="titles"><?= $caseInfo ?></div>
        <form method="post">
            <div class="caseUpdate"><div class="caseUpdateTitle"><?= $caseTitle ?>: </div><input type="text" class="issueTitle" name="issuetitle" placeholder="<?= $caseIssueHolder ?>"></div>
            <div class="caseLabel"><div class="titles"><?= $description ?>:</div></div>
            <div class="setCase">
                <textarea type="text" id="editor" class="messArea" name="commtext" /></textarea><br>
                <input type="hidden" name="addContact" value="<?= $todo->getIdContact() ?>">
                <div class="caseBtn"><input type="submit" class="submitBtn" name="sendCase" value="<?= $save ?>" /></div>
            </div>
        </form>
        <?= $todo->addCase() ?>
    </div>
</div>

<?php include ("view/footer.php"); ?>
