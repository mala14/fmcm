<?php
/**
* Class for users
*/
class User Extends Todo
{

    /* Properties */
    private $conn;

    /**
    * Get database access
    *
    * @return
    */
    public function __construct($pdo)
    {
        $this->conn = $pdo;
    }

    /**
    * Get username and display it on page
    *
    * @return
    */
    public function getName(){
    		$html = null;
    		$html .= "{$_SESSION['uname']}";
    		return $html;
    }

    /**
    * Add contacts to FMCM system
    *
    * @return
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

            if(!empty($lname) && !empty($fname)){
                $stmt = $this->conn->prepare("INSERT INTO fmtodo_contacts (con_fname, con_lname, con_jtitle, con_phone, con_email, con_office, con_address) VALUES (:fname, :lname, :jtitle, :phone, :email, :office, :address)");
                $stmt->execute([$fname, $lname, $jtitle, $phone, $email, $office, $address]);
                $stmt = null;
                echo "<script>location.href = 'my_page.php'</script>";
                exit;
            }
        }
        return $html;
    }

    /**
  	*  List all contacts for edit
    *
  	* @return
  	*/
		public function editContacts()
		{
			$html = null;
			$inactive = null;
			$stmt = $this->conn->prepare("SELECT id_contact, con_fname, con_lname, con_email, con_address, con_office, con_jtitle, con_phone FROM fmtodo_contacts");
			$stmt->execute();
			foreach($stmt as $row){
					$html .= "
								<table class='tableCase tableUser'>
										<tr class='case-row case-row-left' data-href='contact_edit.php?id={$row['id_contact']}'>
												<td class='pad-left-right' title='Id {$row['id_contact']}'>{$row['id_contact']}</td>
												<td class='pad-left-right' title='{$row['con_fname']}'>{$row['con_fname']}</td>
												<td class='pad-left-right' title='{$row['con_lname']}'>{$row['con_lname']}</td>
                        <td class='pad-left-right' title='{$row['con_email']}'>{$row['con_email']}</td>
                        <td class='pad-left-right' title='{$row['con_jtitle']}'>{$row['con_jtitle']}</td>
												<td class='idedit pad-left-right' title='Edit'><a class='uedit' href='contact_edit.php?id={$row['id_contact']}'>{$GLOBALS['userEdit']}</a></td>
										</tr>
								</table>
					";
			}
			$pdo = null;
			return $html;
		}

    /**
  	*  Edit selected user
    *
  	* @return
  	*/
		public function getEditContact()
		{
				$html = null;
				$fail = null;

				$id = $_GET['id'];
				$stmt = $this->conn->prepare("SELECT id_contact, con_fname, con_lname, con_email, con_address, con_office, con_jtitle, con_phone FROM fmtodo_contacts WHERE id_contact = :id");
				$stmt->execute([$id]);
				$val = $stmt->fetch();

				if(isset($_POST['conedit'])){
						$id = $_POST['idcon'];
						$fname = htmlentities($_POST['fname']);
						$lname = htmlentities($_POST['lname']);
						$email = htmlentities($_POST['email']);
						$address = htmlentities($_POST['address']);
            $office = htmlentities($_POST['office']);
						$jtitle = htmlentities($_POST['jtitle']);
						$phone = htmlentities($_POST['phone']);
						$stmt = $this->conn->prepare("
                UPDATE fmtodo_contacts
                SET
                    con_fname = :fname,
                    con_lname = :lname,
                    con_email = :email,
                    con_address = :address,
                    con_office = :office,
                    con_jtitle = :jtitle,
                    con_phone = :phone
                WHERE
                    id_contact = :id
                LIMIT 1
                ");
						$stmt->execute([$fname, $lname, $email, $address, $office, $jtitle, $phone, $id]);
						$pdo = null;
						echo "<script>location.href = 'list_contacts.php'</script>";
						exit;
					}
						$html .= "
								<form method='post'>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['firstName']}: </div><div class='editUserField'><input type='text' name='fname' value='{$val['con_fname']}' required></div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['surname']}: </div><div class='editUserField'><input type='text' name='lname' value='{$val['con_lname']}' required></div></div></div>
										<div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['email']}: </div><div class='editUserField'><input type='text'  name='email' value='{$val['con_email']}' required></div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['address']}: </div><div class='editUserField'><input type='text' name='address' value='{$val['con_address']}' required></div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['office']}: </div><div class='editUserField'><input type='text' name='office' value='{$val['con_office']}' required></div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['jobTitle']}: </div><div class='editUserField'><input type='text' name='jtitle' value='{$val['con_jtitle']}' required></div></div></div>
                    <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['phone']}: </div><div class='editUserField'><input type='text' name='phone' value='{$val['con_phone']}' required></div></div></div>
										<div><input type='hidden' name='idcon' value='{$val['id_contact']}'></div>
										<div class='addUser'><input type='submit' name='conedit' value='{$GLOBALS['updateUser']}'><input type='reset' value='{$GLOBALS['reset']}'></div>
										<div>{$fail}</div>
							</form>
						";
				return $html;
		}
}
