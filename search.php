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
        <form method="post">
            <div class='formdata'>
                <div class='caseInfoTitle'><?= $search ?>: </div>
                <input type="search" class="searchUsrInfo" name="mainSearch"></input>
            </div>
        </form>
    </div>
    <table class="tableCase">
        <thead>
            <tr>
                <th class="thCategories paddingBottom"><?= $created ?></th>
                <span><th class="thCategories paddingBottom fulltext"><?= $caseId ?></th></span>
                <span><th class="thCategories paddingBottom shortText">Id</th></span>
                <th class="thCategories paddingBottom"><?= $contact ?></th>
                <th class="thCategories paddingBottom"><?= $caseTitle ?></th>
                <th class="thCategories paddingBottom colHide"><?= $assignedTo ?></th>
                <?= $todo->getSearchRes() ?>
            </tr>
        </thead>
    </table>
</div>
<?php include ("view/footer.php"); ?>
