<?php
    require (__DIR__ . "/config.php");
    $login = new Login($pdo);
    $users = new User($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Freemasons Simple Case Management System">
    <meta name="author" content="mala14">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Freemasons Case Management System logout</title>
</head>
<article>

  <div class="loginTable">
      <div class="tblLogin">
          <h2>FMCM Logout</h2>
          <form method="post">
            <?= $login->uLogout() ?>
          </form>
      </div>
  </div>

<?php include ("view/footer.php"); ?>
