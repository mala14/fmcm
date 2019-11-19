<?php
/**
* Class file for admin content
*/
class Admin
{

		private $conn;

		public function __construct($pdo)
		{
				$this->conn = $pdo;
		}
		/**
		*  Check that user is admin
		*
		* @return
		*/
		public function IsAdmin()
		{
				if(isset($_SESSION['uname'])){
						$user = $_SESSION['uname'];
						$stmt = $this->conn->prepare("SELECT uname, type, active FROM fmcm_users WHERE uname = :user AND time IS NOT NULL");
						$stmt->execute(array('user' => $user));
						$type = $stmt->fetch();
						if($user && $type['type'] === 'admin'){

						}	else {
							header('Location: dashboard.php');
							exit;
						}
				}
			$stmt = null;
		}

		/**
  	* Publish the admin menu to administrators
    *
  	* @return
  	*/
		public function getAdmMenu()
		{
			$html = null;

			if(isset($_SESSION['uname'])){
				$user = $_SESSION['uname'];
				$stmt = $this->conn->prepare("SELECT type FROM fmcm_users WHERE uname = :user");
				$stmt->execute(array('user' => $user));
				$type = $stmt->fetch();
				if($user && $type['type'] === 'admin'){
						$html .= "
								<a href='users_adm.php' title='Users' ><div class='side-lnk'>{$GLOBALS['addUsers']}</div></a>
								<a href='list_users.php' title='Users' ><div class='side-lnk'>{$GLOBALS['editUsers']}</div></a>
						";
				}
			}
			$stmt = null;
			return $html;
		}

		/**
  	* Add users to system
    *
  	* @return
  	*/
		public function addUsers()
		{
				$html = null;
				$error = null;

				if(isset($_POST['addus'])){
						$uname = htmlentities($_POST['uname']);
						$type = $_POST['type'];
						$lname = htmlentities($_POST['lname']);
						$fname = htmlentities($_POST['fname']);
						$email = htmlentities($_POST['email']);
						$active = "active";
						$timestamp = date('Y-m-d G:i:s');

						if(empty($uname)){
							$error = "Username is required";
						}
						if(empty($fname)){
							$error = "First name is required";
						}
						if(empty($lname)){
							$error = "Last name is required";
						}
						if(empty($email)){
							$error = "Email is required";
						}

						if(!empty($uname) && !empty($lname) && !empty($fname) && !empty($email)){
						$stmt = $this->conn->prepare("INSERT INTO fmcm_users (uname, lname, fname, email, type, regdate, active) VALUES (:uname, :lname, :fname, :email, :type, :timestamp, :active)");
						$stmt->execute([$uname, $lname, $fname, $email, $type, $timestamp, $active]);
						$stmt = null;
						header('Location: users_adm.php');
						exit;
						}

				}
				$html .= "
						<form action='' method='post'>
								<div id='addUtbl'>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['username']}: </div><div class='editUserField'><input type='text' name='uname' placeholder='{$error}'></div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['name']}: </div><div class='editUserField'><input type='text' name='fname' placeholder='{$error}'></div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['surname']}: </div><div class='editUserField'><input type='text' name='lname' placeholder='{$error}'></div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['email']}: </div><div class='editUserField'><input type='text' name='email' placeholder='{$error}'></div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['type']}: </div><div class='editUserField'>
												<select name='type' class='editUserField'>
														<option value='usr'>{$GLOBALS['usrType']}</option>
														<option value='admin'>{$GLOBALS['adminType']}</option>
												</select>
										</div></div></div>
										<div class='addUser'><input type='submit' name='addus' value='{$GLOBALS['save']}'><input type='reset' value='{$GLOBALS['reset']}'></div>
								</div>
						</form>
				";
				return $html;
		}

		/**
  	*  List all users for edit
    *
  	* @return
  	*/
		public function editUsers()
		{
			$html = null;
			$inactive = null;
			$stmt = $this->conn->prepare("SELECT id_user, fname, lname, uname, type, active, lastlogin FROM fmcm_users");
			$stmt->execute();
			foreach($stmt as $row){
					if($row['active'] === 'active'){
							$status = "<div class='active'>Active</div>";
					}	else {
							$status = "<div class='inactive'>Disabled</div>";
					}
					$html .= "
								<table class='tableCase tableUser'>
										<tr class='case-row case-row-left' data-href='users_edit.php?id={$row['id_user']}'>
												<td class='idname pad-left-right' title='Id {$row['id_user']}'>{$row['id_user']}</td>
												<td class='iduname pad-left-right' title='{$row['uname']}'>{$row['uname']}</td>
												<td class='iduname pad-left-right' title='{$row['fname']}'>{$row['fname']}</td>
												<td class='iduname pad-left-right' title='{$row['lname']}'>{$row['lname']}</td>
												<td class='idtype pad-left-right' title='{$row['type']}'>{$row['type']}</td>
												<td> {$status} </td>
												<td class='idlast pad-left-right' title='{$row['lastlogin']}'>Last login: {$row['lastlogin']}</td>
												<td class='idedit pad-left-right' title='Edit'><a class='uedit' href='users_edit.php?id={$row['id_user']}'>{$GLOBALS['userEdit']}</a></td>
										</tr>
								</table>

					";
			}
			$stmt = null;
			return $html;
		}

		/**
  	*  Edit selected user
    *
  	* @return
  	*/
		public function getEditUsr()
		{
				$html = null;
				$fail = null;

				$id = $_GET['id'];
				$stmt = $this->conn->prepare("SELECT id_user, fname, lname, uname, email, type, active FROM fmcm_users WHERE id_user = :id");
				$stmt->execute([$id]);
				$val = $stmt->fetch();

				if(isset($_POST['update'])){
						$id = $_POST['id'];
						$uname = htmlentities($_POST['uname']);
						$lname = htmlentities($_POST['lname']);
						$fname = htmlentities($_POST['fname']);
						$email = htmlentities($_POST['email']);
						$type = $_POST['type'];
						$active = $_POST['active'];
						$stmt = $this->conn->prepare("UPDATE fmcm_users SET uname = :uname, type = :type, lname = :lname, fname = :fname, email = :email, active = :active  WHERE id_user = :id LIMIT 1");
						$stmt->execute([$uname, $type, $lname, $fname, $email, $active, $id]);
						$stmt = null;
						var_dump($stmt);
						header('Location: list_users.php');
						exit;
					}
						$html .= "
								<form method='post'>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['username']}: </div><div class='editUserField'><input type='text' name='uname' value='{$val['uname']}' required></div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['surname']}: </div><div class='editUserField'><input type='text' name='lname' value='{$val['lname']}' required></div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['firstName']}: </div><div class='editUserField'><input type='text' name='fname' value='{$val['fname']}' required></div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['email']}: </div><div class='editUserField'><input type='text'  name='email' value='{$val['email']}' required></div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['type']}: </div><div class='editUserField'>
												<select class='editUserField' name='type'>
														<option value='{$val['type']}'>{$val['type']}</option>
														<option value='admin'>{$GLOBALS['adminType']}</option>
														<option value='usr'>{$GLOBALS['userType']}</option>
												</select>
										</div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['status']}: </div><div class='editUserField'>
												<select class='editUserField' name='active'>
														<option value='{$val['active']}'>{$val['active']}</option>
														<option value='active'>{$GLOBALS['userActive']}</option>
														<option value='disabled'>{$GLOBALS['userDisabled']}</option>
												</select>
										</div></div></div>
										<div><input type='hidden' name='id' value='{$val['id_user']}'></div>
										<div class='addUser'><input type='submit' name='update' value='{$GLOBALS['updateUser']}'><input type='reset' value='{$GLOBALS['reset']}'></div>
										<div>{$fail}</div>
							</form>
						";
				return $html;
				$pdo = null;
		}

		/**
  	*  Change password
    *
  	* @return
  	*/
		public function changePassword() {
				$fail = null;
				$html = null;

				if(isset($_POST['chUPwd'])){
						if(isset($_GET['id'])){
						$id = $_GET['id'];
						$password = strip_tags($_POST['password']);
						$confirmpwd = strip_tags($_POST['confirmpwd']);
						$hash = password_hash($password, PASSWORD_BCRYPT);
						if(empty($password)){
								$fail = "<div>Password field can not be empty!</div>";
						}
						if(empty($confirmpwd)){
								$fail = "<div>Confirm field can not be empty!</div>";
						}
						if(!empty($password) && !empty($confirmpwd)){
								if($password === $confirmpwd){
										$stmt = $this->conn->prepare("UPDATE fmcm_users SET passwd = :hash WHERE id_user = :id LIMIT 1");
										$stmt->execute([$hash, $id]);
										$success = 'Password has been changed!';
										$pdo = null;
								}
								else{
										$fail = 'Passwords do not match!';
								}
						}
						}
				}
				$html .= "
						<form method='post'>
							<div class='passWdTbl'><div class='passwdBox'>{$GLOBALS['setPassWord']}: </div><input type='password' name='password' value='' placeholder='Password' autocomplete='off' /></div>
							<div class='passWdTbl'><div class='passwdBox'>{$GLOBALS['confPassWord']}: </div><input type='password' name='confirmpwd' value='' placeholder='Confirm password' autocomplete='off' /></div>
							<div>{$fail}</div>
							<div class=''><input type='submit' name='chUPwd' value='{$GLOBALS['save']}'/> <input type='reset' value='{$GLOBALS['reset']}'></div>
						</form>
				";
				return $html;
		}
}
