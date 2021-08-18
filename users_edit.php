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
    <div class="caseContactInfo">
        <?= $admin->getEditUsr() ?>
    </div>
    <div class="changePassWord">
        <form method="post">
          <div class="passWdTbl"><div class="passwdBox"><?= $setPassWord ?>: </div><input type="password" name="password" value="" placeholder="Password" autocomplete="off" /></div>
          <div class="passWdTbl"><div class="passwdBox"><?= $confPassWord ?>: </div><input type="password" name="confirmpwd" value="" placeholder="Confirm password" autocomplete="off" /></div>
          <div class=""><input type="submit" name="chUPwd" value="<?= $save ?>"/> <input type="reset" value="<?= $reset ?>"></div>
        </form>
        <?= $admin->changePassword() ?>
    </div>
</div>

<?php include ("view/footer.php"); ?>
