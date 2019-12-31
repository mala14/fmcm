<?php
    require ("config.php");
    include ("view/header.php");
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
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $name ?>: </div><input type="text" class="conInfo" name="fname" required /></div></div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $surname ?>: </div><input type="text" class="conInfo" name="lname" required /></div></div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $jobTitle ?>: </div><input type="text" class="conInfo" name="jtitle"></div></div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $phone ?>: </div><input type="text" class="conInfo" name="phone"></div></div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $email ?>: </div><input type="text" class="conInfo" name="email"></div></div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $office ?>: </div><input type="text" class="conInfo" name="office"></div></div>
            <div class="usrInfoTb"><div class="formdata"><div class="caseInfoTitle"><?= $address ?>: </div><input type="text" class="conInfo" name="address"></div></div>
            <input type="submit" name="add-user" value="<?= $save ?>"><input type="reset" value="<?= $reset ?>">
        </form>
        <?= $users->addContact() ?>
    </div>
</div>

<?php include ("view/footer.php"); ?>
