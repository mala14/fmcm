<?php
    require ("config.php");
    include ("view/header.php");
    $todo = new Todo($pdo);
		$users = new User($pdo);
		$login = new Login($pdo);
    $admin = new Admin($pdo);
    $mailer = new Mailer($pdo);
    $admin->IsAdmin();
		$login->checkLogin();
		$login->getSessions();
?>
<?php include ("view/sidebar.php"); ?>

<div class="caseTbl">
    <form method="post">
        <div class="caseContactInfo">
            <div>Mail settings:</div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle">Mail host: </div><div class="usrInfo"><input type="text" class="issueTitle" name="mailhost" value="<?= $admin->getMailHost() ?>"></div></div></div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle">Mail user: </div><div class="usrInfo"><input type="text" class="issueTitle" name="mailuser" value="<?= $admin->getMailuser() ?>"></div></div></div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle">Set from: </div><div class="usrInfo"><input type="text" class="issueTitle" name="setfrom" value="<?= $admin->getSetFrom() ?>"></div></div></div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle">Pass key: </div><div class="usrInfo"><input type="text" class="issueTitle" name="passkey" value="<?= $admin->getPassKey() ?>"></div></div></div>
            <div class="caseBtn"><input type="submit" class="submitBtn" name="mailsave" value="<?= $save ?>" /></div>
            <?= $admin->saveMailChanges() ?>
            <div>Change mail password:</div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle">Mail passwd: </div><div class="usrInfo"><input type="password" class="issueTitle" name="mailpasswd" value=""></div></div></div>
            <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle">Confirm: </div><div class="usrInfo"><input type="password" class="issueTitle" name="confmailpasswd" value=""></div></div></div>
            <div class="caseBtn"><input type="submit" class="submitBtn" name="chmailpasswd" value="<?= $save ?>" /></div>
            <?= $admin->saveMailPasswd() ?>
        </div>
    </form>
</div>

<?php include ("view/footer.php"); ?>
