<?php
/**
* Class for login
*/
class Login
{
    /**
     *
     */
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
        isset($user) or die (header('Location: index.php'));
	  }

  	/* login form */
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
    				$sql = $this->conn->prepare("UPDATE fmcm_users SET lastlogin = :timestp, status = :status, time = :ltime WHERE uname = :username");
    				$sql->execute([$timestp, $status, $ltime, $username]);
    				header('Location: my_page.php');
    				exit;
  			} else {
            $_SESSION['login-error'] = "<p class='loginErr'>Wrong User Name or password</p>";
            $error = $_SESSION['login-error'];
            unset($_SESSION['login-error']);
  			  }
    				$pdo = null;
    		}
          $html .= "
              <div class=''>
                  <input type='text' class='form-login' name='username' placeholder='User name' required='required' autofocus='autofocus'>
              </div>
              <div class=''>
                  <input type='password' class='form-login' name='password' placeholder='Password' required='required'>
              </div>
              <button type='submit' name='login' class='loginBtn'>Submit</button>
              {$error}
          ";
    		  return $html;
  	}
    // Set login time and make account inactive/signed out after time has ended.
  	public function getSessions()
    {
    		if(isset($_SESSION['uname'])){
      			$mySession = $_SESSION['uname'];
          			if(time() - $_SESSION['time'] > 18000){
            				$stmt = $this->conn->prepare("UPDATE fmcm_users SET time = NULL WHERE uname = :mySession");
            				$stmt->execute([$mySession]);
            				$stmt = null;
            				session_destroy();
            				unset($_SESSION['uname']);
          			}
    		}
  	}
    /**
    * The logout method
    */
  	public function uLogout()
    {
    		$html = null;
      		if(isset($_POST['logout'])){
        			if(isset($_SESSION['uname'])){
        				$status = 0;
        				$mySession = $_SESSION['uname'];
        				$stmt = $this->conn->prepare("UPDATE fmcm_users SET status = :status, time = NULL WHERE uname = :mySession");
        				$stmt->execute([$mySession, $status]);
        				$pdo = null;
        			}
        			session_destroy();
        			unset($_SESSION['uname']);
        			header('Location: index.php');
        			exit;
      		}

  			$html .= "
            <button type='submit' class='loginBtn' name='logout'>Logout</button>
  			";
  			return $html;
  	}
}
