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
        <form method="post">
            <div id="addUtbl">
                <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $username ?>: </div><div class="editUserField"><input type="text" name="uname" required /></div></div></div>
                <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $name ?>: </div><div class="editUserField"><input type="text" name="fname" required /></div></div></div>
                <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $surname ?>: </div><div class="editUserField"><input type="text" name="lname" required /></div></div></div>
                <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $email ?>: </div><div class="editUserField"><input type="text" name="email" required /></div></div></div>
                <div class="usrInfoTbl"><div class="formdata"><div class="caseInfoTitle"><?= $type ?>: </div><div class="editUserField">
                    <select name="type" class="editUserField">
                        <option value="usr"><?= $usrType ?></option>
                        <option value="admin"><?= $adminType ?></option>
                    </select>
                </div></div></div>
                <div class="passWdTbl"><div class="passwdBox"><?= $setPassWord ?>: </div><input type="password" name="password" value="" placeholder="Password" autocomplete="off" required /></div>
                <div class="passWdTbl"><div class="passwdBox"><?= $confPassWord ?>: </div><input type="password" name="confirmpwd" value="" placeholder="Confirm password" autocomplete="off" required /></div>
                <div class="addUser"><input type="submit" name="addus" value="<?= $save ?>"><input type="reset" value="<?= $reset ?>"></div>
            </div>
        </form>
        <?= $admin->addUsers() ?>
    </div>
</div>

<?php include ("view/footer.php"); ?>
