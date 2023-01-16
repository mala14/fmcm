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
    * Get object id
    *
    * @return
    */
    public function getID()
    {
        $id = $_GET['id'] ?? null;
        return $id;
    }

    /**
    * Get case_id id
    *
    * @return
    */
    public function getCaseID()
    {
        $caseid = $_GET['caseId'] ?? null;
        return $caseid;
    }

    /**
    * Get logged in users username as sender
    *
    * @return
    */

    public function getUname()
    {
        $uname = $_SESSION['uname'] ?? null;
        return $uname;
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

    /**
    * Get email id
    *
    * @return
    */
    public function getMailId()
    {
        $mailId = $_GET['mailId'] ?? null;
        return $mailId;
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
		$pdo = null;
        }
    }

    /**
    * Save mail conversation to db
    *
    * @return
    */
    public function saveMail()
    {
        $body = $_POST['body'];
        $dateStamp = date('Y-m-d H:i:s');
        $attachment = null;
        $sql = $this->conn->prepare("INSERT INTO fmcm_mail (time_sent, sender, recepient, attachment, message, case_id) VALUES (:timesent, :sender, :recepient, :attachment, :body, :case_id)");
        $sql->execute([$dateStamp, $this->getUname(), $this->getRecepient(), $attachment, $body, $this->getID()]);
        $pdo = null;
    }

    /**
    * count all mail conversations in specific case_id
    *
    * @return
    */
    public function getMailCount()
    {
        $html = null;
        $sql = $this->conn->prepare("SELECT COUNT(*) AS count FROM fmcm_mail WHERE case_id = :case_id");
        $sql->execute([$this->getCaseID()]);
        $res = $sql->fetch();
        if ($res['count'] > '0') {
            $html .= "<a class='case-mail' href='case_mail.php?caseId={$this->getCaseID()}'>{$GLOBALS['email']} ({$res['count']})</a>";
        } else {
            $html .= "{$GLOBALS['email']} ({$res['count']})";
            }
        return $html;
        $pdo = null;
    }

    /**
    * List all mail conversations to case_id
    *
    * @return
    */
    public function getMailMessages()
    {
        $html = null;
        #$sql = $this->conn->prepare("SELECT id_mail, time_sent, sender  FROM fmcm_mail WHERE case_id = :case_id");
		$sql = $this->conn->prepare("
		SELECT
			fmcm_mail.id_mail,
			fmcm_mail.time_sent,
			v_fmcm_caseinfo.title,
			fmcm_mail.sender,
			v_fmcm_caseinfo.assigned
		FROM fmcm_mail
		INNER JOIN v_fmcm_caseinfo
		ON fmcm_mail.case_id = v_fmcm_caseinfo.case_id
		WHERE fmcm_mail.case_id = ?
		");
        $sql->execute([$this->getCaseID()]);
        $val = $sql->fetchAll();
        foreach ($val as $res) {
            $html .= "
                <tbody>
                    <tr class='case-row 'data-href='read_mail.php?mailId={$res['id_mail']}'>
						<td class='colHide'>{$res['id_mail']}</td>
                        <td class='created'>{$res['time_sent']}</td>
						<td class='colHide'>{$res['sender']}</td>
						<td>{$res['title']}</td>
						<td>{$res['assigned']}</td>
                    </tr>
                </tbody>
            ";
        }
        return $html;
        $pdo = null;
    }

    /**
    * Open selected mail message
    *
    * @return
    */	
	public function readMailMessage()
	{
        $html = null;
        $sql = $this->conn->prepare("SELECT time_sent, sender, recepient, message FROM fmcm_mail WHERE id_mail = :id_mail");
        $sql->execute([$this->getMailId()]);
        $val = $sql->fetchAll();
		foreach ($val as $res) {
			$html .= "<td class='sender'>Sender: {$res['sender']}</td>
				<tr>
					<td class='sender'>To: {$res['recepient']}</td>
				<tr>
					<td class='sender'>Message {$res['message']}</td>
			";
		}
		return $html;
		$pdo = null;
	}
	
    /**
    * Menu button for return to active case
    *
    * @return
    */
	public function backToActiveCase()
	{
        $html = null;
        $sql = $this->conn->prepare("SELECT case_id FROM fmcm_mail WHERE id_mail = :id_mail");
        $sql->execute([$this->getMailId()]);
        $val = $sql->fetch();
		if ($this->getMailId() !== null) {
			foreach ($val as $res) {
					$html .="
						<a class='case-mail' href='case.php?caseId={$val['case_id']}'>To case</a>	
					";
			}
		} else {
			$html .="
				<tbody>
					<a class='case-mail' href='case.php?caseId={$this->getCaseID()}'>To case</a>
				</tbody>		
			";				
			}
		return $html;
		$pdo = null;
	}
	
    /**
    * Menu button for return to mail list on active case
    *
    * @return
    */
	public function backToMailList()
	{
        $html = null;
        $sql = $this->conn->prepare("SELECT case_id FROM fmcm_mail WHERE id_mail = :id_mail");
        $sql->execute([$this->getMailId()]);
        $val = $sql->fetch();
		foreach ($val as $res) {
				$html .="
				<tbody>
					<a class='case-mail' href='case_mail.php?caseId={$val['case_id']}'>To Email</a>
				</tbody>		
				";
		}				
		return $html;
		$pdo = null;
	}
}
