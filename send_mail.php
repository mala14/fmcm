<?php
    require ("config.php");
    include ("view/header.php");
    $users = new User($pdo);
    $login = new Login($pdo);
    $admin = new Admin($pdo);
    $login->checkLogin();
    $login->getSessions();
    $mailer = new Mailer($pdo);
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor/PHPMailer/src/Exception.php';
    require 'vendor/PHPMailer/src/PHPMailer.php';
    require 'vendor/PHPMailer/src/SMTP.php';
    $mail = new PHPMailer(TRUE);
    $error = null;
    if (isset($_POST['sendMail'])) {
        try {
          $subject = htmlspecialchars($_POST['subject']);
          $body = htmlspecialchars($_POST['body']);
          $mail->setFrom($admin->getSetFrom());
          $mail->addAddress($mailer->getRecepient());
          $mail->Subject = htmlspecialchars($subject);
          #$mail->Body = $body;
          $mail->MsgHTML($body);
          /* SMTP parameters. */
          /* Tells PHPMailer to use SMTP. */
          $mail->isSMTP();
          /* SMTP server address. */
          $mail->Host = $admin->getMailHost();
          /* Use SMTP authentication. */
          $mail->SMTPAuth = TRUE;
          /* Set the encryption system. */
          $mail->SMTPSecure = 'tls';
          /* SMTP authentication username. */
          $mail->Username = $admin->getMailUser();
          /* SMTP authentication password.*/
          $mail->Password = $admin->getMailPass();
          /* Set the SMTP port. */
          $mail->Port = 587;
          /* Finally send the mail. */
          $mail->send();
          $mailer->saveMail();
        }
        catch (Exception $e)
        {
           $error = $e->errorMessage();
        }
        catch (\Exception $e)
        {
           $error = $e->getMessage();
        }
     }
?>
<?php include ("view/sidebar.php"); ?>

<div class="caseTbl">
	<div class="email-lnks paddingBottom"><?= $mailer->backToActiveCase() ?></div>
    <form method="post">
        <div class="mailForm">
            <?= $error ?>
            <div class="caseUpdate">
                <div class="caseUpdateTitle">To: </div>
                <div class="udateTitle"><?= $mailer->getRecepient() ?></div>
            </div>
            <div class="caseUpdate">
              <div class="caseUpdateTitle">Subject: </div>
              <div class="udateTitle"><input type="text" class="issueTitle" name="subject" value="<?= $mailer->getsubject() ?> Case id: <?= $mailer->getCaseID() ?>"></div>
            </div>

            <div class="messages">
                <div class="mess-title"><?= $GLOBALS['mailMessage'] ?></div>
                <div class="select-template"><?= $GLOBALS['selectTemplate'] ?><?= $mailer->selectTemplate() ?><input type="submit" name="selecttemplate" value="Select"></div>
            </div>

            <div class="">
                <textarea type="text" id="editor" name="body" />
                    <?= $mailer->getTemplate() ?>
                </textarea>
            </div>
            <div class="caseBtn"><input type="submit" class="submitBtn" name="sendMail" value="<?= $send ?>" /></div>
        </div>
    </form>
</div>

<?php include ("view/footer.php"); ?>
