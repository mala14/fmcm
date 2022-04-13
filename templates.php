<?php
    require ("config.php");
    include ("view/header.php");
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
    <table class="tableCase">
        <thead>
            <tr>
              <div class="titles"><?= $createNewTemplate ?></div>
              <form method="post">
                  <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle">Name: </div><div class="usrInfo"><input type="text" class="issueTitle" name="ntempname" value=""></div></div></div>
                  <textarea type="text" id="editor" class="messArea" name="mtemplate" /></textarea>
                  <div class="caseBtn"><input type="submit" class="submitBtn" name="createtemplate" value="<?= $createTemplate ?>" ></div>
                  <?= $mailer->createTemplate() ?>
             </form>
            </tr>
            <tr>
                <th class="thCategories paddingBottom"><?= $templates ?></th>
                <?= $mailer->getMailTemplate() ?>
              </tr>
        </thead>
    </table>
</div>

<?php include ("view/footer.php"); ?>
