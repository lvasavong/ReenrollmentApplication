<?php
//*******************************************************************************************************
//	m_reset_password.php
//
//	Author: Linda Vasavong
//	Date Created: ????
//	Date Modified: Linda Vasavong
//*******************************************************************************************************

require_once('class_library/database_drivers/MySQLDriver.php');
require_once('class_library/Common.php');

$common = new Common();

$errors = array();
$error_messages = array();;

$status = "";
$valid = true;
$layout = "";

$email = "";
$recordID = "";
$userName = "";
$randomPassword = "";
$newPassword = "";

if(isset($_POST['resetPassword']))
{
    $formData = $_POST;
    $errors = ValidateEmail($formData);
    if(empty($errors) && empty($error_messages))
    {
        if(UpdatePassword())
        {
            SendNewPassword(date('Y-m-d H:i:s', time()));
            $layout = "READY";
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
function ValidateEmail($data)
{
    global $errors;
    global $error_messages;
    global $email;
    global $recordID;
    global $userName;

    $db_drvr = new MySQLDriver();

    if(empty($data['studentEmail']))
    {
        $errors[] = "studentEmail";
    }

    if(empty($errors))
    {
        $results = $db_drvr->ReadTable('ReadmissionApplication',array('studentEmailAddress' => $data['studentEmail']));
        $student = $results[0];
        if($student['studentEmailAddress'] != $data['studentEmail'])
        {
            $error[] = "studentEmail";
            $error_messages[] = "An account with this email address does not exist!";
        }
        else
        {   
            // save email and recordID for changing password process
            $email = $data['studentEmail'];
            $recordID =  $student['recordID'];
            $userName = $student['userName'];
        }
    }
    return $errors;
}
/*****************************************************************
 * 
 * Makes Random Password
 * 
 *****************************************************************/
function RandomPasswordMaker()
{
    global $newPassword;

    for($i = 0; $i < 7; $i++)
	{
		$lower = rand(0,1);
		$newPassword .= ($lower == 1 ? chr(rand(65,90)) : strtolower(chr(rand(65,90))));
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    return $hashedPassword;
}
/*****************************************************************
 * 
 * Update Password to Random Password
 * 
 *****************************************************************/
function UpdatePassword()
{
    global $recordID;
    global $randomPassword;
    $randomPassword = RandomPasswordMaker();
    $db_drvr = new MySQLDriver();
    $record = array();

    $record['password'] = $randomPassword;

    $id = $db_drvr->UpdateRecord('ReadmissionApplication',$recordID,$record);
    if($id == 0)
        return false;
    return true;
}
/*****************************************************************
 * 
 * Send Random Password to Student
 * 
 *****************************************************************/
function SendNewPassword($date)
{
    global $email;
    global $newPassword;
    global $userName;

    $message = "";
    
    if($_SERVER['SERVER_NAME'] == 'secure1.wdev.rochester.edu')
        $message .= "You can login to your Readmission Application at (https://secure1.wdev.rochester.edu/ccas/readmission.php)\n\n";
	else
        $message .= "You can login to your Readmission Application at (https://secure1.rochester.edu/ccas/readmission.php)\n\n";

    $message .= "Here is your new password. " . "\n";
    $message .= "Date Requested Change: " . $date . "\n";
    $message .= "Username: " . $userName . "\n";
    $message .= "Password: " . $newPassword . "\n";

    $message .= "\r\n\r\n";
	$message .= "If you have any questions about your account please contact the Registrar's Office at (585) 275-8131 so we can update our records.\n";
				
	mail($email,'Readmission Application Request To Reset Password',$message,"From: lvasavon@u.rochester.edu\r\n");
}

?>