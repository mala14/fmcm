<?php
    require (__DIR__ . "/config.php");
    include("view/header.php");
    $login = new Login($pdo);
?>
    <div class="loginTable">
        <div class="tblLogin">
            <h2><?= $loginTitle ?></h2>
            <form method="post">
              <div>
                  <input type="text" class="form-login" name='username' placeholder="<?= $loginHolder ?>" required='required' autofocus='autofocus'>
              </div>
              <div>
                  <input type="password" class="form-login" name='password' placeholder="<?= $loginPassHolder ?>" required='required'>
              </div>
              <button type="submit" name="login" class="loginBtn form-login"><?= $loginSubmit ?></button>
              <?= $login->uLogin() ?>
            </form>
        </div>
    </div>
<?php include ("view/footer.php"); ?>
