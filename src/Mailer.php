<?php
/**
* Class for sending mail by PHPMailer
*
*/
class Mailer
{
    private $conn;

    /* Get database access */
    public function __construct($pdo)
    {
        $this->conn = $pdo;
    }
    /**
    * Get user or object id
    *
    * @return
    */
    public function getID()
    {
        $id = $_GET['id'] ?? null;
        return $id;
    }

    /**
    * Get template id.
    *
    * @return
    */
    public function getTid()
    {
        $id = $_GET['tid'] ?? null;
        return $id;
    }

    // public function isPhpMailer()
    // {
    //     $sql = $this
    // }

    /**
    * Get recepient for the send mail front controller
    *
    * @return
    */
    public function getRecepient()
    {
        $sql = $this->conn->prepare("
        SELECT
            fmcm_todo.id,
            fmcm_todo.created,
            fmcm_contacts.con_email AS email
        FROM
            fmcm_todo
        INNER JOIN
            fmcm_contacts
        ON
            fmcm_todo.contacts = fmcm_contacts.id_contact
        WHERE
            fmcm_todo.id = ?
        ");
        $sql->execute([$this->getId()]);
  			$res = $sql->fetch();
        return $res['email'];
        $pdo = null;
    }

    /**
    * Get case title for subject for mail send
    *
    * @return
    */
    public function getsubject()
    {
        $sql = $this->conn->prepare("
        SELECT
            fmcm_todo.id,
            fmcm_todo.title
        FROM
            fmcm_todo
        INNER JOIN
            fmcm_contacts
        ON
            fmcm_todo.contacts = fmcm_contacts.id_contact
        WHERE
            fmcm_todo.id = ?
        ");
        $sql->execute([$this->getId()]);
  			$res = $sql->fetch();
        return trim($res['title']);
        $pdo = null;
    }

    /**
    * Get case title for subject for mail send
    *
    * @return
    */
    public function getIssue()
    {
        $sql = $this->conn->prepare("
        SELECT
            fmcm_todo.id,
            fmcm_todo.issue
        FROM
            fmcm_todo
        INNER JOIN
            fmcm_contacts
        ON
            fmcm_todo.contacts = fmcm_contacts.id_contact
        WHERE
            fmcm_todo.id = ?
        ");
        $sql->execute([$this->getId()]);
  			$res = $sql->fetch();
        return trim($res['issue']);
        $pdo = null;
    }
    /**
    * create new email templates
    *
    * @return
    */
    public function createTemplate()
    {
      $name = null;
      $template = null;
      if (isset($_POST['createtemplate'])) {
          $error = null;
          try {
              $name = $_POST['ntempname'];
              $template = $_POST['mtemplate'];
              $sql = $this->conn->prepare("INSERT INTO fmcm_mailtemplate (name, template) VALUES (:name, :template)");
              $sql->execute([$name, $template]);
              }
              catch (Exception $e)
              {
                 $error = $e->errorMessage();
              }
              catch (\Exception $e)
              {
                 $error = $e->getMessage();
              }
          return $error;
      }
    }

    /**
    * select template for editing
    *
    * @return
    */
    public function getMailTemplate()
    {
        $html = null;
        $sql = $this->conn->prepare("SELECT id, name FROM fmcm_mailtemplate");
        $sql->execute();
        $res = $sql->fetchAll();
        foreach ($res as $val) {
            $html .= "
            <tbody>
            <div class='template-list'>
                <tr class='table-row' data-href='template_edit.php?tid={$val['id']}'>
                    <td class='template-list'>{$val['name']}</td>
                </tr>
            </div>
            </tbody>
          ";
        }
        return $html;
        $pdo = null;
    }

    /**
    * Edit email templates
    *
    * @return
    */
    public function editTemplate()
    {
        $html = null;
        $sql = $this->conn->prepare("SELECT id, name, template FROM fmcm_mailtemplate WHERE id = :id");
        $sql->execute([$this->getTid()]);
        $res = $sql->fetchAll();
        foreach ($res as $val) {
            $template = htmlspecialchars($val['template']);
            $html .= "
            <form method='post'>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>Name: </div><div class='usrInfo'><input type='text' class='issueTitle' name='etempname' value='{$val['name']}'></div></div></div>
                <textarea type='text' id='editor' class='messArea' name='etemplate' value='template'/>{$template}</textarea>
                <div class='caseBtn'><input type='submit' class='submitBtn' name='edittemplate' value='{$GLOBALS['save']}' ></div>
            </form>
            ";
        }
        if (isset($_POST['edittemplate'])) {
            $name = $_POST['etempname'];
            $template = $_POST['etemplate'];
            $sql = $this->conn->prepare("UPDATE fmcm_mailtemplate SET name = :name, template = :template WHERE id = :id LIMIT 1");
            $sql->execute([$name, $template, $this->getTid()]);
            echo "<script>window.location.href = ''</script>;";
            exit;
            $pdo = null;
        }
        return $html;
        $pdo = null;
    }

    /**
    * Select email template to use at send mail
    *
    * @return
    */
    public function selectTemplate()
    {
        $html = null;
        $sql = $this->conn->prepare("SELECT id, name FROM fmcm_mailtemplate");
        $sql->execute();
        $res = $sql->fetchAll();

        $html .= "
        <select class='temp-select' name='template'>
            <option value=''></option>";
        foreach ($res as $val) {
            $html .= "<option value='{$val['id']}'>{$val['name']}</option>";
        }
        $html .="
        </select>

        ";
        return $html;
    }

    /**
    * Present selectaed emai template at frontcontroller
    *
    * @return
    */
    public function getTemplate()
    {
        $id = null;
        $html = null;
        if (isset($_POST['selecttemplate'])) {
            $id = $_POST['template'];
            $sql = $this->conn->prepare("SELECT id, template FROM fmcm_mailtemplate WHERE id = :id");
            $sql->execute([$id]);
            $res = $sql->fetchAll();
            foreach ($res as $val) {
                $html = "
                    {$val['template']}
                ";
            }
        return $html;
        }
    }
}
