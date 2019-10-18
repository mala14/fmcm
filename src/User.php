<?php
/**
* Class for users
*/
class User{

    /* Properties */
    private $conn;

    /* Get database access */
    public function __construct($pdo)
    {
        $this->conn = $pdo;
    }

    /**
    * Add contacts to FMCM system
    *
    */
    public function addContact()
    {
        $html = null;
        $error = null;
        if(isset($_POST['add-user'])){
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $jtitle = $_POST['jtitle'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $office = $_POST['office'];
            $address = $_POST['address'];

            $email = $_POST['email'];
            $timestamp = date('Y-m-d G:i:s');
            if(empty($fname)){
                $error = "First name is required";
            }
            if(empty($lname)){
                $error = "Last name is required";
            }

            if(!empty($lname) && !empty($fname)){
                $stmt = $this->conn->prepare("INSERT INTO fmcm_contacts (con_fname, con_lname, con_jtitle, con_phone, con_email, con_office, con_address) VALUES (:fname, :lname, :jtitle, :phone, :email, :office, :address)");
                $stmt->execute([$fname, $lname, $jtitle, $phone, $email, $office, $address]);
                $stmt = null;
                header('Location: my_page.php');
                exit;
            }
        }
        $html .= "
            <form action='' method='post'>
                <div class='caseFormData'>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>First name: </div><input type='text' class='conInfo' name='fname' placeholder='{$error}'></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>Surname: </div><input type='text' class='conInfo' name='lname' placeholder='{$error}'></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>Job title: </div><input type='text' class='conInfo' name='jtitle'></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>Phone: </div><input type='text' class='conInfo' name='phone'></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>Email: </div><input type='text' class='conInfo' name='email'></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>Office: </div><input type='text' class='conInfo' name='office'></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>Address: </div><input type='text' class='conInfo' name='address'></div></div>
                </div>
                <input type='submit' name='add-user' value='Save'><input type='reset' value='Reset'>
            </form>
        ";
        return $html;
    }
	/*get users in db */

	// public function getOnlineUsers(){
	// 	$stmt = $this->conn->query("SELECT uname, type, time FROM mp_users");
	// 	$stmt->execute();
	// 	$html = null;
	// 	foreach($stmt as $val){
	// 		if(!empty($val['time']) && time() - $val['time'] < 18000 && $val['type'] === 'admin') {
	// 			$html .="<div id='uAdm'>{$val['uname']}</div>, ";
	// 		}
	// 		if(!empty($val['time']) && time() - $val['time'] < 18000 && $val['type'] === 'mod'){
	// 			$html .="<div id='uMod'>{$val['uname']}</div>, ";
	// 		}
	// 		if(!empty($val['time']) && time() - $val['time'] < 18000 && $val['type'] === 'usr'){
	// 			$html .="<div id='uUsr'>{$val['uname']}</div>, ";
	// 		}
	// 	}
	// 	return $html;
	// 	$stmt = null;
	// }
  //
	// public function getAmountUsrs(){
	// 	$html = null;
	// 	$stmt = $this->conn->prepare("SELECT count(*) FROM mp_users");
	// 	$stmt->execute();
	// 	$num_usrs = $stmt->fetchColumn();
	// 	/*
	// 		if($num_usrs > 0){
	// 			$html .= "{$num_usrs}";
	// 		}
	// 	*/
	// 	$sql = null;
	// 	$stmt = null;
	// 	return $html;
	// }

  /*
  *
  * Get username and present it on page
  *
  */

  	public function getName(){
    		$html = null;
    		$html .= "{$_SESSION['uname']}";
    		return $html;
  	}
  //
	// public function changeMyPwd(){
	// 	$noMatch = null;
	// 	$html = null;
	// 	$success = null;
	// 	$fail = null;
	// 	if(isset($_POST['chMyPwd'])){
	// 		$username = $_SESSION['uname'];
	// 		$password = $_POST['password'];
	// 		$confirmpwd = $_POST['confirmpwd'];
	// 		$hash = password_hash($password, PASSWORD_BCRYPT);
	// 		if(empty($password)){
	// 			$fail = "<div>Password field can not be empty!</div>";
	// 		}
	// 		if(empty($confirmpwd)){
	// 			$fail = "<div>Confirm field can not be empty!</div>";
	// 		}
	// 		if(!empty($password) && !empty($confirmpwd)){
	// 			if($password === $confirmpwd){
	// 				$stmt = $this->conn->prepare("UPDATE mp_users SET passwd = :hash WHERE uname = :username");
	// 				$stmt->execute(array('hash' => $hash, 'username' => $username));
	// 				$success = 'Password has been changed!';
	// 				$stmt = null;
	// 			}
	// 			else{
	// 			$noMatch = 'Passwords do not match!';
	// 		}
	// 		}
	// 	}
	// 	$html .= "
	// 		<div id='passWdForm'>
	// 			<div>
	// 				<div>
	// 				<form action='' method='post'>
	// 					<div>Change my password: </div>
	// 					<div id='passwd'><label>Password: </label><input type='password' id='chPassword' name='password' value='' placeholder='Password' autocomplete='off' /></div>
	// 					<div id='passwd'><label>Confirm: </label><input type='password' id='rePassword' name='confirmpwd' value='' placeholder='Confirm password' autocomplete='off' /></div>
	// 					<div>{$fail}{$noMatch}{$success}</div>
	// 					<div class=''><input type='submit' name='chMyPwd' value='Change'/><input type='reset' value='Reset'></div>
	// 				</form>
	// 				</div>
	// 			</div>
	// 		</div>
	// 		";
	// 	return $html;
	// }

}
