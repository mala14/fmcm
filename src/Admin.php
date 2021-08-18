<?php
/**
* Class file for admin content
*/
class Admin
{

		private $conn;
		public $numPerPage = 30;
		/* get database access */
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
							echo "<script>location.href = 'my_page.php';</script>";
							exit;
						}
				}
			$pdo = null;
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
						<a href='users_adm.php' title='Add users'><div class='side-lnk'><i class='fas fa-user-plus'></i><div class='side-lnk-text'>{$GLOBALS['addUsers']}</div></div></a>
						<a href='list_users.php' title='Edit users'><div class='side-lnk'><i class='fas fa-user-edit'></i><div class='side-lnk-text'>{$GLOBALS['editUsers']}</div></div></a>
						<a href='templates.php' title='Settings'><div class='side-lnk'><i class='far fa-edit'></i><div class='side-lnk-text'>{$GLOBALS['templates']}</div></div></a>
						<a href='settings.php' title='Settings'><div class='side-lnk'><i class='far fa-cog'></i><div class='side-lnk-text'>{$GLOBALS['settings']}</div></div></a>
						";
				}
			}
			$pdo = null;
			return $html;
		}

		/**
  	* Publish the moderator menu to moderators
    *
  	* @return
  	*/
		public function getModMenu()
		{
			$html = null;

			if(isset($_SESSION['uname'])){
				$user = $_SESSION['uname'];
				$stmt = $this->conn->prepare("SELECT type FROM fmcm_users WHERE uname = :user");
				$stmt->execute(array('user' => $user));
				$type = $stmt->fetch();
				if($user && $type['type'] === 'mod'){
						$html .= "
						<a href='templates.php' title='Settings'><div class='side-lnk'><i class='far fa-edit'></i><div class='side-lnk-text'>{$GLOBALS['templates']}</div></div></a>
						";
				}
			}
			$pdo = null;
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
				$fail = null;

				if(isset($_POST['addus'])){
						$uname = htmlentities($_POST['uname']);
						$type = $_POST['type'];
						$lname = htmlentities($_POST['lname']);
						$fname = htmlentities($_POST['fname']);
						$email = htmlentities($_POST['email']);
						$active = null;

						$timestamp = date('Y-m-d G:i:s');
						$password = strip_tags($_POST['password']);
						$confirmpwd = strip_tags($_POST['confirmpwd']);
						$hash = password_hash($password, PASSWORD_BCRYPT);

						if(empty($password)){
								$fail = "{$GLOBALS['emptyPassword']}";
						}
						if(empty($confirmpwd)){
								$fail = "{$GLOBALS['emptyConfPassword']}";
						}

						if(!empty($password) && !empty($confirmpwd)) {
								if($password === $confirmpwd){
										$stmt = $this->conn->prepare("INSERT INTO fmcm_users (uname, lname, fname, email, type, passwd, regdate, active) VALUES (:uname, :lname, :fname, :email, :type, :hash, :timestamp, :active)");
										$stmt->execute([$uname, $lname, $fname, $email, $type, $hash, $timestamp, $active]);
										$pdo = null;
										echo "<script>location.href = 'list_users.php';</script>";
										exit;
								}	else {
											$fail = "{$GLOBALS['passwordNoMatch']}";
									}
						}
				}
				$html .= "
						<div>{$fail}</div>
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
			$paging = null;

      if(isset($_GET['page'])) {
          $page = $_GET['page'];
      } else {
         $page = 1;
      }
      $startPage = ($page-1)*$this->numPerPage;

			$stmt = $this->conn->prepare("
			SELECT
					id_user,
					fname,
					lname,
					uname,
					type,
					active,
					lastlogin
			FROM fmcm_users
			LIMIT $startPage, $this->numPerPage");
			$stmt->execute();
      $res = $stmt->fetchAll();

			$rowsNr = $this->conn->prepare("SELECT * FROM fmcm_users");
      $rowsNr->execute();
      $rowCount = $rowsNr->rowCount();
      $maxPages = ceil($rowCount/$this->numPerPage);

      for($i=1;$i<=$maxPages;$i++) {
          $paging .= "<a class='page-nr' href='list_users.php?page=".$i."'>".$i."</a>";
      }

			foreach($res as $row){
					if($row['active'] === 'active'){
							$status = "<div class='active'>{$GLOBALS['userActive']}</div>";
					}	else {
							$status = "<div class='inactive'>{$GLOBALS['userDisabled']}</div>";
					}
					$html .= "
								<tbody>
										<tr class='case-row' data-href='users_edit.php?id={$row['id_user']}'>
												<td class='tbodyTd' title='Id {$row['id_user']}'>{$row['id_user']}</td>
												<td class='tbodyTd' title='{$row['uname']}'>{$row['uname']}</td>
												<td class='tbodyTd' title='{$GLOBALS['contacts']}'>{$row['fname']} {$row['lname']}</td>
												<td class='tbodyTd' title='{$row['type']}'>{$row['type']}</td>
												<td class='tbodyTd'> {$status} </td>
												<td class='tbodyTd colHide' title='{$row['lastlogin']}'>{$GLOBALS['lastLogin']}: {$row['lastlogin']}</td>
										</tr>
								</tbody>
					";
			}
			$html .= "
      <tfoot>
          <tr>
              <td colspan='6'>
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
						$pdo = null;
						echo "<script>location.href = 'list_users.php'</script>";
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
														<option value='mod'>{$GLOBALS['modType']}</option>
														<option value='usr'>{$GLOBALS['usrType']}</option>
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
				$msg = null;
				$html = null;

				if(isset($_POST['chUPwd'])){
						if(isset($_GET['id'])){
								$id = $_GET['id'];
								$password = strip_tags($_POST['password']);
								$confirmpwd = strip_tags($_POST['confirmpwd']);
								$hash = password_hash($password, PASSWORD_BCRYPT);
								if(empty($password)){
										$msg = "{$GLOBALS['emptyPassword']}";
								}
								if(empty($confirmpwd)){
										$msg = "{$GLOBALS['emptyConfPassword']}";
								}
								if(!empty($password) && !empty($confirmpwd)){
										if($password === $confirmpwd){
												$stmt = $this->conn->prepare("UPDATE fmcm_users SET passwd = :hash WHERE id_user = :id LIMIT 1");
												$stmt->execute([$hash, $id]);
												$success = "{$GLOBALS['passwordSuccess']}";
												$pdo = null;
										}
										else{
												$msg = "{$GLOBALS['passwordNoMatch']}";
										}
								}
						}
				}
				$html .= "
						<div>{$msg}</div>
				";
				return $html;
		}

		/**
  	*  Get mailhost name
    *
  	* @return
  	*/
		public function getMailHost()
    {
        $sql = $this->conn->prepare("SELECT mailhost FROM fmcm_settings");
        $sql->execute();
        $val = $sql->fetch();
				if(!empty($val['mailhost'])) {
					return $val['mailhost'];
				}
        $pdo = null;
    }

		public function getPassKey()
		{
				$sql = $this->conn->prepare("SELECT passkey FROM fmcm_settings");
				$sql->execute();
				$val = $sql->fetch();
				if(!empty($val['passkey'])) {
					return $val['passkey'];
				}
				$pdo = null;
		}

		/**
  	*  Get mail user name
    *
  	* @return
  	*/
		public function getMailUser()
    {
	      $sql = $this->conn->prepare("SELECT mailuser FROM fmcm_settings");
        $sql->execute();
        $val = $sql->fetch();
				if(!empty($val['mailuser'])) {
					return $val['mailuser'];
				}
        $pdo = null;
    }

		/**
  	*  Get mail set from
    *
  	* @return
  	*/
		public function getSetFrom()
    {
	      $sql = $this->conn->prepare("SELECT setfrom FROM fmcm_settings");
        $sql->execute();
        $val = $sql->fetch();
				if(!empty($val['setfrom'])) {
					return $val['setfrom'];
				}
        $pdo = null;
    }

		/**
  	*  Save mail user password encrypted to db
    *
  	* @return
  	*/
		public function saveMailPasswd()
    {
				$msg = null;
				if(isset($_POST['chmailpasswd'])){
						$password = strip_tags($_POST['mailpasswd']);
						$confirmpwd = strip_tags($_POST['confmailpasswd']);
						if(empty($password)){
								$msg = "{$GLOBALS['emptyPassword']}";
						}
						if(empty($confirmpwd)){
								$msg = "{$GLOBALS['emptyConfPassword']}";
						}
						if(!empty($password) && !empty($confirmpwd)){
								if($password === $confirmpwd){
										$encrypt = $this->encryptPassWd($password, $this->getPassKey());
										$stmt = $this->conn->prepare("UPDATE fmcm_settings SET mailpasswd = :encrypt");
										$stmt->execute([$encrypt]);
										$success = "{$GLOBALS['passwordSuccess']}";
										$pdo = null;
								}
								else {
										$msg = "{$GLOBALS['passwordNoMatch']}";
								}
						}

				}
				return $msg;
        $pdo = null;
    }
		public function encryptPassWd($data) {
				$password = strip_tags($_POST['mailpasswd']);
				$encryption_key = base64_decode($this->getPassKey());
				$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
				$encrypted = openssl_encrypt($password, 'aes-256-cbc', $encryption_key, 0, $iv);
				return base64_encode($encrypted . '::' . $iv);
		}

		public function decryptPassWd($data) {
				$encryption_key = base64_decode($this->getPassKey());
				list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($data), 2),2,null);
				return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
				}


		/**
  	* Decrypt mail pass for sending mail
  	*
  	* @return
  	*/
  	public function getMailPass()
    {
				$stmt = $this->conn->prepare("SELECT mailpasswd FROM fmcm_settings");
  			$stmt->execute();
  			$res = $stmt->fetch();
				while ($res) {
						return $this->decryptPassWd($res['mailpasswd'], $this->getPassKey());
				}
				$pdo = null;
  	}

		/**
  	*  Save mail configuration changes
    *
  	* @return
  	*/
		public function saveMailChanges()
		{
				$fail = null;
				if (isset($_POST['mailsave'])) {
						$mailhost = $_POST['mailhost'];
						$mailuser = $_POST['mailuser'];
						$setfrom = $_POST['setfrom'];
						$passkey = $_POST['passkey'];
						$sql = $this->conn->prepare("UPDATE fmcm_settings SET mailhost = :mailhost, mailuser = :mailuser, setfrom = :setfrom, passkey = :passkey");
						$sql->execute([$mailhost, $mailuser, $setfrom, $passkey]);
						echo "<script>location.href = 'settings.php';</script>";
						exit;
				}
				$pdo = null;
		}


}
