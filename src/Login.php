<?php

/**
* Class for login
*/
class Login
{
		/* Properties */
    private $conn;

    /* Get database access */
    public function __construct($pdo)
    {
        $this->conn = $pdo;
    }

   //Check login status, if not then redirect to login page.
    public function checkLogin()
    {
        $user = isset($_SESSION['uname']) ? $_SESSION['uname'] : null;
				isset($user) or die("<script>window.location.href = 'index.php'</script>");
	  }

		/**
  	* The login method
  	*
  	* @return
  	*/
  	public function uLogin()
    {
    		$error = null;
    		$html = null;
    		if(isset($_POST['login'])){
      			$username = strip_tags(trim($_POST['username']));
      			$password = strip_tags(trim($_POST['password']));
      			$hash = password_hash('$password', PASSWORD_BCRYPT);
      			$stmt = $this->conn->prepare("SELECT * FROM fmcm_users WHERE uname = :username AND active = 'active' LIMIT 1");
      			$stmt->execute([$username]);
      			$user = $stmt->fetch();

      			if($user && password_verify($password, $user['passwd'])){
        				$_SESSION['uname'] = $username;
        				$_SESSION['time'] = time();
        				$ltime = time();
        				$status = 1;
        				$timestp = date('Y-m-d G:i:s');
        				$sql = $this->conn->prepare("UPDATE fmcm_users SET lastlogin = :timestp, status = :status, sesstime = :ltime WHERE uname = :username");
        				$sql->execute([$timestp, $status, $ltime, $username]);
								echo "<script>window.location.href = 'my_page.php'</script>";
      			}
    				$pdo = null;
            exit;
    		}
          $html .= "{$error}";
    		  return $html;
  	}

		/**
  	* Set login time and make account inactive/signed out after time has ended.
  	*
  	* @return
  	*/
  	public function getSessions()
    {
    		if(isset($_SESSION['uname'])){
      			$mySession = $_SESSION['uname'];
      			if(time() - $_SESSION['time'] > 18000){
        				$stmt = $this->conn->prepare("UPDATE fmcm_users SET sesstime = NULL WHERE uname = :mySession");
        				$stmt->execute([$mySession]);
        				$stmt = null;
        				session_destroy();
        				unset($_SESSION['uname']);
      			}
    		}
  	}

		/**
  	* The log out method
  	*
  	* @return
  	*/
  	public function uLogout()
    {
				$html = null;
				if(isset($_POST['logout'])){
						if(isset($_SESSION['uname'])){
								$status = 0;
								$mySession = $_SESSION['uname'];
								$stmt = $this->conn->prepare("UPDATE fmcm_users SET status = :status, sesstime = NULL WHERE uname = :mySession");
								$stmt->execute([$mySession, $status]);
								$pdo = null;
								session_destroy();
								unset($_SESSION['uname']);
						}
						echo "<script>window.location.href = 'index.php'</script>";
            exit;
				}
				$html .= "<button type='submit' class='loginBtn' name='logout'>{$GLOBALS['logOutSubmit']}</button>";
				return $html;
  	}
}
