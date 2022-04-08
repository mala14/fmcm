<?php
/**
* Class for top navigation bar
*/
class Navbar Extends Todo
{
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
    public function getCaseId()
    {
        $caseId = $_GET['caseId'] ?? null;
        return $caseId;
    }
    /**
    * Display navbar if logged in.
    *
    * @return
    */
    public function getNavBar()
    {
        $close = null;
        $open = null;
        $id = $this->getCaseId();
        $sql = $this->conn->prepare("SELECT status FROM v_fmcm_caseinfo WHERE case_id = :id");
        $sql->execute([$id]);
        $res = $sql->fetch();

        if (isset($id) && $res['status'] == 'Active') {
            $close = $this->closeCase();
        } else {
            $close = "
            <form method='post'>
                <input type='submit' name='closeCase' class='saveCase' title='Close case' value='{$GLOBALS['closeCase']}' disabled>
            </form>";
            }
        if (isset($id) && $res['status'] == 'Closed') {
            $open = $this->openCase();
        } else {
            $open = "
            <form method='post'>
                <input type='submit' name='closeCase' class='saveCase' title='Open case' value='{$GLOBALS['openCase']}' disabled>
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
                        {$open}
                        {$close}
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
    * Open case.
    *
    * @return
    */
    public function openCase() {
        $html = null;
        $uname = $_SESSION['uname'];
        $dateStamp = date('Y-m-d H:i:s');
        $id = $this->getCaseId();
        $status = 'Active';
        if (isset($_POST['openCase'])) {
            $sql = $this->conn->prepare("UPDATE fmcm_todo SET status = :status, closedby = null, fixed = null WHERE id = :id LIMIT 1");
            $sql->execute([$status, $id]);
            echo "<script>window.location.href = ''</script>";
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
    * Closed case.
    *
    * @return
    */
    public function closeCase() {
        $html = null;
        $uname = $_SESSION['uname'];
        $dateStamp = date('Y-m-d H:i:s');
        $id = $this->getCaseId();
        $status = 'Closed';

        if (isset($_POST['closeCase'])) {
            $sql = $this->conn->prepare("UPDATE fmcm_todo SET status = :status, closedby = :uname, fixed = :dateStamp WHERE id = :id LIMIT 1");
            $sql->execute([$status, $uname, $dateStamp, $id]);
            echo "<script>window.location.href = ''</script>";
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
}
