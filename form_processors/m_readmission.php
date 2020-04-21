<?php
//*******************************************************************************************************
//	m_readmission.php
//
//	Author: Linda Vasavong
//	Date Created: ????
//	Date Modified: Linda Vasavong
//*******************************************************************************************************

//===================================================================
// Initialization
//===================================================================
require_once('class_library/ReadmissionLoginForm.php');
require_once('class_library/Common.php');
require_once('class_library/database_drivers/FMDBDriver.php');
require_once('class_library/database_drivers/MySQLDriver.php');

session_start();
session_name('reenrollment');

$loginForm = new LoginForm("Reenrollment Application");
$common = new Common();

$validTest = true;

$dump = "";
//$fmdbstatus = "";

$whichForm = "";

$formData = array();
$essayUploadedDate;
$essayUploadedName = "";
$essayUploaded = false;
$essayError = false;
$essaySuccess = false;
$removeDuplicateEssay = true;
$essayMessage = "";
$file = "";
$part = "Welcome";

$errors = array();
$error_messages = array();
$message = "";	// for essay

$fmessage = "";

$userData = $_SESSION['UserData'];

//===================================================================
// Request Handling
//===================================================================

if($_SESSION['Processed'] == 'Yes')
{
	//Catch to eliminate duplicate submissions
	unset($_SESSION['Processed']);
	
	// Redirect to the login page
	if($_SERVER['SERVER_NAME'] == 'secure1.wdev.rochester.edu')
		header('Location: https://secure1.wdev.rochester.edu/ccas/readmission.php');
	else
		header('Location: https://secure1.rochester.edu/ccas/readmission.php');
}
else if(isset($_POST['Login']))
{
	$loginForm->Instantiate($_POST['username'], $_POST['password']);
	$loginForm->Validate();
	
	if($loginForm->IsValid())
	{
		$userData = $loginForm->GetInfo();
		$formData = $userData;
		$_SESSION['UserData'] = $formData;
		CheckEssayUploaded();

		$_SESSION['LoggedIn'] = 'Yes';
		$_SESSION['State'] = "Welcome";
		$_SESSION['WhichForm'] = "";
	}
	else
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
		unset($_SESSION['WhichForm']);
	}
}



else if(isset($_POST['SaveWhichForm'])) {
	$whichForm = $_POST['whichForm'];
	if($whichForm == "form1") {
		$_SESSION['WhichForm'] = "form1";
	}
	else if($whichForm == "form2") {
		$_SESSION['WhichForm'] = "form2";
	} 
	else if($whichForm == "form3") {
		$_SESSION['WhichForm'] = "form3";
	}
}



else if(isset($_POST['Home'])) 
{
	if(empty($userData['recordID']))
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
	}
	else
	{
		$_SESSION['State'] = "Welcome"; 
	}
}
else if(isset($_POST['Part1'])) 
{
	if(empty($userData['recordID']))
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
	}
	else
	{
		$db_driver = new MySQLDriver();
		$results = $db_driver->ReadTable('ReadmissionApplication', array('recordID' => $userData['recordID']));
		$formData = $results[0];
		$_SESSION['State'] = "Part 1"; 
	}
}
else if(isset($_POST['Part2'])) 
{
	if(empty($userData['recordID']))
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
	}
	else
	{
		$db_driver = new MySQLDriver();
		$results = $db_driver->ReadTable('ReadmissionApplication', array('recordID' => $userData['recordID']));
		$formData = $results[0];
		$_SESSION['State'] = "Part 2"; 
	}
}
else if(isset($_POST['Part3'])) 
{
	if(empty($userData['recordID']))
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
	}
	else
	{
		$db_driver = new MySQLDriver();
		$results = $db_driver->ReadTable('ReadmissionApplication', array('recordID' => $userData['recordID']));
		$formData = $results[0];
		$_SESSION['State'] = "Part 3"; 
	}
}
else if(isset($_POST['Part4'])) 
{
	if(empty($userData['recordID']))
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
	}
	else
	{
		CheckEssayUploaded();
		$_SESSION['State'] = "Part 4";
	}
}
else if(isset($_POST['Review'])) 
{
	if(empty($userData['recordID']))
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
	}
	else
	{ 
		$db_driver = new MySQLDriver();
		$results = $db_driver->ReadTable('ReadmissionApplication', array('recordID' => $userData['recordID']));
		$formData = $results[0];
		$errors = ValidateSubmission($formData);
		CheckEssayUploaded();
		$_SESSION['State'] = "Review"; 
	}
}
else if(isset($_POST['Save']) && ($_SESSION['LoggedIn'] == 'Yes'))
{
	if(empty($userData['recordID']))
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
	}
	else
	{
		$formData = $_POST;
		$part = $_SESSION['State'];
		$errors = Validate($formData);
		
		if(empty($errors) && empty($error_messages))
		{
			if(ProcessSave($formData, $part))
			{
				$status = "OK";
			}
			else
			{
				$status = "DB_ERR";
			}			
		}
		else
		{
			$validTest = false;
		}
	}
}
else if(isset($_POST['Submit']) && ($_SESSION['LoggedIn'] == 'Yes') && ($_SESSION['State'] == "Review"))
{
	if(empty($userData['recordID']))
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
	}
	else
	{
		$formData = $_POST;
		//$errors = ValidateSubmission($formData);
		
		if(empty($errors) && empty($error_messages))
		{
			if(ProcessSubmit())
			{
				$status = "SUBMIT";
				SendEmail(date('Y-m-d H:i:s', time()));
				unset($_SESSION['LoggedIn']);
				unset($_SESSION['UserData']);
				unset($_SESSION['State']);
				$_SESSION['Processed'] = 'Yes';
				/*if(ProcessFM())
				{
					$fmdbstatus = "Processed Successfully!";
				}
				else
				{
					$fmdbstatus = "FMDB_ERR";
				}*/
			}
			else
			{
				$status = "DB_ERR";
			}			
		}
		else
		{
			$validTest = false;
		}
	}
}
else if(isset($_POST['uploadEssay']))
{
	if(empty($userData['recordID']))
	{
		unset($_SESSION['LoggedIn']);
		unset($_SESSION['UserData']);
		unset($_SESSION['State']);
	}
	else
	{
		$formData = $_POST;
		CheckEssayUploaded();
		$db_dr = new MySQLDriver();
		$result = $db_dr->ReadTable('ReadmissionApplication',array('recordID' => $userData['recordID']));
		$student = $result[0];
		$targetDir = "/www_vol/data/secure1-data/ccas/form_processors/readmission_uploads/" . $student['recordID'] . "/";
		if(!file_exists($targetDir))
		{
			mkdir($targetDir);
		}
		$fileName = basename($_FILES['essay']['name']);
		$fileTmpLoc = $_FILES["essay"]["tmp_name"];
		$targetFilePath = $targetDir . $fileName;
		$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
		$dateNow = date('Y-m-d H:i:s', time());
		if(($_FILES['essay']['type'] == "application/pdf"))
		{
			$file = $essayUploaded;
			if($essayUploaded)	// check if user had already uploaded essay (deletes duplicate submissions)
			{
				$db_drvr = new MySQLDriver();
				$results = $db_drvr->ReadTable('ReadmissionApplication',array('recordID' => $userData['recordID']));
				$student = $results[0];
				if(!empty($student['essay']))
				{
					$file = $targetDir . $student['essay'];
					if(file_exists($file))
					{
						if(unlink($file))
						{
							$removeDuplicateEssay = true;
						}
						else
						{
							$message = "There was a server error in updating your essay.";
							$removeDuplicateEssay = false;
						}
					}
				}
				else
				{
					$file = "Empty";
				}
			}
			// Check if there is duplicate essay submission
			if($removeDuplicateEssay)
			{
				// Upload file to correct directory
				if(move_uploaded_file($fileTmpLoc, $targetFilePath))
				{
					$db_driver = new MySQLDriver();
					$result = $db_driver->UpdateRecord('ReadmissionApplication',$userData['recordID'],array('essay' => $fileName,'essayUploadedOn' => $dateNow));

					if($result != 0)
					{
						$essaySuccess = true;
						$essayMessage = "Your Essay has successfully been uploaded!";
						CheckEssayUploaded();
					}
					else
					{
						$essaySuccess = true;
						$essayMessage = "There was a database error uploading this file. Please contact the system administrator (nicholas.smith@rochester.edu).";	
					}
				}
				else
				{
					$essaySuccess = true;
					$essayMessage = "There was a server error uploading this file. Please contact the system administrator (nicholas.smith@rochester.edu).";
				}
			}
		}
		else
		{
			$essayError = true;
			$essayMessage = "Invalid file, essay must be a .pdf file.";	
		}
	}
}
else
{
	unset($_SESSION['LoggedIn']);
	unset($_SESSION['UserData']);
	unset($_SESSION['State']);
}
//===================================================================
// Functions
//===================================================================
//-------------------------------------------------------------------
function CheckEssayUploaded()
{
	$db_driver = new MySQLDriver();
	global $essayUploadedDate;
	global $essayUploaded;
	global $userData;
	global $essayUploadedName;
	//Check to see if the user has uploaded their essay previously
	$student = $db_driver->ReadTable('ReadmissionApplication',array('recordID' => $userData['recordID']));
	$upload = $student[0];
	if(!empty($upload['essay']))
	{
		$essayUploaded = true;	
		$essayUploadedDate = "";
		
		$essayUploadedDate = $upload['essayUploadedOn'];
		$essayUploadedName = $upload['essay'];
	}
	else
	{
		$essayUploaded = false;
	}
}
//------------------------------------------------------------------------------------------------------
// Function to return a standardized error message display
// - $messages - an array of textual error messages.
//------------------------------------------------------------------------------------------------------
function GetErrorDisplay($messages)
{
	$display = "";
	$display .= "<div class='row--with-borders'><div class='columns small-12'>";
	$display .= "<h4 style='color:red;'>The following errors were found! Please go back and fill out the required fields.</h3>";
	$display .= "<ul style='color:red;'>";
		
	foreach($messages as $message)
	{
		$display .= "<li>" . $message . "</li>";	
	}
		
	$display .= "</ul>";
	$display .= "</div></div>";
		
	return $display;
}
//-------------------------------------------------------------------
function Validate($data)
{
	global $essayUploaded;
	global $error_messages;
	global $part;
	$errors = array();
	
	if($part == "Part 1")
	{
		if(empty($data['semesterEnroll']))
		{
			$errors[] = "semesterEnroll";
			$error_messages[] = "Please enter the semester that you want to enroll in for Part 1.";
		}
		if(empty($data['studentFirstName']))
		{
			$errors[] = "studentFirstName";
			$error_messages[] = "Please enter your first name for Part 1.";
		}
		if(empty($data['studentLastName']))
		{
			$errors[] = "studentLastName";
			$error_messages[] = "Please enter your last name for Part 1.";
		}
		if(empty($data['studentPreferredName']))
		{
			$errors[] = "studentPreferredName";
			$error_messages[] = "Please enter your preferred name for Part 1.";
		}
		if(empty($data['studentID']))
		{
			$errors[] = "studentID";
			$error_messages[] = "Please enter your student ID # for Part 1.";
		}
		if(empty($data['advisorName']))
		{
			$errors[] = "advisorName";
			$error_messages[] = "Please enter the name of your advisor for Part 1.";
		}
		if(empty($data['studentAddressLine1']))
		{
			$errors[] = "studentAddressLine1";
			$error_messages[] = "Please enter your address line 1 for Part 1.";
		}
		if(empty($data['studentCity']))
		{
			$errors[] = "studentCity";
			$error_messages[] = "Please enter your city for Part 1.";
		}
		if(empty($data['studentCountry']))
		{
			$errors[] = "studentCountry";
			$error_messages[] = "Please enter your country for Part 1.";
		}
		/*if(empty($data['studentState']))
		{
			$errors[] = "studentState";
			$error_messages[] = "Please enter your state for Part 1.";
		}*/
		if(empty($data['studentZipCode']))
		{
			$errors[] = "studentZipCode";
			$error_messages[] = "Please enter your zip code for Part 1.";
		}
		if(empty($data['studentBirthMonth']))
		{
			$errors[] = "studentBirthMonth";
			$error_messages[] = "Please enter your birth month for Part 1.";
		}
		if(empty($data['studentBirthDay']))
		{
			$errors[] = "studentBirthDay";
			$error_messages[] = "Please enter your birth day for Part 1.";
		}
		if(empty($data['studentBirthYear']))
		{
			$errors[] = "studentBirthYear";
			$error_messages[] = "Please enter your birth year for Part 1.";
		}
		if(empty($data['studentHomePhoneNumber']))
		{
			$errors[] = "studentHomePhoneNumber";
			$error_messages[] = "Please enter your phone number for Part 1.";
		}
		if(empty($data['studentCellPhoneNumber']))
		{
			$errors[] = "studentCellPhoneNumber";
			$error_messages[] = "Please enter your phone number for Part 1.";
		}
		if(empty($data['studentEmailAddress']))
		{
			$errors[] = "studentEmailAddress";
			$error_messages[] = "Please enter your email address for Part 1.";
		}
		if(empty($data['studentIs21AndOver']))
		{
			if(empty($data['parent1FirstName']))
			{
				$errors[] = "parent1FirstName";
				$error_messages[] = "Please enter the first name for parent 1 for Part 1.";
			}
			if(empty($data['parent1LastName']))
			{
				$errors[] = "parent1LastName";
				$error_messages[] = "Please enter the last name for Parent 1 for Part 1.";
			}
			if(empty($data['parent1AddressLine1']))
			{
				$errors[] = "parent1AddressLine1";
				$error_messages[] = "Please enter address line 1 for Parent 1 for Part 1.";
			}
			if(empty($data['parent1City']))
			{
				$errors[] = "parent1City";
				$error_messages[] = "Please enter the city for Parent 1 for Part 1.";
			}
			if(empty($data['parent1Country']))
			{
				$errors[] = "parent1Country";
				$error_messages[] = "Please enter the country for Parent 1 for Part 1.";
			}
			/*if(empty($data['parent1State']))
			{
				$errors[] = "parent1State";
				$error_messages[] = "Please enter the state for Parent 1 for Part 1.";
			}*/
			if(empty($data['parent1ZipCode']))
			{
				$errors[] = "parent1ZipCode";
				$error_messages[] = "Please enter the zip code for Parent 1 for Part 1.";
			}
			if(empty($data['parent1PhoneNumber']))
			{
				$errors[] = "parent1PhoneNumber";
				$error_messages[] = "Please enter the phone number for Parent 1 for Part 1.";
			}
			if(empty($data['parent1EmailAddress']))
			{
				$errors[] = "parent1EmailAddress";
				$error_messages[] = "Please enter the email address for Parent 1 for Part 1.";
			}

			if(!empty($data['parent2FirstName']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2LastName']))
			{
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2AddressLine1']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2City']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2Country']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2State']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2ZipCode']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2PhoneNumber']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2EmailAddress']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
			}
		}
	}
	else if($part == "Part 2")
	{
		if(empty($data['monthEntered']))
		{
		$errors[] = "monthEntered";
		$error_messages[] = "Please enter the month you entered the University of Rochester for Part 2.";
		}

		if(empty($data['yearEntered']))
		{
		$errors[] = "yearEntered";
		$error_messages[] = "Please enter the year you entered the University of Rochester for Part 2.";
		}

		if(empty($data['monthLeft']))
		{
		$errors[] = "monthLeft";
		$error_messages[] = "Please enter the month you left the University of Rochester for Part 2.";
		}

		if(empty($data['yearLeft']))
		{
		$errors[] = "yearLeft";
		$error_messages[] = "Please enter the year you left the University of Rochester for Part 2.";
		}

		if((empty($data['appliedBeforeYes']) && empty($data['appliedBeforeNo'])) || (!empty($data['appliedBeforeYes']) && !empty($data['appliedBeforeNo'])))
		{
		$errors[] = "appliedBeforeYes";
		$error_messages[] = "Please indicate that you have previously applied or not previously applied to return to University of Rochester through the Re-Enrollment Application for Part 2.";
		}

		if($data['appliedBeforeYes'] == "Yes")
		{
			if(empty($data['monthAppliedBefore']))
			{
				$errors[] = "monthAppliedBefore";
				$error_messages[] = "You have indicated that you have previously applied to return to University of Rochester through the Re-Enrollment Application, please enter the month of when you applied for Part 2.";
			}
			if(empty($data['yearAppliedBefore']))
			{
				$errors[] = "yearAppliedBefore";
				$error_messages[] = "You have indicated that you have previously applied to return to University of Rochester through the Re-Enrollment Application, please enter the year of when you applied for Part 2.";
			}
		}

		if(empty($data['onCampusRequired']))
		{
			$errors[] = "onCampusRequired";
			$error_messages[] = "Please indicate that you will arrive 48 hours prior to the start of classes in section 4 for Part 2.";
		}

		if((empty($data['liveOnCampusYes']) && empty($data['liveOnCampusNo'])) || (!empty($data['liveOnCampusYes']) && !empty($data['liveOnCampusNo'])))
		{
			$errors[] = "liveOnCampusYes";
			$error_messages[] = "Please choose an option whether you will live on or off campus in section 5 for Part 2.";
		}
		
		if(!empty($data['medicalYes']) && !empty($data['medicalNo']))
		{
			$errors[] = "medicalYes";
			$error_messages[] = "If filling out section 7, you must only choose one option for Part 2.";
		}

		if(!empty($data['firstInstitution']) && empty($data['firstInstitutionDate']))
		{
			$errors[] = "firstInstitutionDate";
			$error_messages[] = "If filling out section 8, you must fill out both the dates you attended for the first institution you were at for Part 2.";
		}
		if(empty($data['firstInstitution']) && !empty($data['firstInstitutionDate']))
		{
			$errors[] = "firstInstitution";
			$error_messages[] = "If filling out section 8, you must fill out the name of the first institution you were at for Part 2.";
		}

		if(!empty($data['secondInstitution']) && empty($data['secondInstitutionDate']))
		{
			$errors[] = "secondInstitutionDate";
			$error_messages[] = "If filling out section 8, you must fill out both the dates you attended for the second institution you were at for Part 2.";
		}
		if(empty($data['secondInstitution']) && !empty($data['secondInstitutionDate']))
		{
			$errors[] = "secondInstitution";
			$error_messages[] = "If filling out section 8, you must fill out the name of the second institution you were at for Part 2.";
		}

		if(!empty($data['supervisorName']))
		{
			if(empty($data['companyName']))
			{
				$errors[] = "companyName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's company for Part 2.";
			}
			if(empty($data['supervisorTitle']))
			{
				$errors[] = "supervisorTitle";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's title for Part 2.";
			}
			if(empty($data['supervisorPhoneNumber']))
			{
				$errors[] = "supervisorPhoneNumber";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's phone number for Part 2.";
			}
			if(empty($data['supervisorEmailAddress']))
			{
				$errors[] = "supervisorEmailAddress";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's email address for Part 2.";
			}
		}
		else if(!empty($data['companyName']))
		{
			if(empty($data['supervisorName']))
			{
				$errors[] = "supervisorName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's name for Part 2.";
			}
			if(empty($data['supervisorTitle']))
			{
				$errors[] = "supervisorTitle";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's title for Part 2.";
			}
			if(empty($data['supervisorPhoneNumber']))
			{
				$errors[] = "supervisorPhoneNumber";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's phone number for Part 2.";
			}
			if(empty($data['supervisorEmailAddress']))
			{
				$errors[] = "supervisorEmailAddress";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's email address for Part 2.";
			}
		}
		else if(!empty($data['supervisorTitle']))
		{
			if(empty($data['companyName']))
			{
				$errors[] = "companyName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's company for Part 2.";
			}
			if(empty($data['supervisorName']))
			{
				$errors[] = "supervisorName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's name for Part 2.";
			}
			if(empty($data['supervisorPhoneNumber']))
			{
				$errors[] = "supervisorPhoneNumber";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's phone number for Part 2.";
			}
			if(empty($data['supervisorEmailAddress']))
			{
				$errors[] = "supervisorEmailAddress";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's email address for Part 2.";
			}
		}
		else if(!empty($data['supervisorPhoneNumber']))
		{
			if(empty($data['companyName']))
			{
				$errors[] = "companyName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's company for Part 2.";
			}
			if(empty($data['supervisorTitle']))
			{
				$errors[] = "supervisorTitle";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's title for Part 2.";
			}
			if(empty($data['supervisorName']))
			{
				$errors[] = "supervisorName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's name for Part 2.";
			}
			if(empty($data['supervisorEmailAddress']))
			{
				$errors[] = "supervisorEmailAddress";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's email address for Part 2.";
			}
		}
		else if(!empty($data['supervisorEmailAddress']))
		{
			if(empty($data['companyName']))
			{
				$errors[] = "companyName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's company for Part 2.";
			}
			if(empty($data['supervisorTitle']))
			{
				$errors[] = "supervisorTitle";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's title for Part 2.";
			}
			if(empty($data['supervisorPhoneNumber']))
			{
				$errors[] = "supervisorPhoneNumber";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's phone number for Part 2.";
			}
			if(empty($data['supervisorName']))
			{
				$errors[] = "supervisorName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's name for Part 2.";
			}
		}
	}
	else if($part == "Part 3")
	{
		if(empty($data['major']))
		{
			$errors[] = "major";
			$error_messages[] = "Please enter your major.";
		}
		if(empty($data['cluster1']))
		{
			$errors[] = "cluster1";
			$error_messages[] = "Please enter a cluster.";
		}
		if(empty($data['academicPlanPerson']))
		{
			$errors[] = "academicPlanPerson";
			$error_messages[] = "Please enter the name of the person you have discussed your academic plan with.";
		}

		$cnt = 0;
		
		if(!empty($data['courseRationale1']))
		{
			if(empty($data['courseNumber1']) ||
				empty($data['courseTitle1']) ||
				empty($data['courseCredit1']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber1']))
		{
			if(empty($data['courseRationale1']) ||
				empty($data['courseTitle1']) ||
				empty($data['courseCredit1']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle1']))
		{
			if(empty($data['courseNumber1']) ||
				empty($data['courseRationale1']) ||
				empty($data['courseCredit1']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit1']))
		{
			if(empty($data['courseNumber1']) ||
				empty($data['courseTitle1']) ||
				empty($data['courseRationale1']))
				{
					$cnt++;
				}
		}
		
		if(!empty($data['courseRationale2']))
		{
			if(empty($data['courseNumber2']) ||
				empty($data['courseTitle2']) ||
				empty($data['courseCredit2']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber2']))
		{
			if(empty($data['courseRationale2']) ||
				empty($data['courseTitle2']) ||
				empty($data['courseCredit2']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle2']))
		{
			if(empty($data['courseNumber2']) ||
				empty($data['courseRationale2']) ||
				empty($data['courseCredit2']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit2']))
		{
			if(empty($data['courseNumber2']) ||
				empty($data['courseTitle2']) ||
				empty($data['courseRationale2']))
				{
					$cnt++;
				}
		}
		
		if(!empty($data['courseRationale3']))
		{
			if(empty($data['courseNumber3']) ||
				empty($data['courseTitle3']) ||
				empty($data['courseCredit3']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber3']))
		{
			if(empty($data['courseRationale3']) ||
				empty($data['courseTitle3']) ||
				empty($data['courseCredit3']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle3']))
		{
			if(empty($data['courseNumber3']) ||
				empty($data['courseRationale3']) ||
				empty($data['courseCredit3']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit3']))
		{
			if(empty($data['courseNumber3']) ||
				empty($data['courseTitle3']) ||
				empty($data['courseRationale3']))
				{
					$cnt++;
				}
		}
		
		if(!empty($data['courseRationale4']))
		{
			if(empty($data['courseNumber4']) ||
				empty($data['courseTitle4']) ||
				empty($data['courseCredit4']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber4']))
		{
			if(empty($data['courseRationale4']) ||
				empty($data['courseTitle4']) ||
				empty($data['courseCredit4']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle4']))
		{
			if(empty($data['courseNumber4']) ||
				empty($data['courseRationale4']) ||
				empty($data['courseCredit4']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit4']))
		{
			if(empty($data['courseNumber4']) ||
				empty($data['courseTitle4']) ||
				empty($data['courseRationale4']))
				{
					$cnt++;
				}
		}
		
		if(!empty($data['courseRationale5']))
		{
			if(empty($data['courseNumber5']) ||
				empty($data['courseTitle5']) ||
				empty($data['courseCredit5']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber5']))
		{
			if(empty($data['courseRationale5']) ||
				empty($data['courseTitle5']) ||
				empty($data['courseCredit5']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle5']))
		{
			if(empty($data['courseNumber5']) ||
				empty($data['courseRationale5']) ||
				empty($data['courseCredit5']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit5']))
		{
			if(empty($data['courseNumber5']) ||
				empty($data['courseTitle5']) ||
				empty($data['courseRationale5']))
				{
					$cnt++;
				}
		}

		if(!empty($data['courseRationale6']))
		{
			if(empty($data['courseNumber6']) ||
				empty($data['courseTitle6']) ||
				empty($data['courseCredit6']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber6']))
		{
			if(empty($data['courseRationale6']) ||
				empty($data['courseTitle6']) ||
				empty($data['courseCredit6']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle6']))
		{
			if(empty($data['courseNumber6']) ||
				empty($data['courseRationale6']) ||
				empty($data['courseCredit6']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit6']))
		{
			if(empty($data['courseNumber6']) ||
				empty($data['courseTitle6']) ||
				empty($data['courseRationale6']))
				{
					$cnt++;
				}
		}

		if(!empty($data['courseRationale7']))
		{
			if(empty($data['courseNumber7']) ||
				empty($data['courseTitle7']) ||
				empty($data['courseCredit7']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber7']))
		{
			if(empty($data['courseRationale7']) ||
				empty($data['courseTitle7']) ||
				empty($data['courseCredit7']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle7']))
		{
			if(empty($data['courseNumber7']) ||
				empty($data['courseRationale7']) ||
				empty($data['courseCredit7']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit7']))
		{
			if(empty($data['courseNumber7']) ||
				empty($data['courseTitle7']) ||
				empty($data['courseRationale7']))
				{
					$cnt++;
				}
		}
		
		if(!empty($data['courseRationale8']))
		{
			if(empty($data['courseNumber8']) ||
				empty($data['courseTitle8']) ||
				empty($data['courseCredit8']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber8']))
		{
			if(empty($data['courseRationale8']) ||
				empty($data['courseTitle8']) ||
				empty($data['courseCredit8']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle8']))
		{
			if(empty($data['courseNumber8']) ||
				empty($data['courseRationale8']) ||
				empty($data['courseCredit8']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit8']))
		{
			if(empty($data['courseNumber8']) ||
				empty($data['courseTitle8']) ||
				empty($data['courseRationale8']))
				{
					$cnt++;
				}
		}

		if($cnt > 0)
		{
			$error_messages[] = "You must complete entire course row for the course(s) you have entered.";
			$errors[]='firstSemester';
		}

		if(empty($data['courseRationale1']) || empty($data['courseNumber1']) || empty($data['courseTitle1']) || empty($data['courseCredit1']))
		{
			$error_messages[] = "You must fill out at least one course row for your first semester back.";
			$errors[] = 'courseRow';
		}
		
		if((empty($data['returnFullTime']) && empty($data['returnPartTime'])) || (!empty($data['returnFullTime']) && !empty($data['returnPartTime'])))
		{
			$errors[] = "returnFullTime";
			$error_messages[] = "You must only choose one option whether you will be a Full-Time or Part-Time student.";
		}

		if(empty($data['numberOfSemesters']))
		{
			$errors[] = "numberOfSemesters";
			$error_messages[] = "Please enter the number of semesters that it will take for you to complete your degree.";
		}

		if(empty($data['graduationMonth']))
		{
			$errors[] = "graduationMonth";
			$error_messages[] = "Please enter the month you anticipate graduating";
		}
		if(empty($data['graduationYear']))
		{
			$errors[] = "graduationYear";
			$error_messages[] = "Please enter the year you anticipate graduating";
		}
		
		/*CheckEssayUploaded();
		if(!$essayUploaded)
		{
			$error_messages[] = "Please attach an essay addressing your academic plan.";
			$errors[]='essay';
		}*/
	}

	return $errors;
}
function ValidateSubmission($data)
{
	global $essayUploaded;
	global $error_messages;
	global $part;
	$errors = array();
	
		if(empty($data['semesterEnroll']))
		{
			$errors[] = "semesterEnroll";
			$error_messages[] = "Please enter the semester that you want to enroll in for Part 1.";
		}
		if(empty($data['studentFirstName']))
		{
			$errors[] = "studentFirstName";
			$error_messages[] = "Please enter your first name for Part 1.";
		}
		if(empty($data['studentLastName']))
		{
			$errors[] = "studentLastName";
			$error_messages[] = "Please enter your last name for Part 1.";
		}
		if(empty($data['studentPreferredName']))
		{
			$errors[] = "studentPreferredName";
			$error_messages[] = "Please enter your preferred name for Part 1.";
		}
		if(empty($data['studentID']))
		{
			$errors[] = "studentID";
			$error_messages[] = "Please enter your student ID # for Part 1.";
		}
		if(empty($data['advisorName']))
		{
			$errors[] = "advisorName";
			$error_messages[] = "Please enter the name of your advisor for Part 1.";
		}
		if(empty($data['studentAddressLine1']))
		{
			$errors[] = "studentAddressLine1";
			$error_messages[] = "Please enter your address line 1 for Part 1.";
		}
		if(empty($data['studentCity']))
		{
			$errors[] = "studentCity";
			$error_messages[] = "Please enter your city for Part 1.";
		}
		if(empty($data['studentCountry']))
		{
			$errors[] = "studentCountry";
			$error_messages[] = "Please enter your country for Part 1.";
		}
		/*if(empty($data['studentState']))
		{
			$errors[] = "studentState";
			$error_messages[] = "Please enter your state for Part 1.";
		}*/
		if(empty($data['studentZipCode']))
		{
			$errors[] = "studentZipCode";
			$error_messages[] = "Please enter your zip code for Part 1.";
		}
		if(empty($data['studentBirthMonth']))
		{
			$errors[] = "studentBirthMonth";
			$error_messages[] = "Please enter your birth month for Part 1.";
		}
		if(empty($data['studentBirthDay']))
		{
			$errors[] = "studentBirthDay";
			$error_messages[] = "Please enter your birth day for Part 1.";
		}
		if(empty($data['studentBirthYear']))
		{
			$errors[] = "studentBirthYear";
			$error_messages[] = "Please enter your birth year for Part 1.";
		}
		if(empty($data['studentHomePhoneNumber']))
		{
			$errors[] = "studentHomePhoneNumber";
			$error_messages[] = "Please enter your phone number for Part 1.";
		}
		if(empty($data['studentCellPhoneNumber']))
		{
			$errors[] = "studentCellPhoneNumber";
			$error_messages[] = "Please enter your phone number for Part 1.";
		}
		if(empty($data['studentEmailAddress']))
		{
			$errors[] = "studentEmailAddress";
			$error_messages[] = "Please enter your email address for Part 1.";
		}
		if(empty($data['studentIs21AndOver']))
		{
			if(empty($data['parent1FirstName']))
			{
				$errors[] = "parent1FirstName";
				$error_messages[] = "Please enter the first name for parent 1 for Part 1.";
			}
			if(empty($data['parent1LastName']))
			{
				$errors[] = "parent1LastName";
				$error_messages[] = "Please enter the last name for Parent 1 for Part 1.";
			}
			if(empty($data['parent1AddressLine1']))
			{
				$errors[] = "parent1AddressLine1";
				$error_messages[] = "Please enter address line 1 for Parent 1 for Part 1.";
			}
			if(empty($data['parent1City']))
			{
				$errors[] = "parent1City";
				$error_messages[] = "Please enter the city for Parent 1 for Part 1.";
			}
			if(empty($data['parent1Country']))
			{
				$errors[] = "parent1Country";
				$error_messages[] = "Please enter the country for Parent 1 for Part 1.";
			}
			/*if(empty($data['parent1State']))
			{
				$errors[] = "parent1State";
				$error_messages[] = "Please enter the state for Parent 1 for Part 1.";
			}*/
			if(empty($data['parent1ZipCode']))
			{
				$errors[] = "parent1ZipCode";
				$error_messages[] = "Please enter the zip code for Parent 1 for Part 1.";
			}
			if(empty($data['parent1PhoneNumber']))
			{
				$errors[] = "parent1PhoneNumber";
				$error_messages[] = "Please enter the phone number for Parent 1 for Part 1.";
			}
			if(empty($data['parent1EmailAddress']))
			{
				$errors[] = "parent1EmailAddress";
				$error_messages[] = "Please enter the email address for Parent 1 for Part 1.";
			}

			if(!empty($data['parent2FirstName']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2LastName']))
			{
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2AddressLine1']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2City']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2Country']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2State']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2ZipCode']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2PhoneNumber']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter the last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
				if(empty($data['parent2EmailAddress']))
				{
					$errors[] = "parent2EmailAddress";
					$error_messages[] = "If filling out information for Parent 2, please enter the email address for Part 1.";
				}
			}
			else if(!empty($data['parent2EmailAddress']))
			{
				if(empty($data['parent2LastName']))
				{
					$errors[] = "parent2LastName";
					$error_messages[] = "If filling out information for Parent 2, please enter last name for Part 1.";
				}
				if(empty($data['parent2AddressLine1']))
				{
					$errors[] = "parent2AddressLine1";
					$error_messages[] = "If filling out information for Parent 2, please enter address line 1 for Part 1.";
				}
				if(empty($data['parent2City']))
				{
					$errors[] = "parent2City";
					$error_messages[] = "If filling out information for Parent 2, please enter the city for Part 1.";
				}
				if(empty($data['parent2Country']))
				{
					$errors[] = "parent2Country";
					$error_messages[] = "If filling out information for Parent 2, please enter the country for Part 1.";
				}
				/*if(empty($data['parent2State']))
				{
					$errors[] = "parent2State";
					$error_messages[] = "If filling out information for Parent 2, please enter the state for Part 1.";
				}*/
				if(empty($data['parent2ZipCode']))
				{
					$errors[] = "parent2ZipCode";
					$error_messages[] = "If filling out information for Parent 2, please enter the zip code for Part 1.";
				}
				if(empty($data['parent2PhoneNumber']))
				{
					$errors[] = "parent2PhoneNumber";
					$error_messages[] = "If filling out information for Parent 2, please enter the phone number for Part 1.";
				}
				if(empty($data['parent2FirstName']))
				{
					$errors[] = "parent2FirstName";
					$error_messages[] = "If filling out information for Parent 2, please enter the first name for Part 1.";
				}
			}
		}
	
		if(empty($data['monthEntered']))
		{
		$errors[] = "monthEntered";
		$error_messages[] = "Please enter the month you entered the University of Rochester for Part 2.";
		}

		if(empty($data['yearEntered']))
		{
		$errors[] = "yearEntered";
		$error_messages[] = "Please enter the year you entered the University of Rochester for Part 2.";
		}

		if(empty($data['monthLeft']))
		{
		$errors[] = "monthLeft";
		$error_messages[] = "Please enter the month you left the University of Rochester for Part 2.";
		}

		if(empty($data['yearLeft']))
		{
		$errors[] = "yearLeft";
		$error_messages[] = "Please enter the year you left the University of Rochester for Part 2.";
		}

		if((empty($data['appliedBeforeYes']) && empty($data['appliedBeforeNo'])) || (!empty($data['appliedBeforeYes']) && !empty($data['appliedBeforeNo'])))
		{
		$errors[] = "appliedBeforeYes";
		$error_messages[] = "Please indicate that you have previously applied or not previously applied to return to University of Rochester through the Re-Enrollment Application for Part 2.";
		}

		if($data['appliedBeforeYes'] == "Yes")
		{
			if(empty($data['monthAppliedBefore']))
			{
				$errors[] = "monthAppliedBefore";
				$error_messages[] = "You have indicated that you have previously applied to return to University of Rochester through the Re-Enrollment Application, please enter the month of when you applied for Part 2.";
			}
			if(empty($data['yearAppliedBefore']))
			{
				$errors[] = "yearAppliedBefore";
				$error_messages[] = "You have indicated that you have previously applied to return to University of Rochester through the Re-Enrollment Application, please enter the year of when you applied for Part 2.";
			}
		}

		if(empty($data['onCampusRequired']))
		{
			$errors[] = "onCampusRequired";
			$error_messages[] = "Please indicate that you will arrive 48 hours prior to the start of classes in section 4 for Part 2.";
		}

		if((empty($data['liveOnCampusYes']) && empty($data['liveOnCampusNo'])) || (!empty($data['liveOnCampusYes']) && !empty($data['liveOnCampusNo'])))
		{
			$errors[] = "liveOnCampusYes";
			$error_messages[] = "Please choose an option whether you will live on or off campus for Part 2.";
		}
		
		if(!empty($data['medicalYes']) && !empty($data['medicalNo']))
		{
			$errors[] = "medicalYes";
			$error_messages[] = "If filling out section 7, you must only choose one option for Part 2.";
		}

		if(!empty($data['firstInstitution']) && empty($data['firstInstitutionDate']))
		{
			$errors[] = "firstInstitutionDate";
			$error_messages[] = "If filling out section 8, you must fill out both the dates you attended for the first institution you were at for Part 2.";
		}
		if(empty($data['firstInstitution']) && !empty($data['firstInstitutionDate']))
		{
			$errors[] = "firstInstitution";
			$error_messages[] = "If filling out section 8, you must fill out the name of the first institution you were at for Part 2.";
		}

		if(!empty($data['secondInstitution']) && empty($data['secondInstitutionDate']))
		{
			$errors[] = "secondInstitutionDate";
			$error_messages[] = "If filling out section 8, you must fill out both the dates you attended for the second institution you were at for Part 2.";
		}
		if(empty($data['secondInstitution']) && !empty($data['secondInstitutionDate']))
		{
			$errors[] = "secondInstitution";
			$error_messages[] = "If filling out section 8, you must fill out the name of the second institution you were at for Part 2.";
		}

		if(!empty($data['supervisorName']))
		{
			if(empty($data['companyName']))
			{
				$errors[] = "companyName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's company for Part 2.";
			}
			if(empty($data['supervisorTitle']))
			{
				$errors[] = "supervisorTitle";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's title for Part 2.";
			}
			if(empty($data['supervisorPhoneNumber']))
			{
				$errors[] = "supervisorPhoneNumber";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's phone number for Part 2.";
			}
			if(empty($data['supervisorEmailAddress']))
			{
				$errors[] = "supervisorEmailAddress";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's email address for Part 2.";
			}
		}
		else if(!empty($data['companyName']))
		{
			if(empty($data['supervisorName']))
			{
				$errors[] = "supervisorName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's name for Part 2.";
			}
			if(empty($data['supervisorTitle']))
			{
				$errors[] = "supervisorTitle";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's title for Part 2.";
			}
			if(empty($data['supervisorPhoneNumber']))
			{
				$errors[] = "supervisorPhoneNumber";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's phone number for Part 2.";
			}
			if(empty($data['supervisorEmailAddress']))
			{
				$errors[] = "supervisorEmailAddress";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's email address for Part 2.";
			}
		}
		else if(!empty($data['supervisorTitle']))
		{
			if(empty($data['companyName']))
			{
				$errors[] = "companyName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's company for Part 2.";
			}
			if(empty($data['supervisorName']))
			{
				$errors[] = "supervisorName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's name for Part 2.";
			}
			if(empty($data['supervisorPhoneNumber']))
			{
				$errors[] = "supervisorPhoneNumber";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's phone number for Part 2.";
			}
			if(empty($data['supervisorEmailAddress']))
			{
				$errors[] = "supervisorEmailAddress";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's email address for Part 2.";
			}
		}
		else if(!empty($data['supervisorPhoneNumber']))
		{
			if(empty($data['companyName']))
			{
				$errors[] = "companyName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's company for Part 2.";
			}
			if(empty($data['supervisorTitle']))
			{
				$errors[] = "supervisorTitle";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's title for Part 2.";
			}
			if(empty($data['supervisorName']))
			{
				$errors[] = "supervisorName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's name for Part 2.";
			}
			if(empty($data['supervisorEmailAddress']))
			{
				$errors[] = "supervisorEmailAddress";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's email address for Part 2.";
			}
		}
		else if(!empty($data['supervisorEmailAddress']))
		{
			if(empty($data['companyName']))
			{
				$errors[] = "companyName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's company for Part 2.";
			}
			if(empty($data['supervisorTitle']))
			{
				$errors[] = "supervisorTitle";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's title for Part 2.";
			}
			if(empty($data['supervisorPhoneNumber']))
			{
				$errors[] = "supervisorPhoneNumber";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's phone number for Part 2.";
			}
			if(empty($data['supervisorName']))
			{
				$errors[] = "supervisorName";
				$error_messages[] = "If filling out section 9, you must fill out your supervisor's name for Part 2.";
			}
		}

		if(empty($data['major']))
		{
			$errors[] = "major";
			$error_messages[] = "Please enter your major for Part 3.";
		}
		if(empty($data['cluster1']))
		{
			$errors[] = "cluster1";
			$error_messages[] = "Please enter a cluster for Part 3.";
		}
		if(empty($data['academicPlanPerson']))
		{
			$errors[] = "academicPlanPerson";
			$error_messages[] = "Please enter the name of the person you have discussed your academic plan with for Part 3.";
		}

		$cnt = 0;
		
		if(!empty($data['courseRationale1']))
		{
			if(empty($data['courseNumber1']) ||
				empty($data['courseTitle1']) ||
				empty($data['courseCredit1']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber1']))
		{
			if(empty($data['courseRationale1']) ||
				empty($data['courseTitle1']) ||
				empty($data['courseCredit1']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle1']))
		{
			if(empty($data['courseNumber1']) ||
				empty($data['courseRationale1']) ||
				empty($data['courseCredit1']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit1']))
		{
			if(empty($data['courseNumber1']) ||
				empty($data['courseTitle1']) ||
				empty($data['courseRationale1']))
				{
					$cnt++;
				}
		}
		
		if(!empty($data['courseRationale2']))
		{
			if(empty($data['courseNumber2']) ||
				empty($data['courseTitle2']) ||
				empty($data['courseCredit2']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber2']))
		{
			if(empty($data['courseRationale2']) ||
				empty($data['courseTitle2']) ||
				empty($data['courseCredit2']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle2']))
		{
			if(empty($data['courseNumber2']) ||
				empty($data['courseRationale2']) ||
				empty($data['courseCredit2']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit2']))
		{
			if(empty($data['courseNumber2']) ||
				empty($data['courseTitle2']) ||
				empty($data['courseRationale2']))
				{
					$cnt++;
				}
		}
		
		if(!empty($data['courseRationale3']))
		{
			if(empty($data['courseNumber3']) ||
				empty($data['courseTitle3']) ||
				empty($data['courseCredit3']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber3']))
		{
			if(empty($data['courseRationale3']) ||
				empty($data['courseTitle3']) ||
				empty($data['courseCredit3']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle3']))
		{
			if(empty($data['courseNumber3']) ||
				empty($data['courseRationale3']) ||
				empty($data['courseCredit3']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit3']))
		{
			if(empty($data['courseNumber3']) ||
				empty($data['courseTitle3']) ||
				empty($data['courseRationale3']))
				{
					$cnt++;
				}
		}
		
		if(!empty($data['courseRationale4']))
		{
			if(empty($data['courseNumber4']) ||
				empty($data['courseTitle4']) ||
				empty($data['courseCredit4']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber4']))
		{
			if(empty($data['courseRationale4']) ||
				empty($data['courseTitle4']) ||
				empty($data['courseCredit4']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle4']))
		{
			if(empty($data['courseNumber4']) ||
				empty($data['courseRationale4']) ||
				empty($data['courseCredit4']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit4']))
		{
			if(empty($data['courseNumber4']) ||
				empty($data['courseTitle4']) ||
				empty($data['courseRationale4']))
				{
					$cnt++;
				}
		}
		
		if(!empty($data['courseRationale5']))
		{
			if(empty($data['courseNumber5']) ||
				empty($data['courseTitle5']) ||
				empty($data['courseCredit5']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber5']))
		{
			if(empty($data['courseRationale5']) ||
				empty($data['courseTitle5']) ||
				empty($data['courseCredit5']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle5']))
		{
			if(empty($data['courseNumber5']) ||
				empty($data['courseRationale5']) ||
				empty($data['courseCredit5']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit5']))
		{
			if(empty($data['courseNumber5']) ||
				empty($data['courseTitle5']) ||
				empty($data['courseRationale5']))
				{
					$cnt++;
				}
		}

		if(!empty($data['courseRationale6']))
		{
			if(empty($data['courseNumber6']) ||
				empty($data['courseTitle6']) ||
				empty($data['courseCredit6']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber6']))
		{
			if(empty($data['courseRationale6']) ||
				empty($data['courseTitle6']) ||
				empty($data['courseCredit6']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle6']))
		{
			if(empty($data['courseNumber6']) ||
				empty($data['courseRationale6']) ||
				empty($data['courseCredit6']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit6']))
		{
			if(empty($data['courseNumber6']) ||
				empty($data['courseTitle6']) ||
				empty($data['courseRationale6']))
				{
					$cnt++;
				}
		}

		if(!empty($data['courseRationale7']))
		{
			if(empty($data['courseNumber7']) ||
				empty($data['courseTitle7']) ||
				empty($data['courseCredit7']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber7']))
		{
			if(empty($data['courseRationale7']) ||
				empty($data['courseTitle7']) ||
				empty($data['courseCredit7']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle7']))
		{
			if(empty($data['courseNumber7']) ||
				empty($data['courseRationale7']) ||
				empty($data['courseCredit7']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit7']))
		{
			if(empty($data['courseNumber7']) ||
				empty($data['courseTitle7']) ||
				empty($data['courseRationale7']))
				{
					$cnt++;
				}
		}
		
		if(!empty($data['courseRationale8']))
		{
			if(empty($data['courseNumber8']) ||
				empty($data['courseTitle8']) ||
				empty($data['courseCredit8']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseNumber8']))
		{
			if(empty($data['courseRationale8']) ||
				empty($data['courseTitle8']) ||
				empty($data['courseCredit8']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseTitle8']))
		{
			if(empty($data['courseNumber8']) ||
				empty($data['courseRationale8']) ||
				empty($data['courseCredit8']))
				{
					$cnt++;
				}
		}
		if(!empty($data['courseCredit8']))
		{
			if(empty($data['courseNumber8']) ||
				empty($data['courseTitle8']) ||
				empty($data['courseRationale8']))
				{
					$cnt++;
				}
		}

		if($cnt > 0)
		{
			$error_messages[] = "You must complete entire course row for the course(s) you have entered for Part 3.";
			$errors[]='firstSemester';
		}

		if(empty($data['courseRationale1']) || empty($data['courseNumber1']) || empty($data['courseTitle1']) || empty($data['courseCredit1']))
		{
			$error_messages[] = "You must fill out at least one course row for your first semester back for Part 3.";
			$errors[] = 'courseRow';
		}
		
		if((empty($data['returnFullTime']) && empty($data['returnPartTime'])) || (!empty($data['returnFullTime']) && !empty($data['returnPartTime'])))
		{
			$errors[] = "returnFullTime";
			$error_messages[] = "You must only choose one option whether you will be a Full-Time or Part-Time student for Part 3.";
		}

		if(empty($data['numberOfSemesters']))
		{
			$errors[] = "numberOfSemesters";
			$error_messages[] = "Please enter the number of semesters that it will take for you to complete your degree for Part 3.";
		}

		if(empty($data['graduationMonth']))
		{
			$errors[] = "graduationMonth";
			$error_messages[] = "Please enter the month you anticipate graduating for Part 3.";
		}
		if(empty($data['graduationYear']))
		{
			$errors[] = "graduationYear";
			$error_messages[] = "Please enter the year you anticipate graduating for Part 3.";
		}
		
		CheckEssayUploaded();
		if(!$essayUploaded)
		{
			$error_messages[] = "Please attach an essay addressing your academic plan for Part 4.";
			$errors[]='essay';
		}

		return $errors;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for an input array of items in Key : Value pairs.
// - $array - passed in array of items to make options for in [VALUE : DISPLAY] pairs.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetGraduationYearOptionsFromArray()
{
    global $formData;
    $selected = '';
    $array = GetYearAfterOptions(5);
    $options = "<option value=''>--Select--</option>";
		
	foreach($array as $data)
    {
        $options .= "<option value='$data' " . (($selected == $data || $formData['graduationYear'] == $data)? 'selected' : '') . ">$data</option>";	
    }

	return $options;
}
//------------------------------------------------------------------------------------------------------
// Function to return options after how many years after this year
// - $selected - what option should be marked as selected.
// - $end - how many years after this year do we stop
//------------------------------------------------------------------------------------------------------
function GetYearAfterOptions($end)
{
	$year = date("Y");
		
	$array = array();
		
	for($i = $year; $i <= ($year + $end); $i++)
	{
		$array[] = $i;
	}
		
	return $array;
}
//------------------------------------------------------------------------------------------------------
	// Function to return options for an input array of items in Key : Value pairs.
	// - $array - passed in array of items to make options for in [VALUE : DISPLAY] pairs.
	// - $selected - what option should be marked as selected.
	//------------------------------------------------------------------------------------------------------
function GetTermOptionsFromArrayKeys()
{
	global $formData;
    $selected = '';
    $array = GetTermOptions(5);
	$options = "<option value=''>-- Select --</option>";
		
	foreach($array as $value)
    {
        $options .= "<option value='$value' " . (($selected == $value || $formData['semesterEnroll'] == $value) ? 'selected' : '') . ">$value</option>";	
    }
	return $options;
}
//------------------------------------------------------------------------------------------------------
	// Function to return the options for a Term drop down menu running forward from the current term
	// - $length - how far to run the list, if negative we're starting there and running to current
	// - $selected - what option should be marked as selected
	// - Terms are specified in the following way - Two Digit Year followed by term 
	//   e.g. 141 - Fall 2013
	//        142 - Spring 2014
	//        144 - Summer 2014
	//        151 - Fall 2014
	//   
	//------------------------------------------------------------------------------------------------------
function GetTermOptions($length, $selected = '')
{
	$year = date("Y");		
	$start = ($length > 0 ? $year : $year + $length);
	$end = ($length > 0 ? $year + $length : $year);	
		
	$array = array();
		
	for($i = $start ; $i <= $end; $i++)
	{			
		$spring = "Spring " . $i;
		$summer = "Summer " . $i;
		$fall = "Fall " . $i;
			
		$fallTerm = substr(($i + 1),2,2) . "1";
		$springTerm = substr($i,2,2) . "2";
		$summerTerm = substr($i,2,2) . "4";
			
			
		$array[$springTerm] = $spring;
		$array[$summerTerm] = $summer;
		$array[$fallTerm] = $fall;
	}
		
	return $array;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for an input array of items in Key : Value pairs.
// - $array - passed in array of items to make options for in [VALUE : DISPLAY] pairs.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetBirthMonthOptionsFromArray()
{
    global $formData;
    $selected = '';
    $array = GetMonthOptions();
    $options = "<option value=''>--Select--</option>";
		
	foreach($array as $data)
    {
        $options .= "<option value='$data' " . (($selected == $data || $formData['studentBirthMonth'] == $data)? 'selected' : '') . ">$data</option>";	
    }

	return $options;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for an input array of items in Key : Value pairs.
// - $array - passed in array of items to make options for in [VALUE : DISPLAY] pairs.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetMonthEnteredOptionsFromArray()
{
    global $formData;
    $selected = '';
    $array = GetMonthOptions();      
    $options = "<option value=''>--Select--</option>";
		
	foreach($array as $data)
    {
        $options .= "<option value='$data' " . (($selected == $data || $formData['monthEntered'] == $data)? 'selected' : '') . ">$data</option>";	
    }
		
	return $options;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for an input array of items in Key : Value pairs.
// - $array - passed in array of items to make options for in [VALUE : DISPLAY] pairs.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetMonthLeftOptionsFromArray()
{
    global $formData;
    $selected = '';
    $array = GetMonthOptions();
    $options = "<option value=''>--Select--</option>";
		
	foreach($array as $data)
    {
        $options .= "<option value='$data' " . (($selected == $data || $formData['monthLeft'] == $data) ? 'selected' : '') . ">$data</option>";	
    }
		
	return $options;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for an input array of items in Key : Value pairs.
// - $array - passed in array of items to make options for in [VALUE : DISPLAY] pairs.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetMonthAppliedBeforeOptionsFromArray()
{
    global $formData;
    $selected = '';
    $array = GetMonthOptions();
            
    $options = "<option value=''>--Select--</option>";
		
	foreach($array as $data)
    {
        $options .= "<option value='$data' " . (($selected == $data || $formData['monthAppliedBefore'] == $data) ? 'selected' : '') . ">$data</option>";	
    }
		
	return $options;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for Months.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetMonthOptions($selected = '')
{
	$array = array();
	$array[1] = 'January';
	$array[2] = 'February';
	$array[3] = 'March';
	$array[4] = 'April';
	$array[5] = 'May';
	$array[6] = 'June';
	$array[7] = 'July';
	$array[8] = 'August';
	$array[9] = 'September';
	$array[10] = 'October';
	$array[11] = 'November';
	$array[12] = 'December';
		
	return $array;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for an input array of items.
// - $array - passed in array of items to make options for.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetBirthDayOptionsFromArray()
{
    global $formData;
    $selected = '';
    $array = GetDayOptions();
	$options = "<option value=''>--Select--</option>";
	
	foreach($array as $data)
    {
        $options .= "<option value='$data' " . (($selected == $data || $formData['studentBirthDay']) ? 'selected' : '') . ">$data</option>";	
    }
		
	return $options;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for Days.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetDayOptions($selected = '')
{
	$array = array();
		
	for($i = 1; $i <= 31; $i++)
	{
		$array[] = $i;
	}
		
	return $array;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for an input array of items in Key : Value pairs.
// - $array - passed in array of items to make options for in [VALUE : DISPLAY] pairs.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetBirthYearOptionsFromArray()
{
    global $formData;
    $selected = '';
    $array = GetYearOptions(40,0);
    $options = "<option value=''>--Select--</option>";
		
	foreach($array as $data)
    {
        $options .= "<option value='$data' " . (($selected == $data || $formData['studentBirthYear'] == $data) ? 'selected' : '') . ">$data</option>";	
    }
		
	return $options;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for an input array of items in Key : Value pairs.
// - $array - passed in array of items to make options for in [VALUE : DISPLAY] pairs.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetYearEnteredOptionsFromArray()
{
    global $formData;
    $selected = '';
    $array = GetYearOptions(40,0);
    $options = "<option value=''>--Select--</option>";
		
	foreach($array as $data)
    {
        $options .= "<option value='$data' " . (($selected == $data || $formData['yearEntered'] == $data) ? 'selected' : '') . ">$data</option>";	
    }
		
	return $options;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for an input array of items in Key : Value pairs.
// - $array - passed in array of items to make options for in [VALUE : DISPLAY] pairs.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetYearAppliedBeforeOptionsFromArray()
{
    global $formData;
    $selected = '';
    $array = GetYearOptions(40,0);
    $options = "<option value=''>--Select--</option>";
		
	foreach($array as $data)
    {
        $options .= "<option value='$data' " . (($selected == $data || $formData['yearAppliedBefore'] == $data)? 'selected' : '') . ">$data</option>";	
    }
		
	return $options;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for an input array of items in Key : Value pairs.
// - $array - passed in array of items to make options for in [VALUE : DISPLAY] pairs.
// - $selected - what option should be marked as selected.
//------------------------------------------------------------------------------------------------------
function GetYearLeftOptionsFromArray()
{
    global $formData;
    $selected = '';
    $array = GetYearOptions(40,0);
    $options = "<option value=''>--Select--</option>";
		
	foreach($array as $data)
    {
        $options .= "<option value='$data' " . (($selected == $data || $formData['yearLeft'] == $data) ? 'selected' : '') . ">$data</option>";	
    }
		
	return $options;
}
//------------------------------------------------------------------------------------------------------
// Function to return options for Years.
// - $selected - what option should be marked as selected.
// - $start - how many years before this year do we start
// - $end - how many years after this year do we stop
//------------------------------------------------------------------------------------------------------
function GetYearOptions($start,$end)
{
	$year = date("Y");
	$array = array();
		
	for($i = $year - $start; $i <= ($year - $end); $i++)
	{
		$array[] = $i;
	}
		
	return $array;
}
//-------------------------------------------------------------------
function ProcessSave($data, $part)
{
	global $userData;
	$db_drvr = new MySQLDriver();
	
	/* Submit this record to MySQL */
	$record = array();
	if($part == "Part 1")
	{
		//strips all parenthesis and dashes from phone number to ensure submission
		$studentHomePhoneNumber = preg_replace("/[^0-9]/", "", $data['studentHomePhoneNumber']);
		$studentCellPhoneNumber = preg_replace("/[^0-9]/", "", $data['studentCellPhoneNumber']);
		$parent1PhoneNumber = preg_replace("/[^0-9]/", "", $data['parent1PhoneNumber']);
		$parent2PhoneNumber = preg_replace("/[^0-9]/", "", $data['parent2PhoneNumber']);

		$record['studentHomePhoneNumber'] = $studentHomePhoneNumber;
		$record['studentCellPhoneNumber'] = $studentCellPhoneNumber;
		$record['parent1PhoneNumber'] = $parent1PhoneNumber;
		$record['parent2PhoneNumber'] = $parent2PhoneNumber;
		if(empty($data['studentIsInternational']))
		{
			$record['studentIsInternational'] = "";
		}
		if(empty($data['studentIs21AndOver']))
		{
			$record['studentIs21AndOver'] = "";
		}
	}
	if($part == "Part 2")
	{
		if(empty($data['appliedBeforeYes']))
		{
			$record['appliedBeforeYes'] = "";
		}
		if(empty($data['appliedBeforeNo']))
		{
			$record['appliedBeforeNo'] = "";
		}
		if(empty($data['onCampusRequired']))
		{
			$record['onCampusRequired'] = "";
		}
		if(empty($data['liveOnCampusYes']))
		{
			$record['liveOnCampusYes'] = "";
		}
		if(empty($data['liveOnCampusNo']))
		{
			$record['liveOnCampusNo'] = "";
		}
		if(empty($data['medicalYes']))
		{
			$record['medicalYes'] = "";
		}
		if(empty($data['medicalNo']))
		{
			$record['medicalNo'] = "";
		}
	}
	if($part == "Part 3")
	{
		if(empty($data['majorDeclared']))
		{
			$record['majorDeclared'] = "";
		}
		if(empty($data['returnFullTime']))
		{
			$record['returnFullTime'] = "";
		}
		if(empty($data['returnPartTime']))
		{
			$record['returnPartTime'] = "";
		}
	}


	foreach($data as $key => $value)
	{
		/* STRIP OUT ANY KEYS YOU'RE NOT SENDING TO THE MYSQL TABLE */
		if($key != 'Save' && $key != 'uploadEssay' && $key != 'Essay' 
			&& $key != 'Home' && $key != 'Part1' && $key != 'Part2' && $key != 'Part3' && $key != 'Review' 
			&& $key != 'SavePart1AndContinue' && $key != 'SavePart2AndContinue' && $key != 'SavePart3AndContinue'
			&& $key != 'Submit'
			&& $key != 'Logout')
		{
			$record[$key] = $value;	
		}
	}
	$record['ipAddress'] = $_SERVER['REMOTE_ADDR'];
	$record['dateSaved'] = date('Y-m-d H:i:s', time());
	
	$id = $db_drvr->UpdateRecord('ReadmissionApplication',$userData['recordID'],$record);
	
	if($id == 0)
		return false;
	return true;
}
//-------------------------------------------------------------------
function ReturnEssayFile()
{
	global $userData;
	$db_drvr = new MySQLDriver();
	$results = $db_drvr->ReadTable('ReadmissionApplication',array('recordID' => $userData['recordID']));
	$student = $results[0];
	//$fileContents;
	$targetDir = "/www_vol/data/secure1-data/ccas/form_processors/readmission_uploads/" . $student['recordID'] . "/";
	$pdfName = $targetDir . $student['essay'];
	$dest = $student['essay'];
	// read the file from remote location
	$current = file_get_contents($pdfName);
	// Write the contents back to the file
	file_put_contents($dest, $current);
	$pdfPath = "https://secure1.wdev.rochester.edu/ccas/form_processors/readmission_uploads/" . $student['recordID'] . "/";
	$pdfURL = $pdfPath . $student['essay'];
	return $pdfURL;
}
//-------------------------------------------------------------------
/*function ProcessFM()
{
	global $userData;
	$db_driver = new MySQLDriver();
	$results = $db_driver->ReadTable('ReadmissionApplication', array('recordID' => $userData['recordID']));
	$data = $results[0];

	$fmdb_driver = new FMDBDriver();

	//Submit this record to FileMaker 
	$records = array();

	$records['URID'] = $data['studentID'];
	$records['recordID'] = $data['recordID'];
	$records['semesterEnroll'] = $data['semesterEnroll'];
	$records['studentFirstName'] = $data['studentFirstName'];
	$records['studentMiddleInitial'] = $data['studentMiddleInitial'];
	$records['studentLastName'] = $data['studentLastName'];
	$records['studentPreferredName'] = $data['studentPreferredName'];
	$records['studentFormerName'] = $data['studentFormerName'];
	$records['advisorName'] = $data['advisorName'];

	$records['studentAddressLine1'] = $data['studentAddressLine1'];
	$records['studentAddressLine2'] = $data['studentAddressLine2'];

	$records['studentCity'] = $data['studentCity'];
	$records['studentCountry'] = $data['studentCountry'];
	$records['studentState'] = $data['studentState'];
	$records['studentZipCode'] = $data['studentZipCode'];

	$records['studentBirthDate'] = $data['studentBirthMonth'] . " " . $data['studentBirthDay'] . " " . $data['studentBirthYear'];

	$records['studentHomePhoneNumber'] = $data['studentHomePhoneNumber'];
	$records['studentCellPhoneNumber'] = $data['studentCellPhoneNumber'];
	$records['studentEmailAddress'] = $data['studentEmailAddress'];

	if($data['studentIsInternational'] == "Yes")
		$records['studentIsInternational'] = "X";
	else
		$records['studentIsInternational'] = "";

	if($data['studentIs21AndOver'] == "Yes")
		$records['studentIs21AndOver'] = "X";
	else
		$records['studentIs21AndOver'] = "";

	$records['dateEnteredUR'] = $data['monthEntered'] . " " . $data['yearEntered'];

	$records['dateLeftUR'] = $data['monthLeft'] . " " . $data['yearLeft'];

	if($data['appliedBeforeYes'] == "Yes")
		$records['appliedBeforeYes'] = "X";
	else
		$records['appliedBeforeYes'] = "";

	if($data['appliedBeforeNo'] == "Yes")
		$records['appliedBeforeNo'] = "X";
	else
		$records['appliedBeforeNo'] = "";

	$records['dateAppliedBefore'] = $data['monthAppliedBefore'] . " " . $data['yearAppliedBefore'];

	if($data['onCampusRequired'] == "Yes")
		$records['onCampusRequired'] = "X";
	else
		$records['onCampusRequired'] = "";

	if($data['liveOnCampusYes'] == "Yes")
		$records['liveOnCampusYes'] = "X";
	else
		$records['liveOnCampusYes'] = "";

	if($data['liveOnCampusNo'] == "Yes")
		$records['liveOnCampusNo'] = "X";
	else
		$records['liveOnCampusNo'] = "";

	
	if($data['medicalYes'] == "Yes")
		$records['medicalYes'] = "X";
	else
		$records['medicalYes'] = "";
	
	if($data['medicalNo'] == "Yes")
		$records['medicalNo'] = "X";
	else
		$records['medicalNo'] = "";
	
	$records['firstInstitution'] = $data['firstInstitution'];
	$records['firstInstitutionDate'] = $data['firstInstitutionDate'];
	$records['secondInstitution'] = $data['secondInstitution'];
	$records['secondInstitutionDate'] = $data['secondInstitutionDate'];
	$records['supervisorName'] = $data['supervisorName'];
	$records['companyName'] = $data['companyName'];
	$records['supervisorTitle'] = $data['supervisorTitle'];
	$records['supervisorPhoneNumber'] = $data['supervisorPhoneNumber'];
	$records['supervisorEmailAddress'] = $data['supervisorEmailAddress'];
	
	$records['major'] = $data['major'];
	$records['minor'] = $data['minor'];
	$records['cluster1'] = $data['cluster1'];
	$records['cluster2'] = $data['cluster2'];
	$records['academicPlanPerson'] = $data['academicPlanPerson'];

	if($data['majorDeclared'] == "Yes")
		$records['majorDeclared'] = "X";
	else
		$records['majorDeclared'] = "";

	if($data['returnFullTime'] == "Yes")
		$records['returnFullTime'] = "X";
	else
		$records['returnFullTime'] = "";

	if($data['returnPartTime'] == "Yes")
		$records['returnPartTime'] = "X";
	else
		$records['returnPartTime'] = "";
	
	$records['numberOfSemesters'] = $data['numberOfSemesters'];

	$records['graduationDate'] = $data['graduationMonth'] . " " . $data['graduationYear'];

	$records['essayFile'] = ReturnEssayFile();
	$records['essayUploadedOn'] = $data['essayUploadedOn'];
	$records['dateSubmitted'] = $data['dateSubmitted'];

	$cnt = 0;

	$records['fm_database'] = 'Readmission Application'; //L00 main student view
	$records['fm_layout'] = 'Student Data';
	$records['fm_username'] = 'Web';
	$records['fm_password'] = 'Web';
	$id = $fmdb_driver->Submit($records);
	//echo var_dump($id);
	
	if($id != 0)
		$cnt++; 
	
	$records = array();

	$records['URID'] = $data['studentID'];
	$records['recordID'] = $data['recordID'];
	$records['parent1FirstName'] = $data['parent1FirstName'];
	$records['parent1LastName'] = $data['parent1LastName'];
	$records['parent1AddressLine1'] = $data['parent1AddressLine1'];
	$records['parent1AddressLine2'] = $data['parent1AddressLine2'];
	$records['parent1City'] = $data['parent1City'];
	$records['parent1Country'] = $data['parent1Country'];
	$records['parent1State'] = $data['parent1State'];
	$records['parent1ZipCode'] = $data['parent1ZipCode'];
	$records['parent1PhoneNumber'] = $data['parent1PhoneNumber'];
	$records['parent1EmailAddress'] = $data['parent1EmailAddress'];

	$records['parent2FirstName'] = $data['parent2FirstName'];
	$records['parent2LastName'] = $data['parent2LastName'];
	$records['parent2AddressLine1'] = $data['parent2AddressLine1'];
	$records['parent2AddressLine2'] = $data['parent2AddressLine2'];
	$records['parent2City'] = $data['parent2City'];
	$records['parent2Country'] = $data['parent2Country'];
	$records['parent2State'] = $data['parent2State'];
	$records['parent2ZipCode'] = $data['parent2ZipCode'];
	$records['parent2PhoneNumber'] = $data['parent2PhoneNumber'];
	$records['parent2EmailAddress'] = $data['parent2EmailAddress'];

	$records['fm_database'] = 'Readmission Application';
	$records['fm_layout'] = 'L60 parent address';
	$records['fm_username'] = 'Web';
	$records['fm_password'] = 'Web';

	$id = $fmdb_driver->Submit($records);
	
	if($id != 0)
		$cnt++;
	
	$records = array();
	
	$records['URID'] = $data['studentID'];
	$records['recordID'] = $data['recordID'];
	$records['courseRationale1'] = $data['courseRationale1'];
	$records['courseNumber1'] = $data['courseNumber1'];
	$records['courseTitle1'] = $data['courseTitle1'];
	$records['courseCredit1'] = $data['courseCredit1'];

	$records['fm_database'] = 'Readmission Application';
	$records['fm_layout'] = 'L30 course data';
	$records['fm_username'] = 'Web';
	$records['fm_password'] = 'Web';

	$id = $fmdb_driver->Submit($records);
	
	if($id != 0)
		$cnt++;
	
	$records = array();

	if(!empty($data['courseRationale2']))
	{
		$records = array();
		$records['URID'] = $data['studentID'];
		$records['recordID'] = $data['recordID'];
		$records['courseRationale'] = $data['courseRationale2'];
		$records['courseNumber'] = $data['courseNumber2'];
		$records['courseTitle'] = $data['courseTitle2'];
		$records['courseCredit'] = $data['courseCredit2'];

		$records['fm_database'] = 'Readmission Application';
		$records['fm_layout'] = 'L30 course data';
		$records['fm_username'] = 'Web';
		$records['fm_password'] = 'Web';

		$id = $fmdb_driver->Submit($records);
		
		if($id != 0)
			$cnt++;
	}
	if(!empty($data['courseRationale3']))
	{
		$records = array();
		$records['URID'] = $data['studentID'];
		$records['recordID'] = $data['recordID'];
		$records['courseRationale'] = $data['courseRationale3'];
		$records['courseNumber'] = $data['courseNumber3'];
		$records['courseTitle'] = $data['courseTitle3'];
		$records['courseCredit'] = $data['courseCredit3'];

		$records['fm_database'] = 'Readmission Application';
		$records['fm_layout'] = 'L30 course data';
		$records['fm_username'] = 'Web';
		$records['fm_password'] = 'Web';

		$id = $fmdb_driver->Submit($records);
		
		if($id != 0)
			$cnt++;
	}
	if(!empty($data['courseRationale4']))
	{
		$records = array();
		$records['URID'] = $data['studentID'];
		$records['recordID'] = $data['recordID'];
		$records['courseRationale'] = $data['courseRationale4'];
		$records['courseNumber'] = $data['courseNumber4'];
		$records['courseTitle'] = $data['courseTitle4'];
		$records['courseCredit'] = $data['courseCredit4'];

		$records['fm_database'] = 'Readmission Application';
		$records['fm_layout'] = 'L30 course data';
		$records['fm_username'] = 'Web';
		$records['fm_password'] = 'Web';

		$id = $fmdb_driver->Submit($records);
		
		if($id != 0)
			$cnt++;
	}
	if(!empty($data['courseRationale5']))
	{
		$records = array();
		$records['URID'] = $data['studentID'];
		$records['recordID'] = $data['recordID'];
		$records['courseRationale'] = $data['courseRationale5'];
		$records['courseNumber'] = $data['courseNumber5'];
		$records['courseTitle'] = $data['courseTitle5'];
		$records['courseCredit'] = $data['courseCredit5'];

		$records['fm_database'] = 'Readmission Application';
		$records['fm_layout'] = 'L30 course data';
		$records['fm_username'] = 'Web';
		$records['fm_password'] = 'Web';

		$id = $fmdb_driver->Submit($records);
		
		if($id != 0)
			$cnt++;
	}
	if(!empty($data['courseRationale6']))
	{
		$records = array();
		$records['URID'] = $data['studentID'];
		$records['recordID'] = $data['recordID'];
		$records['courseRationale'] = $data['courseRationale6'];
		$records['courseNumber'] = $data['courseNumber6'];
		$records['courseTitle'] = $data['courseTitle6'];
		$records['courseCredit'] = $data['courseCredit6'];

		$records['fm_database'] = 'Readmission Application';
		$records['fm_layout'] = 'L30 course data';
		$records['fm_username'] = 'Web';
		$records['fm_password'] = 'Web';

		$id = $fmdb_driver->Submit($records);
		
		if($id != 0)
			$cnt++;
	}
	if(!empty($data['courseRationale7']))
	{
		$records = array();
		$records['URID'] = $data['studentID'];
		$records['recordID'] = $data['recordID'];
		$records['courseRationale'] = $data['courseRationale7'];
		$records['courseNumber'] = $data['courseNumber7'];
		$records['courseTitle'] = $data['courseTitle7'];
		$records['courseCredit'] = $data['courseCredit7'];

		$records['fm_database'] = 'Readmission Application';
		$records['fm_layout'] = 'L30 course data';
		$records['fm_username'] = 'Web';
		$records['fm_password'] = 'Web';

		$id = $fmdb_driver->Submit($records);
		
		if($id != 0)
			$cnt++;
	}
	if(!empty($data['courseRationale8']))
	{
		$records = array();
		$records['URID'] = $data['studentID'];
		$records['recordID'] = $data['recordID'];
		$records['courseRationale'] = $data['courseRationale8'];
		$records['courseNumber'] = $data['courseNumber8'];
		$records['courseTitle'] = $data['courseTitle8'];
		$records['courseCredit'] = $data['courseCredit8'];

		$records['fm_database'] = 'Readmission Application';
		$records['fm_layout'] = 'L30 course data';
		$records['fm_username'] = 'Web';
		$records['fm_password'] = 'Web';

		$id = $fmdb_driver->Submit($records);
		
		if($id != 0)
			$cnt++;
	}
	
	if($cnt >= 3)
	{
		$db_driver->UpdateRecord('ReadmissionApplication',$userData['recordID'],array('fileMakerSubmitted' => 1));
		return true;
	}
	else
		return false;
	
}*/
//-------------------------------------------------------------------
function ProcessSubmit()
{
	global $userData;
	$db_drvr = new MySQLDriver();
	
	$record['password'] = "";	// SHOULD I ONLY ALLOW ONE APPLICATION PER ACCOUNT?????????????????????
	$record['ipAddress'] = $_SERVER['REMOTE_ADDR'];
	$record['dateSubmitted'] = date('Y-m-d H:i:s', time());
	
	$id = $db_drvr->UpdateRecord('ReadmissionApplication',$userData['recordID'],$record);
	
	if($id == 0)
		return false;
	return true;
}
//-------------------------------------------------------------------
function SendEmail($date)
{
	global $userData;
	$db_driver = new MySQLDriver();
	$results = $db_driver->ReadTable('ReadmissionApplication', array('recordID' => $userData['recordID']));
	$data = $results[0];

	CheckEssayUploaded();
	global $essayUploadedName;
	
	$dir = "/www_vol/data/secure1-data/ccas/form_processors/readmission_uploads/" . $data['recordID'] . "/";
	$pdfName = $dir . $essayUploadedName;
	

	$to = $data['studentEmailAddress'];
	//$from = 'cascas@ur.rochester.edu';
	$from = 'lvasavon@u.rochester.edu';
	$headers = "From: College Center for Advising Services"." <".$from.">";

	//boundary 
	$semi_rand = md5(time()); 
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

	//headers for attachment 
	$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
	
	
	
	$subject = 'Reenrollment Application Submission';
	
	/*$headers = "From: cascas@ur.rochester.edu\r\n"; {padding: 2px; text-align: left;} 
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";*/
	
	$message = "<html><head><style> th, td{padding: 2px; text-align: left;} @media only screen and (max-width:480px){table {width:100% !important; max-width:480px !important;} </style></head><body>";
	$message .= "<table style='width:100%;' >";
	$message .= "<tr><td colspan='2' style='width: 100%; text-align: center; background-color: #021e47; color: #FFC125; border-bottom: 3px solid #FFC125; border-radius: 10px;'>Arts, Sciences and Engineering<hr/><div style='color:white; font-size: 175%; padding: 0px 0px 15px 0px;'><span style='color:#FFC125;'>U</span><span style='font-variant:small-caps;'>niversity</span> <i>of</i> <span style='color:#FFC125;'>R</span><span style='font-variant:small-caps;'>ochester</span></div></td></tr>";
	$message .= "<tr><td>Your account has been deactivated. If you wish to submit another Reenrollment Application, please make another account.</td></tr>";
	$message .= "<tr><td colspan='2'>You have submitted a Reenrollment Application:<div align='center'><b>1. Personal Information</b></div><hr/></td></tr>";
	$message .= "<tr><td>Semester Enrolling:</td><td>" . $data['semesterEnroll'] . "</td></tr>";
	$message .= "<tr><td>Student Name:</td><td>" . $data['studentFirstName'] . " " . $data['studentMiddleInitial'] . " " . $data['studentLastName'] . "</td></tr>";
	$message .= "<tr><td>Preferred Name:</td><td>" . $data['studentPreferredName'] . "</td></tr>";
	$message .= "<tr><td>Student ID:</td><td>" . $data['studentID'] . "</td></tr>";
	$message .= "<tr><td>Former Name(s), if applicable:</td><td>" . $data['studentFormerName'] . "</td></tr>";
	$message .= "<tr><td>Name of Advisor(s):</td><td>" . $data['advisorName'] . "</td></tr>";
	$message .= "<tr><td>Address Line 1:</td><td>" . $data['studentAddressLine1'] . "</td></tr>";
	$message .= "<tr><td>Address Line 2:</td><td>" . $data['studentAddressLine2'] . "</td></tr>";
	$message .= "<tr><td>City:</td><td>" . $data['studentCity'] . "</td></tr>";
	$message .= "<tr><td>Country:</td><td>" . $data['studentCountry'] . "</td></tr>";
	$message .= "<tr><td>State:</td><td>" . $data['studentState'] . "</td></tr>";
	$message .= "<tr><td>Zip Code:</td><td>" . $data['studentZipCode'] . "</td></tr>";
	$message .= "<tr><td>Birth Date:</td><td>" . $data['studentBirthMonth'] . " " . $data['studentBirthDay'] . " " . $data['studentBirthYear'] . "</td></tr>";
	$message .= "<tr><td>Home Phone Number:</td><td>" . $data['studentHomePhoneNumber'] . "</td></tr>";
	$message .= "<tr><td>Cell Phone Number:</td><td>" . $data['studentCellPhoneNumber'] . "</td></tr>";
	$message .= "<tr><td>Email Address:</td><td>" . $data['studentEmailAddress'] . "</td></tr>";
	$studentIsInternational;
	if(!empty($data['studentIsInternational'])) 
	{
		$studentIsInternational = $data['studentIsInternational'];
	} 
	else 
	{
		$studentIsInternational = "No";
	}
	$message .= "<tr><td>You are an international student:</td><td>" . $studentIsInternational . "</td></tr>";
	$studentIs21AndOver;
	if(!empty($data['studentIs21AndOver'])) 
	{
		$studentIs21AndOver = $data['studentIs21AndOver'];
	} 
	else 
	{
		$studentIs21AndOver = "No";
	}
	$message .= "<tr><td>You are 21 and over and do not need to fill out the Parent(s) Information Section:</td><td>" . $studentIs21AndOver . "</td></tr>";

	$message .= "<tr><td colspan='2'><div align='center'><b>2: Parent(s)/Guardian(s) Information</b></div><hr/></td></tr>";
	$message .= "<tr><td><b>Parent 1 Information</b></td></tr>";
	$message .= "<tr><td>First Name:</td><td>" . $data['parent1FirstName'] . "</td></tr>";
	$message .= "<tr><td>Last Name:</td><td>" . $data['parent1LastName'] . "</td></tr>";
	$message .= "<tr><td>Address Line 1:</td><td>" . $data['parent1AddressLine1'] . "</td></tr>";
	$message .= "<tr><td>Address Line 2:</td><td>" . $data['parent1AddressLine2'] . "</td></tr>";
	$message .= "<tr><td>City:</td><td>" . $data['parent1City'] . "</td></tr>";
	$message .= "<tr><td>Country</td><td>" . $data['parent1Country'] . "</td></tr>";
	$message .= "<tr><td>State</td><td>" . $data['parent1State'] . "</td></tr>";
	$message .= "<tr><td>Zip Code</td><td>" . $data['parent1ZipCode'] . "</td></tr>";
	$message .= "<tr><td>Phone Number</td><td>" . $data['parent1PhoneNumber'] . "</td></tr>";
	$message .= "<tr><td>Email Address</td><td>" . $data['parent1EmailAddress'] . "</td></tr>";
	$message .= "<tr><td><b>Parent 2 Information</b></td></tr>";
	$message .= "<tr><td>First Name:</td><td>" . $data['parent2FirstName'] . "</td></tr>";
	$message .= "<tr><td>Last Name:</td><td>" . $data['parent2LastName'] . "</td></tr>";
	$message .= "<tr><td>Address Line 1:</td><td>" . $data['parent2AddressLine1'] . "</td></tr>";
	$message .= "<tr><td>Address Line 2:</td><td>" . $data['parent2AddressLine2'] . "</td></tr>";
	$message .= "<tr><td>City:</td><td>" . $data['parent2City'] . "</td></tr>";
	$message .= "<tr><td>Country</td><td>" . $data['parent2Country'] . "</td></tr>";
	$message .= "<tr><td>State</td><td>" . $data['parent2State'] . "</td></tr>";
	$message .= "<tr><td>Zip Code</td><td>" . $data['parent2ZipCode'] . "</td></tr>";
	$message .= "<tr><td>Phone Number</td><td>" . $data['parent2PhoneNumber'] . "</td></tr>";
	$message .= "<tr><td>Email Address</td><td>" . $data['parent2EmailAddress'] . "</td></tr>";

	$message .= "<tr><td colspan='2'><div align='center'><b>3: Attendance and Application History</b></div><hr/></td></tr>";
	$message .= "<tr><td>Date you entered the University of Rochester (Month/Year):</td><td>" . $data['monthEntered'] . " " . $data['yearEntered'] . "</td></tr>";
	$message .= "<tr><td>Date you left the University of Rochester (Month/Year):</td><td>" . $data['monthLeft'] . " " . $data['yearLeft'] . "</td></tr>";
	if(!empty($data['appliedBeforeYes']))
	{
		$message .= "<tr><td>You applied to return to University of Rochester through the Re-Enrollment Application before:</td><td>" . $data['appliedBeforeYes'] . "</td></tr>";
		$message .= "<tr><td>If yes, when:</td><td>" . $data['monthAppliedBefore'] . " " . $data['yearAppliedBefore'] . "</td></tr>";
	}
	else
	{
		$message .= "<tr><td>You applied to return to University of Rochester through the Re-Enrollment Application before:</td><td>" . "No" . "</td></tr>";
	}

	$message .= "<tr><td colspan='2'><div align='center'><b>4. Arrival Information</b></div><hr/></td></tr>";
	$message .= "<tr><td colspan='2'>Reenrolling students are required to be on campus prior to the start of classes to meet with their advisors. Students who are unable to do so will not be readmitted.</td></tr>";
	$message .= "<tr><td>I acknowledge that If I am readmitted, I will arrive at least 48 hours prior to the start of classes:</td><td>" . $data['onCampusRequired'] . "</td></tr>";

	$message .= "<tr><td colspan='2'><div align='center'><b>5. Housing Preference</b></div><hr/></td></tr>";
	$message .= "<tr><td colspan='2'>Please make us aware of your interest with housing for when you return. Unfortunately, housing is not guaranteed for reenrolling students. Assignments are made on a rolling basis for both fall and spring semesters. More information will be provided once your application is received.</td></tr>";
	if(!empty($data['liveOnCampusYes']))
	{
		$message .= "<tr><td>I desire to live on campus:</td><td>" . $data['liveOnCampusYes'] . "</td></tr>";
	}
	else
	{
		$message .= "<tr><td>I desire to live off campus:</td><td>" . $data['liveOnCampusNo'] . "</td></tr>";
	}

	$message .= "<tr><td colspan='2'><div align='center'><b>6. Financial Information</b></div><hr/></td></tr>";
	$message .= "<tr><td colspan='2'>Financial aid deadlines are the same as the re-enrollment deadlines: November 1 and May 1. These are also the deadlines for clearing up any balances that remain on your account from your previous enrollment. You can review information about deadlines and financial aid eligibility on the re-enrollment website <a href='https://www.rochester.edu/college/ccas/handbook/financial-aid.html' target='_new'>https://www.rochester.edu/college/ccas/handbook/financial-aid.html</a>. You may contact the University of Rochester’s Bursar Office at <a href='https://www.rochester.edu/adminfinance/bursar/' target='_new'>https://www.rochester.edu/adminfinance/bursar/</a> or (585) 275-3931 with any questions you may have regarding your account.</td></tr>";
	
	$message .= "<tr><td colspan='2'><div align='center'><b>7. Health/Mental Health Care Provider Information (if applicable)</b></div><hr/></td></tr>";
	$message .= "<tr><td colspan='2'>If you are on a medical leave, or if medical issues were a factor in your leaving the University of Rochester, your application will not be complete without approval from UHS or UCC. You should contact UHS (585) 275-2679 or email mlivingston@uhs.rochester.edu) or UCC (585) 275-3115 well before the reenrollment application deadline to discuss your plans to return. The dean of the College, following the recommendation made by UHS/UCC, will make a final decision regarding reenrollment or the deferment of your application to the next semester’s reenrollment cycle.</td></tr>";
	if(!empty($data['medicalYes']))
	{
		$message .= "<tr><td>I believe that I will need medical clearance in order to reenroll at Rochester:</td><td>" . $data['medicalYes'] . "</td></tr>";
	}
	else if(!empty($data['medicalNo']))
	{
		$message .= "<tr><td>I have been away from the College for more than 10 months and know that I must submit a new Health History Form:</td><td>" . $data['medicalNo'] . "</td></tr>";
	}

	$message .= "<tr><td colspan='2'><div align='center'><b>8. Academic Work While Away (if applicable)</b></div><hr/></td></tr>";
	$message .= "<tr><td colspan='2'>If you took college courses while away, please provide the following information along with an official transcript of work in progress and/or completed.</td></tr>";
	$message .= "<tr><td>Name of First Institution:</td><td>" . $data['firstInstitution'] . "</td></tr>";
	$message .= "<tr><td>Dates Attended:</td><td>" . $data['firstInstitutionDate'] . "</td></tr>";
	$message .= "<tr><td>Name of Second Institution:</td><td>" . $data['secondInstitution'] . "</td></tr>";
	$message .= "<tr><td>Dates Attended:</td><td>" . $data['secondInstitutionDate'] . "</td></tr>";

	$message .= "<tr><td colspan='2'><div align='center'><b>9. Employment While Away (if applicable)</b></div><hr/></td></tr>";
	$message .= "<tr><td colspan='2'>If you were employed while away, please ask your supervisor to submit a letter of support on your behalf.</td></tr>";
	$message .= "<tr><td>Supervisor Name:</td><td>" . $data['supervisorName'] . "</td></tr>";
	$message .= "<tr><td>Company/Organization</td><td>" . $data['companyName'] . "</td></tr>";
	$message .= "<tr><td>Title:</td><td>" . $data['supervisorTitle'] . "</td></tr>";
	$message .= "<tr><td>Phone Number:</td><td>" . $data['supervisorPhoneNumber'] . "</td></tr>";
	$message .= "<tr><td>Email Address:</td><td>" . $data['supervisorEmailAddress'] . "</td></tr>";

	$message .= "<tr><td colspan='2'><div align='center'><b>10. Academic Plan</b></div><hr/></td></tr>";
	$message .= "<tr><td>Major:</td><td>" . $data['major'] . "</td></tr>";
	$message .= "<tr><td>Minor</td><td>" . $data['minor'] . "</td></tr>";
	$message .= "<tr><td>Cluster:</td><td>" . $data['cluster1'] . "</td></tr>";
	$message .= "<tr><td>Cluster:</td><td>" . $data['cluster2'] . "</td></tr>";
	$message .= "<tr><td>With whom have you discussed your Academic Plan with:</td><td>" . $data['academicPlanPerson'] . "</td></tr>";
	$majorDeclared;
	if(!empty($data['majorDeclared']))
	{
		$majorDeclared = $data['majorDeclared'];
	}
	else
	{
		$majorDeclared = "No";
	}
	$message .= "<tr><td>You have officially declared this major:</td><td>" . $majorDeclared . "</td></tr>";
	$message .= "<tr><td>Courses you plan on taking during your first semester back at the University of Rochester as well as the rationale is listed below:</td></tr></table><br/>";

	$message .= "<table style='width: 100%;'><tr><td><b>Course Rationale</b></td>";
	$message .= "<td><b>Course Number</b></td>";
	$message .= "<td><b>Course Title</b></td>";
	$message .= "<td><b>Credit Hrs</b></td></tr><tr><td>";
		
	if(!empty($data['courseRationale1']))
	{
		$message .= " " . $data['courseRationale1'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseNumber1'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseTitle1'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseCredit1'] . " ";
		$message .= "</td></tr>";
	}

	if(!empty($data['courseRationale2']))
	{
		$message .= "<tr>";
		$message .= "<td>";
		$message .= " " . $data['courseRationale2'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseNumber2'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseTitle2'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseCredit2'] . " ";
		$message .= "</td></tr>";
	}

	if(!empty($data['courseRationale3']))
	{
		$message .= "<tr>";
		$message .= "<td>";
		$message .= " " . $data['courseRationale3'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseNumber3'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseTitle3'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseCredit3'] . " ";
		$message .= "</td></tr>";
	}

	if(!empty($data['courseRationale4']))
	{
		$message .= "<tr>";
		$message .= "<td>";
		$message .= " " . $data['courseRationale4'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseNumber4'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseTitle4'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseCredit4'] . " ";
		$message .= "</td></tr>";
	}

	if(!empty($data['courseRationale5']))
	{
		$message .= "<tr>";
		$message .= "<td>";
		$message .= " " . $data['courseRationale5'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseNumber5'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseTitle5'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseCredit5'] . " ";
		$message .= "</td></tr>";
	}

	if(!empty($data['courseRationale6']))
	{
		$message .= "<tr>";
		$message .= "<td>";
		$message .= " " . $data['courseRationale6'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseNumber6'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseTitle6'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseCredit6'] . " ";
		$message .= "</td></tr>";
	}

	if(!empty($data['courseRationale7']))
	{
		$message .= "<tr>";
		$message .= "<td>";
		$message .= " " . $data['courseRationale7'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseNumber7'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseTitle7'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseCredit7'] . " ";
		$message .= "</td></tr>";
	}

	if(!empty($data['courseRationale8']))
	{
		$message .= "<tr>";
		$message .= "<td>";
		$message .= " " . $data['courseRationale8'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseNumber8'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseTitle8'] . " ";
		$message .= "</td>";
		$message .= "<td>";
		$message .= " " . $data['courseCredit8'] . " ";
		$message .= "</td></tr>";
	}

	$message .= "</table><br/>";

	$message .= "<table style='width:100%;'>";
	if(!empty($data['returnFullTime']))
	{
		$message .= "<tr><td>You plan to return Full-Time (12+ credits):</td><td>" . $data['returnFullTime'] . "</td></tr>";
	}
	else
	{
		$message .= "<tr><td>You plan to return Part-Time:</td><td>" . $data['returnPartTime'] . "</td></tr>";
	}

	$message .= "<tr><td># of semesters that will take you to complete your degree:</td><td>" . $data['numberOfSemesters'] . "</td></tr>";
	$message .= "<tr><td>When you anticipate graduation:</td><td>" . $data['graduationMonth'] . " " . $data['graduationYear'] . "</td></tr>";

	$message .= "<tr><td colspan='2'><div align='center'><b>10. Academic Plan</b></div><hr/></td></tr>";
	$message .= "<tr><td>The essay that you submitted is attached to this email. </td></tr>";
	$message .= "<tr><td>Please write a short essay (no more than 2-4 pages) that addresses the following questions:</td></tr>";
	$message .= "<tr><td>- Why did you leave the University of Rochester?</td></tr>";
	$message .= "<tr><td>- Describe the particular difficulties you encountered when you were previously enrolled.</td></tr>";
	$message .= "<tr><td>- What have you done since leaving, and how have these activities contributed to your readiness to return to full-time study?</td></tr>";
	$message .= "<tr><td>- Explain your reasons for wanting to return now. (If you withdrew from the College because of medical reasons, please explain why you feel ready to return.)</td></tr>";
	$message .= "<tr><td>- What are your plans for maintaining personal and academic supports when you return?</td></tr>";
	$message .= "<tr><td>- Please elaborate on your academic plans as well as anything else you think we should know.</td></tr>";

	
	$message .= "<tr><td colspan='2'><hr/></td></tr>";
	$message .= "<tr><td>Date/Time Submitted: " . $date . "</td></tr>";
	$message .= "<tr><td colspan='2'>Submission of this document <b>DOES NOT</b> guarantee your Reenrollment Application has been accepted.</td></tr>";
	$message .= "<tr><td colspan='2' style='width: 100%; text-align: center; background-color: #021e47; color: white; border-top: 3px solid #FFC125; border-radius: 10px;'><p>Copyright &#169; 2013&#150;2015. All rights reserved.<br /><a style='color:white;' href='http://www.rochester.edu/'>University of Rochester</a> | <a style='color:white;' href='http://www.rochester.edu/college/'>AS&#38;E</a> | <a style='color:white;' href='index.html'>Registrar</a><br/><a style='color:white;' href='http://www.rochester.edu/accessibility.html'>Accessibility</a> | <a style='color:white;' href='http://text.rochester.edu/tt/referrer' title='Access a text-only version of this page.'>Text</a> | <a style='color:white;' href='http://www.rochester.edu/college/webcomm/' title='Get help with your AS&amp;E website.'>Web Communications</a></p></td></tr>";
	$message .="</table>";	 
	$message .= "</body></html>";

	// above ends body message
	//multipart boundary 
	$messageBody = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
	"Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";

	//preparing attachment
	if(!empty($pdfName) > 0)
	{
		if(is_file($pdfName))
		{
			$messageBody .= "--{$mime_boundary}\n";
			$fp =    @fopen($pdfName,"rb");
			$info =  @fread($fp,filesize($pdfName));

			@fclose($fp);
			$info = chunk_split(base64_encode($info));
			$messageBody .= "Content-Type: application/octet-stream; name=\"".basename($pdfName)."\"\n" . 
			"Content-Description: ".basename($pdfName)."\n" .
			"Content-Disposition: attachment;\n" . " filename=\"".basename($pdfName)."\"; size=".filesize($pdfName).";\n" . 
			"Content-Transfer-Encoding: base64\n\n" . $info . "\n\n";
		}
	}

	$messageBody .= "--{$mime_boundary}--";
	$returnpath = "-f" . $from;
	
	//send email
	$mail = @mail($to, $subject, $messageBody, $headers, $returnpath);
	if($mail)
		return true;
	return false;
}
