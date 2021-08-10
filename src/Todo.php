<?php
/**
* Class for cases/todo
*
*/
class Todo
{

    /* Properties */
    private $conn;
    public $numPerPage = 30;
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
        $id = $this->getId();
        $sql = $this->conn->prepare("SELECT status FROM v_fmcm_caseinfo WHERE case_id = :id");
        $sql->execute([$id]);
        $res = $sql->fetch();
        if (isset($id) && $res['status'] === 'Active') {
            $closed = $this->closeCase();
        } else {
            $closed = "
                <form method='post'>
                    <input type='submit' name='closeCase' class='saveCase' title='Close case' value='{$GLOBALS['closeCase']}' disabled>
                </form>";
        }
        if (isset($id) && $res['status'] === 'Closed') {
            $open = $this->openCase();
        } else {
            $open = "
            <form method='post'>
                <input type='submit' name='closeCase' class='saveCase' title='Close case' value='{$GLOBALS['openCase']}' disabled>
            </form>";
        }

        $html = null;
        if (isset($_SESSION['uname'])) {
            $html .= "
                <td class='nav-item'>
                   <a class='nav-link' href='logout.php' title='Logout'>{$GLOBALS['logOutSubmit']}</a>
                </td>
                <td class='nav-item'>
                  <div class='dropdown'>
                      <div class='nav-link'>{$GLOBALS['navNew']}</div>
                          <div class='dropdown-content'>
                              <a href='new_contact.php' title='New customer'>{$GLOBALS['contact']}</a>
                              <a href='new_case.php' title='New case'>{$GLOBALS['navCase']}</a>
                          </div>
                  </div>
                </td>
                <td class='nav-item'>
                  <div class='dropdown'>
                      <div class='nav-link'>{$GLOBALS['navCase']}</div>
                      <div class='dropdown-content'>
                        {$closed}
                        {$open}
                      </div>
                  </div>
                </td>
                <td class='nav-item'>
                    <a class='nav-link' href='search.php' title='Search'><i class='fal fa-search fa-sm'></i></a>
                </td>
            ";
        }
        $pdo = null;
        return $html;
    }

    /**
    * Add new case
    * @return
    */
    public function addCase(){
  		$html = null;
  		$msg = null;
  		$search = "<i class='fas fa-search'></i>";

  		if(isset($_POST['sendCase'])){
          $name = $_SESSION['uname'] ?? null;
    			$issue = htmlspecialchars_decode($_POST['commtext']);
    			$title = htmlspecialchars_decode($_POST['issuetitle']);
    			$created = date('Y-m-d G:H:i');
          $contacts = $_POST['addContact'];
          $status = 'Active';

    			if (empty($title)){
    				  $msg .= "{$GLOBALS['caseEmptyTitle']}";
    			}
    			if (empty($issue)){
    				  $msg .= "{$GLOBALS['caseEmptyMessage']}";
    			}
          if (empty($contacts)) {
              $msg .= "{$GLOBALS['caseEmptyContact']}";
          }
    			if(!empty($title) && !empty($issue) && !empty($contacts)){
      				$stmt = $this->conn->prepare("INSERT INTO fmcm_todo (name, title, issue, created, status, contacts) VALUES (:name, :title, :issue, :created, :status, :contacts)");
      				$stmt->execute([$name, $title, $issue, $created, $status, $contacts]);
      				$msg .= "Message saved";
      				$stmt = null;
              echo "<script>location.href = 'new_case.php'</script>";
              exit;
    			}
  		}
  		$html .= "
  				<div>{$msg}</div>
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
                    $result .= "{$GLOBALS['emptySearch']}";
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
      $paging = null;

      if(isset($_GET['page'])) {
          $page = $_GET['page'];
      } else {
         $page = 1;
      }
      $startPage = ($page-1)*$this->numPerPage;

  		$sql = $this->conn->prepare("
      SELECT
          created,
          case_id,
          contact,
          title,
          assigned
      FROM
          v_fmcm_caseinfo
      WHERE
          status = 'Closed' ORDER BY created ASC
      LIMIT $startPage, $this->numPerPage");
      $sql->execute();
      $res = $sql->fetchAll();

      $rowsNr = $this->conn->prepare("SELECT * FROM v_fmcm_caseinfo WHERE status = 'Closed'");
      $rowsNr->execute();
      $rowCount = $rowsNr->rowCount();
      $maxPages = ceil($rowCount/$this->numPerPage);

      for($i=1;$i<=$maxPages;$i++) {
          $paging .= "<a class='page-nr' href='closedcases.php?page=".$i."'>".$i."</a>";
      }

      foreach ($res as $val) {
          $cutCreated = substr($val['created'], 0, 10);
          $html .= "
              <tbody>
                  <tr class='case-row' data-href='case.php?id={$val['case_id']}'>
                      <td class='tbodyTd'>{$cutCreated}</td>
                      <td class='tbodyTd'>{$val['case_id']}</td>
                      <td class='tbodyTd'>{$val['contact']}</td>
                      <td class='tbodyTd'>{$val['title']}</td>
                      <td class='tbodyTd colHide'>{$val['assigned']}</td>
                  </tr>
              </tbody>
          ";
      }
      $html .= "
      <tfoot>
          <tr>
              <td colspan='5'>
                  <li class='page-item'>
                      {$paging}
                  </li>
              </td>
          </tr>
      </tfoot>";

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
      $paging = null;

      if(isset($_GET['page'])) {
          $page = $_GET['page'];
      } else {
         $page = 1;
      }
      $startPage = ($page-1)*$this->numPerPage;

      $sql = $this->conn->prepare("
      SELECT
          created,
          case_id,
          contact,
          title,
          assigned
      FROM v_fmcm_caseinfo
      WHERE
          status = 'Active' ORDER BY created ASC
      LIMIT $startPage, $this->numPerPage");
      $sql->execute();
      $res = $sql->fetchAll();

      $rowsNr = $this->conn->prepare("SELECT * FROM v_fmcm_caseinfo WHERE status = 'Active'");
      $rowsNr->execute();
      $rowCount = $rowsNr->rowCount();
      $maxPages = ceil($rowCount/$this->numPerPage);

      for($i=1;$i<=$maxPages;$i++) {
          $paging .= "<a class='page-nr' href='allcases.php?page=".$i."'>".$i."</a>";
      }

      foreach ($res as $val) {
          $cutCreated = substr($val['created'], 0, 10);
          $html .= "
              <tbody>
                  <tr class='case-row' data-href='case.php?id={$val['case_id']}'>
                      <td class='tbodyTd'>{$cutCreated}</td>
                      <td class='tbodyTd'>{$val['case_id']}</td>
                      <td class='tbodyTd'>{$val['contact']}</td>
                      <td class='tbodyTd'>{$val['title']}</td>
                      <td class='tbodyTd colHide'>{$val['assigned']}</td>
                  </tr>
              </tbody>
          ";
      }

      $html .= "
          <tfoot>
              <tr>
                  <td colspan='5'>
                      <li class='page-item'>
                          {$paging}
                      </li>
                  </td>
              </tr>
          </tfoot>
      ";
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
        $paging = null;

        if(isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
           $page = 1;
        }
        $startPage = ($page-1)*$this->numPerPage;

    		$uname = $_SESSION['uname'];
        $status = 'Active';
    		$sql = $this->conn->prepare("
        SELECT
            created,
            case_id,
            contact,
            title,
            assigned
        FROM
            v_fmcm_caseinfo
        WHERE
            status = :status
        AND
            assigned = :uname ORDER BY created ASC
        LIMIT $startPage, $this->numPerPage
        ");

        $sql->execute([$status, $uname]);
        $res = $sql->fetchAll();

        $rowsNr = $this->conn->prepare("SELECT * FROM v_fmcm_caseinfo WHERE status = 'Active' AND assigned = :uname");
        $rowsNr->execute([$uname]);
        $rowCount = $rowsNr->rowCount();
        $maxPages = ceil($rowCount/$this->numPerPage);

        for($i=1;$i<=$maxPages;$i++) {
            $paging .= "<a class='page-nr' href='my_page.php?page=".$i."'>".$i."</a>";
        }

        foreach ($res as $val) {
            $cutCreated = substr($val['created'], 0, 10);
            $html .= "
                <tbody>
                    <tr class='case-row' data-href='case.php?id={$val['case_id']}'>
                        <td class='tbodyTd'>{$cutCreated}</td>
                        <td class='tbodyTd'>{$val['case_id']}</td>
                        <td class='tbodyTd'>{$val['contact']}</td>
                        <td class='tbodyTd'>{$val['title']}</td>
                        <td class='tbodyTd colHide'>{$val['assigned']}</td>
                    </tr>
                </tbody>
            ";
        }

        $html .= "
            <tfoot>
                <tr>
                    <td colspan='5'>
                        <li class='page-item'>
                            {$paging}
                        </li>
                    </td>
                </tr>
            </tfoot>
        ";
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
        $sql = $this->conn->prepare("SELECT fname, lname, uname FROM fmcm_users WHERE active = 'active'");
        $sql->execute();
        $res = $sql->fetchAll();
        if (isset($_POST['updateCaseInfo'])) {
            if (isset($_POST['assigned'])) {
                if (empty($_POST['assigned'])) {
                    $engineer = NULL;
                } else {
                    $engineer = $_POST['assigned'];
                }
                $sql = $this->conn->prepare("UPDATE fmcm_todo SET assigned = :engineer WHERE id = :id LIMIT 1");
                $sql->execute([$engineer, $this->getId()]);
                echo "<script>location.href = ''</script>";
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
                $error = "{$GLOBALS['noComment']}";
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
            echo "<script>location.href = ''</script>";
            exit;
        }
        $html .= "
            <form method='post'>
                <input type='submit' name='closeCase' class='saveCase' title='Close case' value='{$GLOBALS['closeCase']}'>
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
            echo "<script>location.href = ''</script>";
            exit;
        }

        $html .= "
            <form method='post'>
                <input type='submit' name='openCase' class='saveCase' title='Open case' value='{$GLOBALS['openCase']}'>
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
        return htmlspecialchars_decode($res['issue']);
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
    public function showCases()
    {
      $sql = "
        SELECT
            created,
            case_id,
            contact,
            title,
            assigned
        FROM
            v_fmcm_caseinfo
        WHERE
            status = :status ORDER BY case_id ASC
        ";
      return $sql;
    }

    /**
  	* Main search. The search of cases, by case id, contact, phone number etc.
  	*
  	* @return
  	*/
    public function getSearchRes()
    {
        $html = null;
        $result = null;
        $search = null;
        $paging = null;

        if(isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
           $page = 1;
        }
        $startPage = ($page-1)*$this->numPerPage;

        if (isset($_POST['mainSearch'])) {
            $search = $_POST['mainSearch'];
        }
        $sql = $this->conn->prepare("
        SELECT *
        FROM
            v_fmcm_caseinfo
        WHERE
            case_id LIKE ?
            OR created LIKE ?
            OR contact LIKE ?
            OR title LIKE ?
            OR assigned LIKE ?
            OR email LIKE ?
            OR phone LIKE ?
        ORDER BY case_id ASC
        LIMIT $startPage, $this->numPerPage
        ");
        $sql->execute(["%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%"]);
        $result = $sql->fetchAll();

        // pagination starts here
        $rowsNr = $this->conn->prepare("
        SELECT *
        FROM v_fmcm_caseinfo
        WHERE
            case_id LIKE ?
            OR created LIKE ?
            OR contact LIKE ?
            OR title LIKE ?
            OR assigned LIKE ?
            OR email LIKE ?
            OR phone LIKE ?
        ");
        $rowsNr->execute(["%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%", "%$search%"]);
        $rowCount = $rowsNr->rowCount();
        $maxPages = ceil($rowCount/$this->numPerPage);
        for($i=1;$i<=$maxPages;$i++) {
            $paging .= "<a class='page-nr' href='search.php?page=".$i."'>".$i."</a>";
        }

        foreach ($result as $row) {
          $cutCreated = substr($row['created'], 0, 10);
          $html .= "
              <tbody>
                  <tr class='case-row' data-href='case.php?id={$row['case_id']}'>
                      <td class='tbodyTd'>{$cutCreated}</td>
                      <td class='tbodyTd'>{$row['case_id']}</td>
                      <td class='tbodyTd'>{$row['contact']}</td>
                      <td class='tbodyTd'>{$row['title']}</td>
                      <td class='tbodyTd colHide'>{$row['assigned']}</td>
                  </tr>
              </tbody>
          ";
        }
        $html .= "
        <tfoot>
            <tr>
                <td colspan='5'>
                    <li class='page-item'>
                        {$paging}
                    </li>
                </td>
            </tr>
        </tfoot>";
        $pdo = null;
        return $html;
    }
}
