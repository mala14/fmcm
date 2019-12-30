<?php
/**
* Class for users
*/
class User
{

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
                $error = "{$GLOBALS['emptyFname']}";
            }
            if(empty($lname)){
                $error = "{$GLOBALS['emptyLname']}";
            }

            if(!empty($lname) && !empty($fname)){
                $stmt = $this->conn->prepare("INSERT INTO fmcm_contacts (con_fname, con_lname, con_jtitle, con_phone, con_email, con_office, con_address) VALUES (:fname, :lname, :jtitle, :phone, :email, :office, :address)");
                $stmt->execute([$fname, $lname, $jtitle, $phone, $email, $office, $address]);
                $stmt = null;
                $html .= "<script>location.href = 'my_page.php'</script>";
            }
        }
        $html .= "
            <form action='' method='post'>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['name']}: </div><input type='text' class='conInfo' name='fname' placeholder='{$error}'></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['surname']}: </div><input type='text' class='conInfo' name='lname' placeholder='{$error}'></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['jobTitle']}: </div><input type='text' class='conInfo' name='jtitle'></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['phone']}: </div><input type='text' class='conInfo' name='phone'></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['email']}: </div><input type='text' class='conInfo' name='email'></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['office']}: </div><input type='text' class='conInfo' name='office'></div></div>
                <div class='usrInfoTbl'><div class='formdata'><div class='caseInfoTitle'>{$GLOBALS['address']}: </div><input type='text' class='conInfo' name='address'></div></div>
                <input type='submit' name='add-user' value='{$GLOBALS['save']}'><input type='reset' value='{$GLOBALS['reset']}'>
            </form>
        ";
        return $html;
    }

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
}
