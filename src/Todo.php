<?php
/**
* Class for cases/todo
*
*/
class Todo
{

    /* Properties */
    private $conn;

    /* Get database access */
    public function __construct($pdo)
    {
        $this->conn = $pdo;
    }

    /**
    * Get the case id.
    *
    * @return
    */

    public function getId()
    {
        $id = $_GET['id'] ?? null;
        return $id;
    }

    /**
    * Get logged in users username
    *
    * @return
    */

    public function getUname()
    {
        $uname = $_SESSION['uname'] ?? null;
        return $uname;
    }
    /**
    * Get contact id.
    *
    * @return
    */
    public function getIdContact()
    {
        $id_contact = $_POST['id_contact'] ?? null;
        return $id_contact;
    }

    /**
    * Display navbar if logged in.
    *
    * @return
    */
    public function getNavBar()
    {
        $html = null;
        if (isset($_SESSION['uname'])) {
            $html .= "
                <td class='nav-item'>
                   <a class='nav-link' href='logout.php'>Logout</a>
                </td>
                <td class='nav-item'>
                  <div class='dropdown'>
                      <div class='nav-link'>New</div>
                          <div class='dropdown-content nav-link'>
                              <a href='new_contact.php'>Contact</a>
                          </div>
                  </div>
                </td>
                <td class='nav-item'>
                  <div class='dropdown'>
                      <div class='nav-link'>Case</div>
                          <div class='dropdown-content nav-link'>
                              <tr>{$this->closeCase()}</tr>
                              <tr>{$this->openCase()}</tr>
                          </div>
                  </div>
                </td>
            ";
        }
        return $html;
    }

    /**
    * Add new case
    * @return
    */
    public function addCase(){
  		$html = null;
  		$error = null;
  		$saved = null;
  		$search = "<i class='fas fa-search'></i>";

  		if(isset($_POST['sendCase'])){
          $name = $_SESSION['uname'] ?? null;
    			$issue = $_POST['commtext'];
    			$title = strip_tags($_POST['issuetitle']);
    			$created = date('Y-m-d G:H:i');
          $contacts = $_POST['addContact'];
          $status = 'Active';

    			if (empty($title)){
    				  $error .= "Title is required.";
    			}
    			if (empty($issue)){
    				  $error .= "Message is required.";
    			}
          if (empty($contacts)) {
              $error .= "A contact is required.";
          }
    			if(!empty($title) && !empty($issue) && !empty($contacts)){
      				$stmt = $this->conn->prepare("INSERT INTO fmcm_todo (name, title, issue, created, status, contacts) VALUES (:name, :title, :issue, :created, :status, :contacts)");
      				$stmt->execute([$name, $title, $issue, $created, $status, $contacts]);
      				$saved .= "Message saved";
      				$stmt = null;
      				header('Location: new_case.php');
      				exit;
    			}
  		}

  		$html .= "
  				<form action='' method='post'>
  						<div class='caseUpdate'><div class='caseUpdateTitle'>{$GLOBALS['caseTitle']}: </div><input type='text' class='issueTitle' name='issuetitle' placeholder='{$GLOBALS['caseIssueHolder']}'></div>
  						<div class='caseLabel'><label>{$GLOBALS['description']}:</label></div>
              <div class='setCase'>
                  <textarea type='text' id='editor' class='messArea' name='commtext' /></textarea><br>
                  <input type='hidden' name='addContact' value='{$this->getIdContact()}'>
      						<div class='caseBtn'><input type='submit' class='submitBtn' name='sendCase' value='{$GLOBALS['save']}' /></div>
              </div>
  				</form>
  				<div>{$error}</div>
  		";
      $pdo = null;
  		return $html;
  	}

    /**
    * The user search form
    * @return
    */
    public function searchUserForm()
    {
        $html = null;
        $result = null;
        if (isset($_POST['getcontact'])) {
            $search = $_POST['searchval'];
            $sql = ("
            SELECT *
            FROM fmcm_contacts
            WHERE
                con_fname LIKE ?
                OR con_lname LIKE ?
                OR con_phone LIKE ?
            ");
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(["%$search%", "%$search%", "%$search%"]);
            $res = $stmt->fetchAll();
                if (!empty($search)) {
                    $result .= "<div class='searchUser'>";
                    foreach ($res as $row) {
                        $result .= "
                            <div class='caseFormData'>
                                <form method='post'>
                                    <input type='hidden' name='id_contact' value='{$row['id_contact']}'>
                                    <div class='usrInfoTbl'><div class='listUserBtn'><input type='submit' class='noBtn' name='selectContact' value='{$row['con_fname']} {$row['con_lname']} {$row['con_jtitle']}'></div></div>
                                </form>
                            </div>
                        ";
                    }
                    $result .= "</div>";
                } else {
                    $result .= "Search field can not be empty";
                }
            $pdo = null;
        }
        $html .= "
        <div class='caseFormData'>
            <form method='post'>
                <div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['search']}: </div><input type='search' class='searchUsrInfo' name='searchval' placeholder='{$GLOBALS['searchContactHolder']}'><button type='image' name='getcontact'><i class='fas fa-search'></i></button></div>
                {$result}
            </form>
            {$this->getUserInfo()}
        </div>
        ";
        return $html;
    }

    public function getUserInfo()
    {
        $html = null;
        if (isset($_POST['selectContact'])) {
            $contact = $this->getIdContact();
            $sql = ("SELECT id_contact, con_fname, con_lname, con_email, con_jtitle, con_office, con_phone, con_address FROM fmcm_contacts WHERE id_contact = :contact");
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$contact]);
            $row = $stmt->fetch();
            $html .= "
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['name']}: </div><div class='usrInfo'>{$row['con_fname']} {$row['con_lname']}</div></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['userTitle']}: </div><div class='usrInfo'>{$row['con_jtitle']}</div></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['phone']}: </div><div class='usrInfo'>{$row['con_phone']}</div></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['email']}: </div><div class='usrInfo'>{$row['con_email']}</div></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['office']}: </div><div class='usrInfo'>{$row['con_office']}</div></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['address']}: </div><div class='usrInfo'>{$row['con_address']}</div></div></div>
                ";
        }
        $pdo = null;
        return $html;
    }

    /**
  	*  Print all closed cases
    *
  	* @return
  	*/
  	public function getClosedCases()
    {
  		$html = null;
  		$sql = $this->conn->prepare("SELECT id, created, title, assigned, closedby, status, fixed FROM fmcm_todo WHERE closedby IS NOT NULL ORDER BY fixed DESC");
  		$sql->execute();
      $getAmount = $sql->rowCount();
      if ($getAmount >= 1) {
          foreach ($sql as $res) {
              $html .= "
              <tr class='case-row' data-href='case.php?id={$res['id']}'>
                  <td class='tbodyTd'>{$res['id']}</td>
                  <td class='tbodyTd'>{$res['title']}</td>
                  <td class='tbodyTd'>{$res['created']}</td>
                  <td class='tbodyTd'>{$res['fixed']}</td>
                  <td class='tbodyTd'>{$res['assigned']}</td>
                  <td class='tbodyTd'>{$res['closedby']}</td>
              </tr>
              ";
          }
      } else {
          $html .= "
          <tr class='case-row'>
              <td class='nodata' colspan='6'>No entries in database</td>
          </tr>
          ";
        }
      $pdo = null;
  		return $html;
  	}

    /**
  	* Get all open cases and publish them
  	*
  	* @return
  	*/
	  public function getAllOpenCases()
    {
		$html = null;
		$sql = $this->conn->prepare("SELECT id, created, title, assigned, status FROM fmcm_todo WHERE closedby IS NULL ORDER BY id ASC");
		$sql->execute();
    $getAmount = $sql->rowCount();
    if ($getAmount >= 1) {
        foreach ($sql as $res) {
            $created = substr($res['created'], 0, 10);
            $html .= "
            <tr class='case-row' data-href='case.php?id={$res['id']}'>
                <td class='tbodyTd'>{$res['id']}</td>
                <td class='tbodyTd'>{$created}</td>
                <td class='tbodyTd'>{$res['title']}</td>
                <td class='tbodyTd'>{$res['assigned']}</td>
            </tr>
            ";
        }
      } else {
          $html .= "
          <tr class='case-row'>
              <td class='nodata' colspan='4'>No entries in database</td>
          </tr>
          ";
      }
        $pdo = null;
    		return $html;
    }

    /**
  	* Get all cases assigned to logged in user
  	*
  	* @return
  	*/
  	public function getMyCases()
    {
    		$html = null;
    		$uname = $_SESSION['uname'];
        $status = 'Active';
    		$sql = $this->conn->prepare("
        SELECT
            id,
            title,
            status,
            assigned,
            created
        FROM
            fmcm_todo
        WHERE
            status = :status
        AND
            assigned = :uname
        ");

    		$sql->execute([$status, $uname]);
        $getAmount = $sql->rowCount();
        if ($getAmount >= 1) {
          foreach ($sql as $val) {
              $cutCreated = substr($val['created'], 0, 10);
              $html .= "
              <tr class='case-row' data-href='case.php?id={$val['id']}'>
                  <td class='tbodyTd'>{$val['id']}</td>
                  <td class='tbodyTd'>{$cutCreated}</td>
                  <td class='tbodyTd'>{$val['title']}</td>
                  <td class='tbodyTd'>{$val['assigned']}</td>
              </tr>
              ";
          }
        } else {
               $html .= "
               <tr class='case-row'>
                   <td class='nodata' colspan='4'>No entries in database</td>
               </tr>
               ";
            }
    		$pdo = null;
    		return $html;
  	}

    /**
    * Get the engineer
    *
    * @return
    */
    public function getEngineer() {
        $id = $this->getId();
        $sql = $this->conn->prepare("SELECT id_user, uname FROM fmcm_users WHERE id_user = :id");
        $sql->execute([$id]);
        $res = $sql['uname'];
        return $res;
    }

    /**
    * Get case information
    *
    * @return
    */
  	public function getCaseInfo()
    {
    		$html = null;
  			$sql = $this->conn->prepare("
            SELECT
                fmcm_todo.id,
                fmcm_todo.assigned,
                fmcm_todo.created,
                fmcm_contacts.con_fname,
                fmcm_contacts.con_lname,
                fmcm_contacts.con_email,
                fmcm_contacts.con_jtitle,
                fmcm_contacts.con_phone,
                fmcm_contacts.con_office,
                fmcm_contacts.con_address
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
  			$res = $sql->fetchAll();

        foreach ($res as $val) {
            $assigned = $_POST['setEngineer'] ?? null;
            $created = substr($val['created'], 0, 10);
            $html .= "
        				<div class='caseFormData'>
          					<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['caseId']}: </div><div class='usrInfo'>{$val['id']}</div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['contact']}: </div><div class='usrInfo'>{$val['con_fname']} {$val['con_lname']}</div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['userTitle']}: </div><div class='usrInfo'>{$val['con_jtitle']}</div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['phone']}: </div><div class='usrInfo'>{$val['con_phone']}</div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['email']}: </div><div class='usrInfo'>{$val['con_email']}</div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['office']}: </div><div class='usrInfo'>{$val['con_office']}</div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['address']}: </div><div class='usrInfo'>{$val['con_address']}</div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['userCreated']}: </div><div class='usrInfo'>{$created}</div></div></div>
        				</div>
        		";
        }
        $pdo = null;
    		return $html;
  	}


    /**
    * Display Assigned user to case
    *
    * @return
    */
    public function getAssigned()
    {
        $html = null;
        $sql = $this->conn->prepare("
        SELECT
            fmcm_todo.id,
            fmcm_users.fname,
            fmcm_users.lname
        FROM
            fmcm_todo
        INNER JOIN
            fmcm_users
        ON
            fmcm_todo.assigned = fmcm_users.uname
        WHERE
            fmcm_todo.id = ?
        ");
        $sql->execute([$this->getId()]);
        $res = $sql->fetchAll();
        foreach ($res as $val) {
            $html .= "{$val['fname']} {$val['lname']}";
        }
        $pdo = null;
        return $html;
    }

    /**
    * Assicg case to user
    *
    * @return
    */
    public function assignCaseEngineer()
    {
        $html = null;
        $error = null;
        $sql = $this->conn->prepare("SELECT fname, lname, uname FROM fmcm_users");
        $sql->execute();
        $res = $sql->fetchAll();
        if (isset($_POST['updateCaseInfo'])) {
            if (isset($_POST['assigned'])) {
                $engineer = $_POST['assigned'];
                $sql = $this->conn->prepare("UPDATE fmcm_todo SET assigned = :engineer WHERE id = :id LIMIT 1");
                $sql->execute([$engineer, $this->getId()]);
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit;
            }
        }

        $html .= "
        <form method='post'>
            <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['assigned']}: </div><div class='selectInfo'>
            <div class='selectObj'>
                <select name='assigned'>
                    <option>{$this->getAssigned()}</option>
                    <option></option>
        ";
        foreach ($res as $row) {
            $html .= "
                <option value='{$row['uname']}'>{$row['fname']} {$row['lname']}</option>
            ";
        }
        $html .= "
                </select>
            </div></div></div></div>
            <input type='submit' class='right sumitBtn' name='updateCaseInfo' value='Assign'>
        </form>
        {$error}
    ";
        return $html;
        $pdo = null;
    }

    /**
    * Get the case title
    *
    * @return
    */
    public function getCaseTitle()
    {
  			$stmt = $this->conn->prepare("SELECT id, title FROM fmcm_todo WHERE id = :id");
  			$stmt->execute([$this->getId()]);
  			$res = $stmt->fetch();
        $pdo = null;
        return $res['title'];
    }

    /**
    * Update the case with a comment.
    *
    * @return
    */
    public function updateCase()
    {
        $error = null;
        $id = $this->getId();
        $uname = $_SESSION['uname'];
        $dateStamp = date('Y-m-d H:i:s');

        if (isset($_POST['comment'])) {
            $commtext = $_POST['commtext'];
            if (!empty($commtext)) {
                $sql = $this->conn->prepare("INSERT INTO fmcm_comment (id_todo, comment, id_user, saved) VALUES (:id, :commtext, :uname, :dateStamp)");
                $sql->execute([$id, $commtext, $uname, $dateStamp]);
            } else {
                $error = "You have to leave a comment";
            }
        }

     		$html = "
            <div class='caseUpdate'><div class='caseUpdateTitle'>{$GLOBALS['caseTitle']}: </div><div class='udateTitle'>{$this->getCaseTitle()}</div></div>
            <div class='caseUpdate'><div class='caseUpdateTitle'>{$GLOBALS['issue']}: </div><div class='udateTitle'>{$this->getIssue()}</div></div>
    				Add comment
            <form method='post' class='setCase'>
                <input type='hidden' name='id' value='{$id}'>
            		<textarea type='text' id='editor' class='messArea' name='commtext' placeholder='{$error}'/></textarea><br>
        				<input type='submit' class='submitBtn' name='comment' value='{$GLOBALS['caseUpdate']}'> {$this->caseStatus()}
            </form>
            {$this->getComment()}
    		";
        $pdo = null;
        return $html;
    }

    /**
  	* Closed case.
  	*
  	* @return
  	*/
    public function closeCase() {
        $html = null;
        $uname = $_SESSION['uname'];
        $dateStamp = date('Y-m-d H:i:s');
        $id = $this->getId();
        $status = 'Closed';
        if (isset($_POST['closeCase'])) {
            $sql = $this->conn->prepare("UPDATE fmcm_todo SET status = :status, closedby = :uname, fixed = :dateStamp WHERE id = :id LIMIT 1");
            $sql->execute([$status, $uname, $dateStamp, $id]);
        }

        $html .= "
            <form method='post'>
                <button type='submit' name='closeCase' class='saveCase nav-link'>Close case</button>
            </form>
        ";
        $pdo = null;
        return $html;
    }

    /**
  	* Open case.
  	*
  	* @return
  	*/
    public function openCase() {
        $html = null;
        $uname = $_SESSION['uname'];
        $dateStamp = date('Y-m-d H:i:s');
        $id = $this->getId();
        $status = 'Active';
        if (isset($_POST['openCase'])) {
            $sql = $this->conn->prepare("UPDATE fmcm_todo SET status = :status, closedby = null, fixed = null WHERE id = :id LIMIT 1");
            $sql->execute([$status, $id]);
        }

        $html .= "
            <form method='post'>
                <input type='submit' name='openCase' class='saveCase nav-link' value='Open case'>
            </form>
        ";
        $pdo = null;
        return $html;
    }

    /**
  	* Get the icase status Active/Closed
  	*
  	* @return
  	*/
    public function caseStatus()
    {
        $sql = $this->conn->prepare("SELECT id, status FROM fmcm_todo WHERE id = :id");
        $sql->execute([$this->getId()]);
        $res = $sql->fetch();
        if ($res['status'] === 'Active') {
            $status = "
                <div class='caseStatusOpen'>
                    {$res['status']}
                </div>
            ";
        } else {
            $status = "
                <div class='caseStatusClosed'>
                    {$res['status']}
                </div>
            ";
        }
        $pdo = null;
        return $status;
    }

    /**
  	* Get the issue text from case and display it.
  	*
  	* @return
  	*/
    public function getIssue()
    {
        $sql = $this->conn->prepare("SELECT id, issue FROM fmcm_todo WHERE id = :id");
        $sql->execute([$this->getId()]);
        $res = $sql->fetch();
        return $res['issue'];
        $pdo = null;
    }

    /**
  	* Get the comments for issue.
  	*
  	* @return
  	*/
    public function getComment()
    {
        $html = null;
        $id = $this->getId();
        $sql = $this->conn->prepare("SELECT id_comment, id_todo, comment, id_user, saved FROM fmcm_comment WHERE id_todo = :id ORDER BY id_comment DESC");
        $sql->execute([$id]);
        $res = $sql->fetchAll();
        foreach ($res as $val) {
            $html .= "
                <div class='commentField'>
                    <div class='updatestamp'>{$GLOBALS['updatedBy']}: {$val['id_user']} <br /> {$val['saved']}</div>
                    <div class='comments'>{$val['comment']}</div>
                </div>
            ";
        }
        $pdo = null;
        return $html;
    }
}
