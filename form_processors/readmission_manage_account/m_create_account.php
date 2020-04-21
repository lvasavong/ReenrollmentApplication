<?php
//*******************************************************************************************************
//	m_create_account.php 
//
//	Author: Linda Vasavong
//	Date Created: ????
//	Date Modified: Linda Vasavong
//*******************************************************************************************************

require_once('class_library/database_drivers/MySQLDriver.php');
require_once('class_library/Common.php');
include_once('NetIDAuth.php');

$common = new Common();
$auth = new NetIDAuth();

$errors = array();
$error_messages = array();;

$status = "";
$canProcess = true;
$valid = true;

if(isset($_POST['createAccount']))
{
    $formData = $_POST;
    $errors = Validate($formData);
    if(empty($errors) && empty($error_messages))
    {
        if(Process($formData))
        {
            $status = "OK";
            SendEmail($formData,date('Y-m-d H:i:s', time()));
        }
        else
        {
            $status = "DB_ERR";
        }
    }
    else
    {
        $valid = false;
    }
}
else if(isset($_POST['back']) || isset($_POST['login']))
{
    if($_SERVER['SERVER_NAME'] == 'secure1.wdev.rochester.edu')
		header('Location: https://secure1.wdev.rochester.edu/ccas/readmission.php');
	else
		header('Location: https://secure1.rochester.edu/ccas/readmission.php');
}

/*****************************************************************
 * 
 * Functions
 * 
 *****************************************************************/
/*****************************************************************
 * 
 * Validate Input
 * 
 *****************************************************************/
function Validate($data)
{
    global $errors;
    global $error_messages;
    global $canProcess;

    $db_drvr = new MySQLDriver();

    $errorsCnt = 0;
    if(empty($data['studentEmail1']))
    {
        $errors[] = "studentEmail1";
    }
    if(empty($data['studentEmail2']))
    {
        $errors[] = "studentEmail2";
    }
    if(empty($data['userName1']))
    {
        $errors[] = "userName1";
    }
    if(empty($data['userName2']))
    {
        $errors[] = "userName2";
    }
    if(empty($data['password1']))
    {
        $errors[] = "password1";
    }
    if(empty($data['password2']))
    {
        $errors[]= "password2";
    }

    if($data['studentEmail1'] != $data['studentEmail2'])
    {
        $error_messages[] = "Email addresses do not match. Please try again.";
    }
    if($data['userName1'] != $data['userName2'])
    {
        $error_messages[] = "User names do not match. Please try again.";
    }
    else if(($data['userName1'] == $data['userName2']))
    {
        if(AuthNetID($data['userName1']) == false)
        {
            $error_messages[] = "NetID does not exist.";
        }
    }
    if($data['password1'] != $data['password2'])
    {
        $error_messages[] = "Passwords do not match. Please try again.";
    }

    if(empty($error_messages))
    {
        $results = $db_drvr->ReadTable('ReadmissionApplication',array('userName' => $data['userName1']));
        $studentInfo = $results[0];
        if(($studentInfo['userName'] == $data['userName1']) && !empty($studentInfo['password']))
        {
            $reset = "";
            if($_SERVER['SERVER_NAME'] == 'secure1.wdev.rochester.edu')
                $reset = "<a href='https://secure1.wdev.rochester.edu/ccas/readmission_reset_password.php'>Forgot Your Password?</a>";
            else
                $reset = "<a href='https://secure1.rochester.edu/ccas/readmission_reset_password.php'>Forgot Your Password?</a>";
            $error_messages[] = "An account with this username and password is ACTIVE! If you forgot your password, please go reset your password. " . $reset;
        }
        else if(strlen($data['password1']) < 6 || !preg_match('@[0-9]@', $data['password1']) || !preg_match('@[A-Z]@', $data['password1']) || !preg_match('@[a-z]@', $data['password1']))
        {
            $error_messages[] = "Password Requirements: <br/>- Must be at least 6 characters in length<br/>- Must contain at least one number<br/>- Must contain at least one uppercase character<br/>- Must contain at least one lowercase character";
        }
    }
    return $errors;
}
/*****************************************************************
 * 
 * Process Data
 * 
 *****************************************************************/
function Process($data)
{
    $db_drvr = new MySQLDriver();
    $record = array();

    $record['studentEmailAddress'] = $data['studentEmail1'];
    $record['userName'] = $data['userName1'];
    $password = password_hash($data['password1'], PASSWORD_DEFAULT);
    $record['password'] = $password;
    $id = $db_drvr->Insert('ReadmissionApplication',$record);
    if($id == 0)
        return false;
    return true;
}
/*****************************************************************
 * 
 * Authenticate NetID if allowed to make account
 * 
 *****************************************************************/
function AuthNetID($userName)
{
    global $auth;
    $result = $auth->Authenticate($userName);
    if($result)
        return true;
    else
        return false;
}
/*****************************************************************
 * 
 * Send Email if account created successfully
 * 
 *****************************************************************/
function SendEmail($data, $date)
{
	$data['dateSubmitted'] = $date;

    $message = "";
    
    if($_SERVER['SERVER_NAME'] == 'secure1.wdev.rochester.edu')
        $message .= "Your account has been successfully created. You can login to your Readmission Application at (https://secure1.wdev.rochester.edu/ccas/readmission.php)\n\n";
	else
        $message .= "Your account has been successfully created. You can login to your Readmission Application at (https://secure1.rochester.edu/ccas/readmission.php)\n\n";

	$message .= "Username: " . $data['userName1'] . " Account Created On: " . $data['dateSubmitted'] . "\n";

    $message .= "\r\n\r\n";
	$message .= "If you have any questions about your account please contact the Registrar's Office at (585) 275-8131 so we can update our records.\n";
				
	mail($data['studentEmail1'],'Readmission Application Account Successfully Created',$message,"From: lvasavon@u.rochester.edu\r\n");
}
?>