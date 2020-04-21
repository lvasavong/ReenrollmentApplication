<?php
//*******************************************************************************************************
//	readmission.php 
//
//	Author: Linda Vasavong
//	Date Created: ????
//	Date Modified: Linda Vasavong
//*******************************************************************************************************


require_once('form_processors/m_readmission.php');

// Setup the stock Responsive header and the page container
$html .= "<div class='page row'>";


if(!isset($_SESSION['LoggedIn']))
{
  if($status == "OK")
  {
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>You have successfully saved and logged out of your Reenrollment Application!</div></div>";
  }
  if($status == "SUBMIT")
  {
    $html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>You have successfully submitted your Reenrollment Application! A copy of your submission has been sent to your email! Your account has been DEACTIVATED. To submit another application, you must create another account.</div></div>";
  }
  $html .= $loginForm->GetRiverBankInputDisplay();
}
else
{

    ob_start();

    if($_SESSION['WhichForm'] == "") {
    ?>
    <article class="columns small-12">
    <br/>
        <fieldset class="formField">
            <br>
            <form action="?" method="POST">
                <div class="row--with-borders">
                    <div class="columns small-12">
                        <p>Which form do you want to fill out?</p>
                    </div>
                </div>
                <div class="row--with-borders">
                    <div class="columns small-12 medium-4 large-4">
                        <p><input type="radio" name="whichForm" value="form1"/>&nbsp;&nbsp;Form 1</p>
                    </div>
                </div>
                <div class="row--with-borders">
                    <div class="columns small-12 medium-4 large-4">
                        <p><input type="radio" name="whichForm" value="form2"/>&nbsp;&nbsp;Form 2</p>
                    </div>
                </div>
                <div class="row--with-borders">
                    <div class="columns small-12 medium-4 large-4">
                        <p><input type="radio" name="whichForm" value="form3"/>&nbsp;&nbsp;Re-Enrollment Application</p>
                    </div>
                </div>
                <div class="row--with-borders">
                    <div class="text-center columns small-12">
                        <input class="small button secondary button-pop" name="SaveWhichForm" type="submit" value="Continue"/>
                    </div>
                </div>
                <br/>
            </form>
        </fieldset>
        <br/>
    </article>


    <?php
    }
    if($_SESSION['WhichForm'] == "form1") {
        ?>

        <article class="columns small-12">
            <br/>
                <fieldset class="formField">
                    <br>
                    <form action="?" method="POST">
                        <div class="row--with-borders">
                            <div class="columns small-12">
                                <p>Welcome to Form 1</p>
                            </div>
                        </div>
                        <br/>
                    </form>
                </fieldset>
                <br/>
        </article>

        <?php
    } 
    else if($_SESSION['WhichForm'] == "form2") {

        ?>

        <article class="columns small-12">
            <br/>
                <fieldset class="formField">
                    <br>
                    <form action="?" method="POST">
                        <div class="row--with-borders">
                            <div class="columns small-12">
                                <p>Welcome to Form 2</p>
                            </div>
                        </div>
                        <br/>
                    </form>
                </fieldset>
                <br/>
        </article>

        <?php
    } 
    else if($_SESSION['WhichForm'] == "form3") {
	
    if($status == "DB_ERR")
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>There was a problem submitting this form to the Database, the database could currently be offline. Contact the College Center for Advising Services (585) 275-2354 for further assistance.</div></div>";
    
    if($status == "OK")
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>You have successfully saved " . $_SESSION['State'] . " of your application.</div></div>";
        
    if($essayError)
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_fail medium-centered section--thick'>" . $essayMessage . "</div></div>";
    
    if($essaySuccess)
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>" . $essayMessage . "</div></div>";
    
    if(!$removeDuplicateEssay)
        $html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>There was a server error in updating your essay.</div></div>";
    
    if(!$validTest && !empty($errors))
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_fail medium-centered section--thick'>One or more required fields indicated below have been left blank!</div></div>"; 
	
    if(!$validTest && !empty($error_messages))
    {
        echo $common->GetErrorDisplay($error_messages); 
    }
    $disabled = "";
    if($_SESSION['State'] == "Review")
    {
        if(!empty($errors) && !empty($error_messages))
        {
            $disabled = "disabled";
            echo GetErrorDisplay($error_messages); 
        }
    }
    //echo var_dump($dump);
  ?>
<article class="columns small-12">
<br/>
<fieldset class="formField">
<br>
<form action="?" method="POST">
<div class="row--with-borders">
    <div class="columns small-12">
        <p>
            <input class="button no-margin" name="Home" style="display:inline-block;width:90px;" type="submit" value="Home"/>
            <input class="button no-margin" name="Part1" style="display:inline-block;width:90px;" type="submit" value="Part 1"/>
            <input class="button no-margin" name="Part2" style="display:inline-block;width:90px;" type="submit" value="Part 2"/>
            <input class="button no-margin" name="Part3" style="display:inline-block;width:90px;" type="submit" value="Part 3"/>
            <input class="button no-margin" name="Part4" style="display:inline-block;width:90px;" type="submit" value="Part 4"/>
            <input class="button no-margin" name="Review" style="display:inline-block;width:90px;" type="submit" value="Review"/>
        </p>
    </div>
</div>
<br/>
<div class="row--with-borders">
    <div class="columns small-12">
        <p>Please use the buttons above to navigate through parts of the application.</p>
    </div>
</div>
<div class="row--with-borders">
    <div class="columns small-12">
        <input class="button no-margin" style="background-color:gold;color:black;width:100px;display:inline-block;" onMouseOver="this.style.backgroundColor='#cca700'"  onMouseOut="this.style.backgroundColor='gold'" name="Logout" type="submit" value="Logout"/>
    </div>
</div>
</form>
<?php
if($_SESSION['State'] == "Welcome")
{
?>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h2>University of Rochester</h2>
            <h2>Application for Reenrollment</h2>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>Thank you for your interest in resuming your studies at the University of Rochester. We welcome your application. Please review our instructions with care (<a href="https://www.rochester.edu/college/ccas/handbook/readmission.html" target="_new">http://www.rochester.edu/college/ccas/handbook/readmission.html</a>) and complete this application fully, submitting all required supporting materials such as letters and transcripts, and providing thoughtful responses to the questions at the end. Make sure to send everything by May 1 (for fall return) or November 1 (for spring return) as an email attachment to asechangeofstatus@rochester.edu with the subject line “Reenrollment Application: your name”. Or, you may mail your reenrollment application to the College Center for Advising Services, 312 Lattimore Hall, RC Box 270402, University of Rochester, Rochester, NY 14627, ATTN: Anika Simone Johnson.</p>
        </div>
    </div>
<?php 
}
else if($_SESSION['State'] == "Part 1")
{
?>
    <form action="?" method="POST">
    <br/>
    <br/>
    <div class="row--with-borders">
        <div class="columns small-12">
        <h3>1. Personal Information</h3><br>
        </div>
    </div>
    <br/>
    <br/>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><b>NOTE:</b> Fields marked with <span class="required">*</span> are <b>required</b> fields</p><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <table>
                <tbody>
                    <tr>
                        <td <?php if(in_array('semesterEnroll',$errors)) echo "class='error'";?>><span class="required">*</span><b>In which semester are you enrolling?</b></td>
                        <td><div class="entrySelectWide">
                            <select id="semesterEnroll" name="semesterEnroll">
                                <?php
                                    // get data after signing in or havent completed will select empty
                                    echo GetTermOptionsFromArrayKeys();
                                ?>
                            </select>
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="studentFirstName" <?php if(in_array('studentFirstName',$errors)) echo "class='error'";?>><span class="required">*</span>First Name</label>
            <input type="text" id="studentFirstName" name="studentFirstName" maxlength="50" value="<?php echo $formData['studentFirstName'];?>"/>
        </div>
        <div class="columns small-12 medium-1 large-1">
            <label for="studentMiddleInitial">M.I.</label>
            <input type="text" id="studentMiddleInitial" maxlength='1' name="studentMiddleInitial" value="<?php echo $formData['studentMiddleInitial'];?>"/>
        </div>
        <div class="columns small-12 medium-4 large-4">
            <label for="studentLastName" <?php if(in_array('studentLastName',$errors)) echo "class='error'";?>><span class="required">*</span>Last Name</label>
            <input type="text" id="studentLastName" name="studentLastName" maxlength="50" value="<?php echo $formData['studentLastName'];?>"/>
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="studentPreferredName" <?php if(in_array('studentPreferredName',$errors)) echo "class='error'";?>><span class="required">*</span>Preferred Name</label>
            <input type="text" id="studentPreferredName" name="studentPreferredName" maxlength='80' value="<?php echo $formData['studentPreferredName'];?>"/>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="studentID" <?php if(in_array('studentID',$errors)) echo "class='error'";?>><span class="required">*</span>Student ID #</label>
            <input type="text" id="studentID" name="studentID" maxlength='8' size='8' value="<?php echo $formData['studentID'];?>"/>
        </div>
        <div class="columns small-12 medium-8 large-8">
            <label for="studentFormerName">Former Name(s), if applicable</label>
            <input type="text" id="studentFormerName" name="studentFormerName" maxlength="80" value="<?php echo $formData['studentFormerName'];?>"/>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="advisorName" <?php if(in_array('advisorName',$errors)) echo "class='error'";?>><span class="required">*</span>Name of Advisor(s)</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type='text' name='advisorName' maxlength="80" value='<?php echo $formData['advisorName']; ?>' size='70'/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="studentAddressLine1" <?php if(in_array('studentAddressLine1',$errors)) echo "class='error'";?>><span class="required">*</span>Address Line 1</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="studentAddressLine1" name="studentAddressLine1" maxlength="75" value="<?php echo $formData['studentAddressLine1'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="studentAddressLine2">Address Line 2</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="studentAddressLine2" name="studentAddressLine2" maxlength="75" value="<?php echo $formData['studentAddressLine2'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="studentCity" <?php if(in_array('studentCity',$errors)) echo "class='error'";?>><span class="required">*</span>City</label>
            <input type="text" id="studentCity" name="studentCity" maxlength="75" value="<?php echo $formData['studentCity'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="studentCountry" <?php if(in_array('studentCountry',$errors)) echo "class='error'";?>><span class="required">*</span>Country</label>
            <input type="text" id="studentCountry" name="studentCountry" maxlength="75" value="<?php echo $formData['studentCountry'];?>">
        </div>
        <div class="columns small-12 medium-2 large-2">
            <label for="studentState" <?php if(in_array('studentState',$errors)) echo "class='error'";?>>State</label>
            <input type="text" id="studentState" name="studentState" maxlength="75" value="<?php echo $formData['studentState'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="studentZipCode" <?php if(in_array('studentZipCode',$errors)) echo "class='error'";?>><span class="required">*</span>Zip Code</label>
            <input type="text" id="studentZipCode" name="studentZipCode" maxlength="25" value="<?php echo $formData['studentZipCode'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-3 large-3">
            <label for="studentDateOfBirth" <?php if(in_array('studentBirthMonth',$errors) || in_array('studentBirthDay',$errors) || in_array('studentBirthYear',$errors)) echo "class='error'";?>><span class="required">*</span>Date of Birth</label>
             <select name="studentBirthMonth">
                <?php
                echo GetBirthMonthOptionsFromArray();
                ?>
            </select>
        </div>
        <div class="columns small-12 medium-2 large-1">
        <label for="studentDateOfBirth"> &nbsp;</label>
            <select name="studentBirthDay">
                <?php 
                echo GetBirthDayOptionsFromArray();
                ?>
             </select>
        </div>
        <div class="columns small-12 medium-2 large-2">
        <label for="studentDateOfBirth"> &nbsp;</label>
            <select name="studentBirthYear">
                <?php
                echo GetBirthYearOptionsFromArray();
                ?>
            </select>
        </div>
        <div class="columns small-12 medium-5 large-6">
            <label for="studentHomePhoneNumber" <?php if(in_array('studentHomePhoneNumber',$errors)) echo "class='error'";?>><span class="required">*</span>Home Phone Number</label>
            <input type="text" id="studentHomePhoneNumber" name="studentHomePhoneNumber" maxlength="20" value="<?php echo $formData['studentHomePhoneNumber']; ?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="studentCellPhoneNumber" <?php if(in_array('studentCellPhoneNumber',$errors)) echo "class='error'";?>><span class="required">*</span>Cell Phone Number</label>
            <input type="text" id="studentCellPhoneNumber" name="studentCellPhoneNumber" maxlength="20" value="<?php echo $formData['studentCellPhoneNumber'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="studentEmailAddress" <?php if(in_array('studentEmailAddress',$errors)) echo "class='error'";?>><span class="required">*</span>Email Address</label>
            <input type="text" id="studentEmailAddress" name="studentEmailAddress" maxlength="40" value="<?php echo $formData['studentEmailAddress'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>If you are an international student, please check this box <input type="checkbox" name="studentIsInternational" size='3' value='Yes' <?php if($formData['studentIsInternational'] == 'Yes') echo ' checked';?>/> one of our International Student Advisors will contact you upon submitting this application, to discuss the immigration matters.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>If you are 21 and over, please check this box <input type="checkbox" name="studentIs21AndOver" size='3' value='Yes' <?php if($formData['studentIs21AndOver'] == 'Yes') echo ' checked';?>/> you are not required to fill out the Parent(s)/Guardian(s) Information Section.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>2. Parent(s)/Guardian(s) Information</h3><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h5>Parent/Guardian 1 Information</h5><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="parent1FirstName" <?php if(in_array('parent1FirstName',$errors)) echo "class='error'";?>>Parent 1 First Name</label>
            <input type="text" id="parent1FirstName" name="parent1FirstName" maxlength="50" value="<?php echo $formData['parent1FirstName'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="parent1LastName" <?php if(in_array('parent1LastName',$errors)) echo "class='error'";?>>Parent 1 Last Name</label>
            <input type="text" id="parent1LastName" name="parent1LastName" maxlength="50" value="<?php echo $formData['parent1LastName'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="parent1AddressLine1" <?php if(in_array('parent1AddressLine1',$errors)) echo "class='error'";?>>Address Line 1</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="parent1AddressLine1" maxlength="75" name="parent1AddressLine1" value="<?php echo $formData['parent1AddressLine1'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="parent1AddressLine2">Address Line 2</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="parent1AddressLine2" maxlength="75" name="parent1AddressLine2" value="<?php echo $formData['parent1AddressLine2'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="parent1City" <?php if(in_array('parent1City',$errors)) echo "class='error'";?>>City</label>
            <input type="text" id="parent1City" name="parent1City" maxlength="75" value="<?php echo $formData['parent1City'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="parent1Country" <?php if(in_array('parent1Country',$errors)) echo "class='error'";?>>Country</label>
            <input type="text" id="parent1Country" name="parent1Country" maxlength="75" value="<?php echo $formData['parent1Country'];?>">
        </div>
        <div class="columns small-12 medium-2 large-2">
            <label for="parent1State" <?php if(in_array('parent1State',$errors)) echo "class='error'";?>>State</label>
            <input type="text" id="parent1State" name="parent1State" maxlength="75" value="<?php echo $formData['parent1State'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="parent1ZipCode" <?php if(in_array('parent1ZipCode',$errors)) echo "class='error'";?>>Zip Code</label>
            <input type="text" id="parent1ZipCode" name="parent1ZipCode" maxlength="25" value="<?php echo $formData['parent1ZipCode'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="parent1PhoneNumber" <?php if(in_array('parent1PhoneNumber',$errors)) echo "class='error'";?>>Phone Number</label>
            <input type="text" id="parent1PhoneNumber" name="parent1PhoneNumber" maxlength="20" value="<?php echo $formData['parent1PhoneNumber'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="parent1EmailAddress" <?php if(in_array('parent1EmailAddress',$errors)) echo "class='error'";?>>Email Address</label>
            <input type="text" id="parent1EmailAddress" name="parent1EmailAddress" maxlength="40" value="<?php echo $formData['parent1EmailAddress'];?>">
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h5>Parent/Guardian 2 Information (if applicable)</h5><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="parent2FirstName" <?php if(in_array('parent2FirstName',$errors)) echo "class='error'";?>>Parent 2 First Name</label>
            <input type="text" id="parent2FirstName" name="parent2FirstName" maxlength="50" value="<?php echo $formData['parent2FirstName'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="parent2LastName" <?php if(in_array('parent2LastName',$errors)) echo "class='error'";?>>Parent 2 Last Name</label>
            <input type="text" id="parent2LastName" name="parent2LastName" maxlength="50" value="<?php echo $formData['parent2LastName'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="parent2AddressLine1" <?php if(in_array('parent2AddressLine1',$errors)) echo "class='error'";?>>Address Line 1</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="parent2AddressLine1" maxlength="75" name="parent2AddressLine1" value="<?php echo $formData['parent2AddressLine1'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="parent2AddressLine2">Address Line 2</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="parent2AddressLine2" maxlength="75" name="parent2AddressLine2" value="<?php echo $formData['parent2AddressLine2'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="parent2City" <?php if(in_array('parent2City',$errors)) echo "class='error'";?>>City</label>
            <input type="text" id="parent2City" name="parent2City" maxlength="75" value="<?php echo $formData['parent2City'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="parent2Country" <?php if(in_array('parent2Country',$errors)) echo "class='error'";?>>Country</label>
            <input type="text" id="parent2Country" name="parent2Country" maxlength="75" value="<?php echo $formData['parent2Country'];?>">
        </div>
        <div class="columns small-12 medium-2 large-2">
            <label for="parent2State" <?php if(in_array('parent2State',$errors)) echo "class='error'";?>>State</label>
            <input type="text" id="parent2State" name="parent2State" maxlength="75" value="<?php echo $formData['parent2State'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="parent2ZipCode" <?php if(in_array('parent2ZipCode',$errors)) echo "class='error'";?>>Zip Code</label>
            <input type="text" id="parent2ZipCode" name="parent2ZipCode" maxlength="25" value="<?php echo $formData['parent2ZipCode'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="parent2PhoneNumber" <?php if(in_array('parent2PhoneNumber',$errors)) echo "class='error'";?>>Phone Number</label>
            <input type="text" id="parent2PhoneNumber" name="parent2PhoneNumber" maxlength="20" value="<?php echo $formData['parent2PhoneNumber'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="parent2EmailAddress" <?php if(in_array('parent2EmailAddress',$errors)) echo "class='error'";?>>Email Address</label>
            <input type="text" id="parent2EmailAddress" name="parent2EmailAddress" maxlength="40" value="<?php echo $formData['parent2EmailAddress'];?>">
        </div>
    </div>
    <br><br>
    <div class="row--with-borders">
        <div class="text-center columns small-12">
            <input class="small button secondary button-pop" name="Save" type="submit" value="Save"/>
        </div>
    </div>
    </form>
<?php 
} 
else if($_SESSION['State'] == 'Part 2')
{
?>
    <form action="?" method="POST">
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>3. Attendance and Application History</h3><br>
        </div>
    </div>
    <br/>
    <br/>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><b>NOTE:</b> Fields marked with <span class="required">*</span> are <b>required</b> fields</p><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-5 large-5">
            <p <?php if(in_array('monthEntered',$errors) || in_array('yearEntered',$errors)) echo "class='error'";?>><span class="required">*</span>Date you entered the University of Rochester (Month/Year)</p>
            <p <?php if(in_array('monthLeft',$errors) || in_array('yearLeft',$errors)) echo "class='error'";?>><span class="required">*</span>Date you left the University of Rochester (Month/Year)</p>
        </div>
        <div class="columns small-12 medium-4 large-4">
            <select id="monthEntered" name="monthEntered">
                <?php 
                echo GetMonthEnteredOptionsFromArray();
                ?>
            </select>
            <select id="monthLeft" name="monthLeft">
                <?php 
                echo GetMonthLeftOptionsFromArray();
                ?>
            </select>
        </div>
        <div class="columns small-12 medium-3 large-3">
            <select id="yearEntered" name="yearEntered">
                <?php
                echo GetYearEnteredOptionsFromArray();
                ?>
            </select>
            <select id="yearLeft" name="yearLeft">
                <?php 
                echo GetYearLeftOptionsFromArray();
                ?>
            </select>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-9 large-9">
            <p <?php if(in_array('appliedBeforeYes',$errors)) echo "class='error'";?>><span class="required">*</span>Have you ever applied to return to University of Rochester through the Re-Enrollment Application before?</p>
        </div>
        <div class="columns small-12 medium-3 large-3">
            <p><input type="checkbox" name="appliedBeforeYes" id="appliedBeforeYes" onclick="checkAppliedBeforeYes()" size='3' value='Yes' <?php if($formData['appliedBeforeYes'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;<b>Yes</b>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="appliedBeforeNo" id="appliedBeforeNo" onclick="checkAppliedBeforeNo()" size='3' value='Yes' <?php if($formData['appliedBeforeNo'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;<b>No</b>
            </p>           
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-5 large-5">
            <p <?php if(in_array('monthAppliedBefore',$errors) || in_array('yearAppliedBefore',$errors)) echo "class='error'";?>>If yes, when&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        </div>
        <div class="columns small-12 medium-4 large-4">
            <select id="monthAppliedBefore" name="monthAppliedBefore">
                <?php 
                echo GetMonthAppliedBeforeOptionsFromArray();
                ?>
            </select>
        </div>
        <div class="columns small-12 medium-3 large-3">
            <select id="yearAppliedBefore" name="yearAppliedBefore">
                <?php 
                echo GetYearAppliedBeforeOptionsFromArray();
                ?>
            </select>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>4. Arrival Information</h3><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('onCampusRequired',$errors)) echo "class='error'";?>><span class="required">*</span>Reenrolling students are required to be on campus prior to the start of classes to meet with their advisors. Students who are unable to do so will not be readmitted.</p>
            <p><input type="checkbox" name="onCampusRequired" size='3' value='Yes' <?php if($formData['onCampusRequired'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;I <b>acknowledge</b> that If I am readmitted, I will arrive at least 48 hours prior to the start of classes.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>5. Housing Preference</h3><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('liveOnCampusYes',$errors) || in_array('liveOnCampusNo',$errors)) echo "class='error'";?>><span class="required">*</span>Please make us aware of your interest with housing for when you return. Unfortunately, housing is not guaranteed for reenrolling students. Assignments are made on a rolling basis for both fall and spring semesters. More information will be provided once your application is received.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p><input type="checkbox" name="liveOnCampusYes" id="liveOnCampusYes" size='3' onclick="checkLiveOnCampusYes()" value='Yes' <?php if($formData['liveOnCampusYes'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;I <b>desire</b> to live on campus.&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="liveOnCampusNo" id="liveOnCampusNo" size='3' onclick="checkLiveOnCampusNo()" value='Yes' <?php if($formData['liveOnCampusNo'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;I <b>intend</b> to live off campus.
            </p>           
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>6. Financial Information</h3><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>Financial aid deadlines are the same as the re-enrollment deadlines: November 1 and May 1. These are also the deadlines for clearing up any balances that remain on your account from your previous enrollment. You can review information about deadlines and financial aid eligibility on the re-enrollment website <a href="https://www.rochester.edu/college/ccas/handbook/financial-aid.html" target="_new">https://www.rochester.edu/college/ccas/handbook/financial-aid.html</a>. You may contact the University of Rochester’s Bursar Office at <a href="https://www.rochester.edu/adminfinance/bursar/" target="_new">https://www.rochester.edu/adminfinance/bursar/</a> or (585) 275-3931 with any questions you may have regarding your account.</p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <h3>7. Health/Mental Health Care Provider Information (if applicable)</h3>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p <?php if(in_array('medicalYes',$errors)) echo "class='error'";?>>If you are on a medical leave, or if medical issues were a factor in your leaving the University of Rochester, your application will not be complete without approval from UHS or UCC. You should contact UHS (585) 275-2679 or email mlivingston@uhs.rochester.edu) or UCC (585) 275-3115 well before the reenrollment application deadline to discuss your plans to return. The dean of the College, following the recommendation made by UHS/UCC, will make a final decision regarding reenrollment or the deferment of your application to the next semester’s reenrollment cycle.</p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small--12">
            <p><input type="checkbox" name="medicalYes" id="medicalYes" onclick="checkMedicalYes()" size='3' value='Yes' <?php if($formData['medicalYes'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;I believe that I will need medical clearance in order to reenroll at Rochester.</p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small--12">
            <p><input type="checkbox" name="medicalNo" id="medicalNo" onclick="checkMedicalNo()" size='3' value='Yes' <?php if($formData['medicalNo'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;I have been away from the College for more than 10 months and know that I must submit a new Health History Form.</p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <h3>8. Academic Work While Away (if applicable)</h3>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>If you took college courses while away, please provide the following information along with an official transcript of work in progress and/or completed.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-7 large-7">
            <label for="firstInstitution" <?php if(in_array('firstInstitution',$errors)) echo "class='error'";?>>Name of First Institution</label>
            <input type="text" id="firstInstitution" name="firstInstitution" maxlength="80" value="<?php echo $formData['firstInstitution']; ?>">
        </div>
        <div class="columns small-12 medium-5 large-5">
            <label for="firstInstitutionDate" <?php if(in_array('firstInstitutionDate',$errors)) echo "class='error'";?>>Dates Attended</label>
            <input type="text" id="firstInstitutionDate" name="firstInstitutionDate" maxlength="40" value="<?php echo $formData['firstInstitutionDate']; ?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-7 large-7">
            <label for="secondInstitution" <?php if(in_array('secondInstitution',$errors)) echo "class='error'";?>>Name of Second Institution</label>
            <input type="text" id="secondInstitution" name="secondInstitution" maxlength="80" value="<?php echo $formData['secondInstitution']; ?>">
        </div>
        <div class="columns small-12 medium-5 large-5">
            <label for="secondInstitutionDate" <?php if(in_array('secondInstitutionDate',$errors)) echo "class='error'";?>>Dates Attended</label>
            <input type="text" id="secondInstitutionDate" name="secondInstitutionDate" maxlength="40" value="<?php echo $formData['secondInstitutionDate']; ?>">
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <h3>9. Employment While Away (if applicable)</h3>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>If you were employed while away, please ask your supervisor to submit a letter of support on your behalf.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="supervisorName" <?php if(in_array('supervisorName',$errors)) echo "class='error'";?>>Name of Supervisor</label>
            <input type="text" id="supervisorName" name="supervisorName" maxlength="90" value="<?php echo $formData['supervisorName'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="companyName" <?php if(in_array('companyName',$errors)) echo "class='error'";?>>Company/Organization</label>
            <input type="text" id="companyName" name="companyName" maxlength="70" value="<?php echo $formData['companyName'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="supervisorTitle" <?php if(in_array('supervisorTitle',$errors)) echo "class='error'";?>>Title</label>
            <input type="text" id="supervisorTitle" name="supervisorTitle" maxlength="70" value="<?php echo $formData['supervisorTitle'];?>">
        </div>
        <div class="columns small-12 medium-4 large-4">
            <label for="supervisorPhoneNumber" <?php if(in_array('supervisorPhoneNumber',$errors)) echo "class='error'";?>>Phone Number</label>
            <input type="text" id="supervisorPhoneNumber" maxlength="20" name="supervisorPhoneNumber" value="<?php echo $formData['supervisorPhoneNumber'];?>">
        </div>
        <div class="columns small-12 medium-4 large-4">
            <label for="supervisorEmailAddress" <?php if(in_array('supervisorEmailAddress',$errors)) echo "class='error'";?>>Email Address</label>
            <input type="text" id="supervisorEmailAddress" maxlength="40" name="supervisorEmailAddress" value="<?php echo $formData['supervisorEmailAddress'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="text-center columns small-12">
            <input class="small button secondary button-pop" name="Save" type="submit" value="Save"/>
        </div>
    </div>
    </form>
<?php 
} 
else if($_SESSION['State'] == "Part 3")
{
?>
    <form action="?" method="POST">
    <div class="row--with--borders">
        <div class="columns small-12">
            <h3>10. Academic Plan</h3>
        </div>
    </div>
    <br/>
    <br/>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><b>NOTE:</b> Fields marked with <span class="required">*</span> are <b>required</b> fields</p><br>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>What is your intended plan to complete the Rochester Curriculum?</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="major" <?php if(in_array('major',$errors)) echo "class='error'";?>><span class="required">*</span>Major</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="major" maxlength="70" name="major" value="<?php echo $formData['major'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="minor">Minor</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="minor" name="minor" maxlength="70" value="<?php echo $formData['minor'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="cluster1" <?php if(in_array('cluster1',$errors)) echo "class='error'";?>><span class="required">*</span>Cluster</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="cluster1" maxlength="70" name="cluster1" value="<?php echo $formData['cluster1'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="cluster2">Cluster</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="cluster2" maxlength="70" name="cluster2" value="<?php echo $formData['cluster2'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="academicPlanPerson" <?php if(in_array('academicPlanPerson',$errors)) echo "class='error'";?>><span class="required">*</span>With whom have you discussed your Academic Plan with (i.e. Major Department, OMSA Advisor, College Center for Advising Services Advisor, etc.</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="academicPlanPerson" maxlength="70" name="academicPlanPerson" value="<?php echo $formData['academicPlanPerson'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small--12">
            <p><input type="checkbox" name="majorDeclared" size='3' value='Yes' <?php if($formData['majorDeclared'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;I have officially declared this major.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
			<p <?php if(in_array('firstSemester',$errors) || in_array('courseRow',$errors)) echo "class='error'";?>>Please list the courses you plan on taking during your first semester back at the University of Rochester as well as the rationale (i.e.: major, cluster, elective, etc.) for each one. Use the ' + ' and ' - ' buttons to add/remove course rows.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <table align="center">
                <tbody><tr><th>Rationale</th><th>Course Number</th><th>Course Title</th><th>Credit Hrs</th>
                <tr><td>e.g., Major<br/><button type='button' class='button no-margin' id="addCourseRow" style="font-size:27px;font-weight:bold;height:33px;width:33px;margin:0 auto;padding:0;display:table-cell;vertical-align:middle;">&#43;</button>&nbsp;&nbsp;<button type='button' class='button no-margin' id="removeCourseRow" style="font-size:27px;font-weight:bold;height:33px;width:33px;margin:0 auto;padding:0;display:table-cell;vertical-align:middle;">&minus;</button></td><td>CSC 172<br/>&nbsp;</td><td>Data Structures and Algorithms<br/>&nbsp;</td><td>4.0<br/>&nbsp;</td></tr>
                    <tr><td align='center'><select id="courseRationale1" name="courseRationale1"><option value="" <?php echo ($formData['courseRationale1'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale1'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale1'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale1'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective" <?php echo ($formData['courseRationale1'] == "Elective" ? " selected" : "");?>>Elective</option></select></td><td><input type="text" id='courseNumber1' name="courseNumber1" size='15' maxlength='10' value="<?php echo $formData['courseNumber1'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle1" id='courseTitle1' value="<?php echo $formData['courseTitle1'];?>"/></td><td><select id="courseCredit1" name="courseCredit1"><option value="" <?php echo ($formData['courseCredit1'] == "" ? " selected" : "");?>>--Select--</option><option value="0.0" <?php echo ($formData['courseCredit1'] == "0.0" ? " selected" : "");?>>0.0</option><option value="0.5" <?php echo ($formData['courseCredit1'] == "0.5" ? " selected" : "");?>>0.5</option><option value="1.0" <?php echo ($formData['courseCredit1'] == "1.0" ? " selected" : "");?>>1.0</option><option value="2.0" <?php echo ($formData['courseCredit1'] == "2.0" ? " selected" : "");?>>2.0</option><option value="3.0" <?php echo ($formData['courseCredit1'] == "3.0" ? " selected" : "");?>>3.0</option><option value="4.0" <?php echo ($formData['courseCredit1'] == "4.0" ? " selected" : "");?>>4.0</option><option value="5.0" <?php echo ($formData['courseCredit1'] == "5.0" ? " selected" : "");?>>5.0</option><option value="6.0" <?php echo ($formData['courseCredit1'] == "6.0" ? " selected" : "");?>>6.0</option><option value="7.0" <?php echo ($formData['courseCredit1'] == "7.0" ? " selected" : "");?>>7.0</option><option value="8.0" <?php echo ($formData['courseCredit1'] == "8.0" ? " selected" : "");?>>8.0</option></select></td></tr>
                    <tr id="courseRow2"><td align='center'><select id="courseRationale2" name="courseRationale2"><option value="" <?php echo ($formData['courseRationale2'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale2'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale2'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale2'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective" <?php echo ($formData['courseRationale2'] == "Elective" ? " selected" : "");?>>Elective</option></select></td><td><input type="text" id='courseNumber2' name="courseNumber2" size='15' maxlength='10' value="<?php echo $formData['courseNumber2'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle2" id='courseTitle2' value="<?php echo $formData['courseTitle2'];?>"/></td><td><select id="courseCredit2" name="courseCredit2"><option value="" <?php echo ($formData['courseCredit2'] == "" ? " selected" : "");?>>--Select--</option><option value="0.0" <?php echo ($formData['courseCredit2'] == "0.0" ? " selected" : "");?>>0.0</option><option value="0.5" <?php echo ($formData['courseCredit2'] == "0.5" ? " selected" : "");?>>0.5</option><option value="1.0" <?php echo ($formData['courseCredit2'] == "1.0" ? " selected" : "");?>>1.0</option><option value="2.0" <?php echo ($formData['courseCredit2'] == "2.0" ? " selected" : "");?>>2.0</option><option value="3.0" <?php echo ($formData['courseCredit2'] == "3.0" ? " selected" : "");?>>3.0</option><option value="4.0" <?php echo ($formData['courseCredit2'] == "4.0" ? " selected" : "");?>>4.0</option><option value="5.0" <?php echo ($formData['courseCredit2'] == "5.0" ? " selected" : "");?>>5.0</option><option value="6.0" <?php echo ($formData['courseCredit2'] == "6.0" ? " selected" : "");?>>6.0</option><option value="7.0" <?php echo ($formData['courseCredit2'] == "7.0" ? " selected" : "");?>>7.0</option><option value="8.0" <?php echo ($formData['courseCredit2'] == "8.0" ? " selected" : "");?>>8.0</option></select></td></tr>
                    <tr id="courseRow3"><td align='center'><select id="courseRationale3" name="courseRationale3"><option value="" <?php echo ($formData['courseRationale3'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale3'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale3'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale3'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective" <?php echo ($formData['courseRationale3'] == "Elective" ? " selected" : "");?>>Elective</option></select></td><td><input type="text" id='courseNumber3' name="courseNumber3" size='15' maxlength='10' value="<?php echo $formData['courseNumber3'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle3" id='courseTitle3' value="<?php echo $formData['courseTitle3'];?>"/></td><td><select id="courseCredit3" name="courseCredit3"><option value="" <?php echo ($formData['courseCredit3'] == "" ? " selected" : "");?>>--Select--</option><option value="0.0" <?php echo ($formData['courseCredit3'] == "0.0" ? " selected" : "");?>>0.0</option><option value="0.5" <?php echo ($formData['courseCredit3'] == "0.5" ? " selected" : "");?>>0.5</option><option value="1.0" <?php echo ($formData['courseCredit3'] == "1.0" ? " selected" : "");?>>1.0</option><option value="2.0" <?php echo ($formData['courseCredit3'] == "2.0" ? " selected" : "");?>>2.0</option><option value="3.0" <?php echo ($formData['courseCredit3'] == "3.0" ? " selected" : "");?>>3.0</option><option value="4.0" <?php echo ($formData['courseCredit3'] == "4.0" ? " selected" : "");?>>4.0</option><option value="5.0" <?php echo ($formData['courseCredit3'] == "5.0" ? " selected" : "");?>>5.0</option><option value="6.0" <?php echo ($formData['courseCredit3'] == "6.0" ? " selected" : "");?>>6.0</option><option value="7.0" <?php echo ($formData['courseCredit3'] == "7.0" ? " selected" : "");?>>7.0</option><option value="8.0" <?php echo ($formData['courseCredit3'] == "8.0" ? " selected" : "");?>>8.0</option></select></td></tr>
                    <tr id="courseRow4"><td align='center'><select id="courseRationale4" name="courseRationale4"><option value="" <?php echo ($formData['courseRationale4'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale4'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale4'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale4'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective" <?php echo ($formData['courseRationale4'] == "Elective" ? " selected" : "");?>>Elective</option></select></td><td><input type="text" id='courseNumber4' name="courseNumber4" size='15' maxlength='10' value="<?php echo $formData['courseNumber4'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle4" id='courseTitle4' value="<?php echo $formData['courseTitle4'];?>"/></td><td><select id="courseCredit4" name="courseCredit4"><option value="" <?php echo ($formData['courseCredit4'] == "" ? " selected" : "");?>>--Select--</option><option value="0.0" <?php echo ($formData['courseCredit4'] == "0.0" ? " selected" : "");?>>0.0</option><option value="0.5" <?php echo ($formData['courseCredit4'] == "0.5" ? " selected" : "");?>>0.5</option><option value="1.0" <?php echo ($formData['courseCredit4'] == "1.0" ? " selected" : "");?>>1.0</option><option value="2.0" <?php echo ($formData['courseCredit4'] == "2.0" ? " selected" : "");?>>2.0</option><option value="3.0" <?php echo ($formData['courseCredit4'] == "3.0" ? " selected" : "");?>>3.0</option><option value="4.0" <?php echo ($formData['courseCredit4'] == "4.0" ? " selected" : "");?>>4.0</option><option value="5.0" <?php echo ($formData['courseCredit4'] == "5.0" ? " selected" : "");?>>5.0</option><option value="6.0" <?php echo ($formData['courseCredit4'] == "6.0" ? " selected" : "");?>>6.0</option><option value="7.0" <?php echo ($formData['courseCredit4'] == "7.0" ? " selected" : "");?>>7.0</option><option value="8.0" <?php echo ($formData['courseCredit4'] == "8.0" ? " selected" : "");?>>8.0</option></select></td></tr>
                    <tr id="courseRow5"><td align='center'><select id="courseRationale5" name="courseRationale5"><option value="" <?php echo ($formData['courseRationale5'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale5'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale5'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale5'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective" <?php echo ($formData['courseRationale5'] == "Elective" ? " selected" : "");?>>Elective</option></select></td><td><input type="text" id='courseNumber5' name="courseNumber5" size='15' maxlength='10' value="<?php echo $formData['courseNumber5'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle5" id='courseTitle5' value="<?php echo $formData['courseTitle5'];?>"/></td><td><select id="courseCredit5" name="courseCredit5"><option value="" <?php echo ($formData['courseCredit5'] == "" ? " selected" : "");?>>--Select--</option><option value="0.0" <?php echo ($formData['courseCredit5'] == "0.0" ? " selected" : "");?>>0.0</option><option value="0.5" <?php echo ($formData['courseCredit5'] == "0.5" ? " selected" : "");?>>0.5</option><option value="1.0" <?php echo ($formData['courseCredit5'] == "1.0" ? " selected" : "");?>>1.0</option><option value="2.0" <?php echo ($formData['courseCredit5'] == "2.0" ? " selected" : "");?>>2.0</option><option value="3.0" <?php echo ($formData['courseCredit5'] == "3.0" ? " selected" : "");?>>3.0</option><option value="4.0" <?php echo ($formData['courseCredit5'] == "4.0" ? " selected" : "");?>>4.0</option><option value="5.0" <?php echo ($formData['courseCredit5'] == "5.0" ? " selected" : "");?>>5.0</option><option value="6.0" <?php echo ($formData['courseCredit5'] == "6.0" ? " selected" : "");?>>6.0</option><option value="7.0" <?php echo ($formData['courseCredit5'] == "7.0" ? " selected" : "");?>>7.0</option><option value="8.0" <?php echo ($formData['courseCredit5'] == "8.0" ? " selected" : "");?>>8.0</option></select></td></tr>
                    <tr id="courseRow6"><td align='center'><select id="courseRationale6" name="courseRationale6"><option value="" <?php echo ($formData['courseRationale6'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale6'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale6'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale6'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective" <?php echo ($formData['courseRationale6'] == "Elective" ? " selected" : "");?>>Elective</option></select></td><td><input type="text" id='courseNumber6' name="courseNumber6" size='15' maxlength='10' value="<?php echo $formData['courseNumber6'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle6" id='courseTitle6' value="<?php echo $formData['courseTitle6'];?>"/></td><td><select id="courseCredit6" name="courseCredit6"><option value="" <?php echo ($formData['courseCredit6'] == "" ? " selected" : "");?>>--Select--</option><option value="0.0" <?php echo ($formData['courseCredit6'] == "0.0" ? " selected" : "");?>>0.0</option><option value="0.5" <?php echo ($formData['courseCredit6'] == "0.5" ? " selected" : "");?>>0.5</option><option value="1.0" <?php echo ($formData['courseCredit6'] == "1.0" ? " selected" : "");?>>1.0</option><option value="2.0" <?php echo ($formData['courseCredit6'] == "2.0" ? " selected" : "");?>>2.0</option><option value="3.0" <?php echo ($formData['courseCredit6'] == "3.0" ? " selected" : "");?>>3.0</option><option value="4.0" <?php echo ($formData['courseCredit6'] == "4.0" ? " selected" : "");?>>4.0</option><option value="5.0" <?php echo ($formData['courseCredit6'] == "5.0" ? " selected" : "");?>>5.0</option><option value="6.0" <?php echo ($formData['courseCredit6'] == "6.0" ? " selected" : "");?>>6.0</option><option value="7.0" <?php echo ($formData['courseCredit6'] == "7.0" ? " selected" : "");?>>7.0</option><option value="8.0" <?php echo ($formData['courseCredit6'] == "8.0" ? " selected" : "");?>>8.0</option></select></td></tr>
                    <tr id="courseRow7"><td align='center'><select id="courseRationale7" name="courseRationale7"><option value="" <?php echo ($formData['courseRationale7'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale7'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale7'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale7'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective" <?php echo ($formData['courseRationale7'] == "Elective" ? " selected" : "");?>>Elective</option></select></td><td><input type="text" id='courseNumber7' name="courseNumber7" size='15' maxlength='10' value="<?php echo $formData['courseNumber7'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle7" id='courseTitle7' value="<?php echo $formData['courseTitle7'];?>"/></td><td><select id="courseCredit7" name="courseCredit7"><option value="" <?php echo ($formData['courseCredit7'] == "" ? " selected" : "");?>>--Select--</option><option value="0.0" <?php echo ($formData['courseCredit7'] == "0.0" ? " selected" : "");?>>0.0</option><option value="0.5" <?php echo ($formData['courseCredit7'] == "0.5" ? " selected" : "");?>>0.5</option><option value="1.0" <?php echo ($formData['courseCredit7'] == "1.0" ? " selected" : "");?>>1.0</option><option value="2.0" <?php echo ($formData['courseCredit7'] == "2.0" ? " selected" : "");?>>2.0</option><option value="3.0" <?php echo ($formData['courseCredit7'] == "3.0" ? " selected" : "");?>>3.0</option><option value="4.0" <?php echo ($formData['courseCredit7'] == "4.0" ? " selected" : "");?>>4.0</option><option value="5.0" <?php echo ($formData['courseCredit7'] == "5.0" ? " selected" : "");?>>5.0</option><option value="6.0" <?php echo ($formData['courseCredit7'] == "6.0" ? " selected" : "");?>>6.0</option><option value="7.0" <?php echo ($formData['courseCredit7'] == "7.0" ? " selected" : "");?>>7.0</option><option value="8.0" <?php echo ($formData['courseCredit7'] == "8.0" ? " selected" : "");?>>8.0</option></select></td></tr>
                    <tr id="courseRow8"><td align='center'><select id="courseRationale8" name="courseRationale8"><option value="" <?php echo ($formData['courseRationale8'] == "" ? " selected" : "");?>>--Select--</option><option value="Major" <?php echo ($formData['courseRationale8'] == "Major" ? " selected" : "");?>>Major</option><option value="Minor" <?php echo ($formData['courseRationale8'] == "Minor" ? " selected" : "");?>>Minor</option><option value="Cluster" <?php echo ($formData['courseRationale8'] == "Cluster" ? " selected" : "");?>>Cluster</option><option value="Elective" <?php echo ($formData['courseRationale8'] == "Elective" ? " selected" : "");?>>Elective</option></select></td><td><input type="text" id='courseNumber8' name="courseNumber8" size='15' maxlength='10' value="<?php echo $formData['courseNumber8'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle8" id='courseTitle8' value="<?php echo $formData['courseTitle8'];?>"/></td><td><select id="courseCredit8" name="courseCredit8"><option value="" <?php echo ($formData['courseCredit8'] == "" ? " selected" : "");?>>--Select--</option><option value="0.0" <?php echo ($formData['courseCredit8'] == "0.0" ? " selected" : "");?>>0.0</option><option value="0.5" <?php echo ($formData['courseCredit8'] == "0.5" ? " selected" : "");?>>0.5</option><option value="1.0" <?php echo ($formData['courseCredit8'] == "1.0" ? " selected" : "");?>>1.0</option><option value="2.0" <?php echo ($formData['courseCredit8'] == "2.0" ? " selected" : "");?>>2.0</option><option value="3.0" <?php echo ($formData['courseCredit8'] == "3.0" ? " selected" : "");?>>3.0</option><option value="4.0" <?php echo ($formData['courseCredit8'] == "4.0" ? " selected" : "");?>>4.0</option><option value="5.0" <?php echo ($formData['courseCredit8'] == "5.0" ? " selected" : "");?>>5.0</option><option value="6.0" <?php echo ($formData['courseCredit8'] == "6.0" ? " selected" : "");?>>6.0</option><option value="7.0" <?php echo ($formData['courseCredit8'] == "7.0" ? " selected" : "");?>>7.0</option><option value="8.0" <?php echo ($formData['courseCredit8'] == "8.0" ? " selected" : "");?>>8.0</option></select></td></tr>
                </tbody>
	        </table>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('returnFullTime',$errors)) echo "class='error'";?>><span class="required">*</span>Please check one of the following boxes to indicate your student status.</p>           
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p><input type="checkbox" name="returnFullTime" id="returnFullTime" onclick="checkReturnFullTime()" size='3' value='Yes' <?php if($formData['returnFullTime'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;I plan to return to <b>Full-Time</b> (12+ credits).&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="returnPartTime" id="returnPartTime" onclick="checkReturnPartTime()" size='3' value='Yes' <?php if($formData['returnPartTime'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;I plan to return <b>Part-Time</b>.
            </p>           
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-5 large-5">
            <p <?php if(in_array('numberOfSemesters',$errors)) echo "class='error'";?>><span class="required">*</span>How many more semesters will it take for you to complete your degree?&nbsp;&nbsp;</p>
            <p <?php if(in_array('graduationMonth',$errors) || in_array('graduationYear',$errors)) echo "class='error'";?>><span class="required">*</span>When do you anticipate graduating?</p>
        </div>
        <div class="columns small-12 medium-4 large-4">    
            <input type="text" name="numberOfSemesters" style="width: 50px;" maxlength="1" value='<?php echo $formData['numberOfSemesters'];?>'/>
            <select id="graduationMonth" name="graduationMonth">
                <option value="" <?php echo ($formData['graduationMonth'] == "" ? " selected" : "");?>>--Select--</option>
                <option value="May" <?php echo ($formData['graduationMonth'] == "May" ? " selected" : "");?>>May</option>
                <option value="August" <?php echo ($formData['graduationMonth'] == "August" ? " selected" : "");?>>August</option>
                <option value="December" <?php echo ($formData['graduationMonth'] == "December" ? " selected" : "");?>>December</option>
            </select>
        </div>
        <div class="columns small-12 medium-3 large-3">    
            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <select id="graduationYear" name="graduationYear">
                <?php echo GetGraduationYearOptionsFromArray(); ?>
            </select>
        </div>
    </div>
    <br>
    <div class="row--with-borders">
        <div class="text-center columns small-12">
            <input class="small button secondary button-pop" name="Save" type="submit" value="Save"/>
        </div>
    </div>
    </form>
<?php
}
else if($_SESSION['State'] == "Part 4")
{
?>
    <form action="?" method="POST" enctype="multipart/form-data">
    <div class="row--with--borders">
        <div class="columns small-12">
            <h3>11. Personal Narrative</h3>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p <?php if(in_array('essay',$errors)) echo "class='error'";?>>Please write a short essay (no more than 2-4 pages) that addresses the following questions:</p>
            <ul>
                <li>Why did you leave the University of Rochester?</li>
                <li>Describe the particular difficulties you encountered when you were previously enrolled.</li>
                <li>What have you done since leaving, and how have these activities contributed to your readiness to return to full-time study?</li>
                <li>Explain your reasons for wanting to return now. (If you withdrew from the College because of medical reasons, please explain why you feel ready to return.)</li>
                <li>What are your plans for maintaining personal and academic supports when you return?</li>
                <li>Please elaborate on your academic plans as well as anything else you think we should know.</li>
            </ul>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <?php
            if($essayUploaded)
            {
                echo "<p style='color:blue;'>You last uploaded an essay named <b>" . $essayUploadedName . "</b> on <b>" . $essayUploadedDate . "</b>. Upload a new essay to update this document.</p>";
            }
            else
            {
                echo $fmessage;
                echo $dump;
                echo $message;
                echo "<p style='color:red;'>You have not yet uploaded an essay.</p>";
            }
            ?>
            <p><input name="essay" type="file"/></P>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="text-center columns small-12">
            <input class="small button secondary button-pop" name="uploadEssay" type="submit" value="Upload Essay"/>
        </div>
    </div>
    </form>
<?php
}
else if($_SESSION['State'] == "Review")
{
?>   
    <form action="?" method="POST" enctype="multipart/form-data">
    <br/>
    <br/>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h2>Reenrollment Application - Submission</h2><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>1. Personal Information</h3><br>
        </div>
    </div>
    <br/>
    <br/>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><b>NOTE:</b> Fields marked with <span class="required">*</span> are <b>required</b> fields</p><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <table>
                <tbody>
                    <tr>
                        <td <?php if(in_array('semesterEnroll',$errors)) echo "class='error'";?>><span class="required">*</span><b>In which semester are you enrolling?</b></td>
                        <td><div class="entrySelectWide">
                            <input type="text" name="semesterEnroll" readonly value="<?php echo $formData['semesterEnroll'];?>">
                        </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="studentFirstName" <?php if(in_array('studentFirstName',$errors)) echo "class='error'";?>><span class="required">*</span>First Name</label>
            <input type="text" id="studentFirstName" name="studentFirstName" maxlength="50" readonly value="<?php echo $formData['studentFirstName'];?>"/>
        </div>
        <div class="columns small-12 medium-1 large-1">
            <label for="studentMiddleInitial">M.I.</label>
            <input type="text" id="studentMiddleInitial" maxlength='1' name="studentMiddleInitial" readonly value="<?php echo $formData['studentMiddleInitial'];?>"/>
        </div>
        <div class="columns small-12 medium-4 large-4">
            <label for="studentLastName" <?php if(in_array('studentLastName',$errors)) echo "class='error'";?>><span class="required">*</span>Last Name</label>
            <input type="text" id="studentLastName" name="studentLastName" maxlength="50" readonly value="<?php echo $formData['studentLastName'];?>"/>
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="studentPreferredName" <?php if(in_array('studentPreferredName',$errors)) echo "class='error'";?>><span class="required">*</span>Preferred Name</label>
            <input type="text" id="studentPreferredName" name="studentPreferredName" maxlength='80' readonly value="<?php echo $formData['studentPreferredName'];?>"/>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="studentID" <?php if(in_array('studentID',$errors)) echo "class='error'";?>><span class="required">*</span>Student ID #</label>
            <input type="text" id="studentID" name="studentID" maxlength='8' size='8' readonly value="<?php echo $formData['studentID'];?>"/>
        </div>
        <div class="columns small-12 medium-8 large-8">
            <label for="studentFormerName">Former Name(s), if applicable</label>
            <input type="text" id="studentFormerName" name="studentFormerName" maxlength="80" readonly value="<?php echo $formData['studentFormerName'];?>"/>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="advisorName" <?php if(in_array('advisorName',$errors)) echo "class='error'";?>><span class="required">*</span>Name of Advisor(s)</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type='text' name='advisorName' maxlength="80" readonly value='<?php echo $formData['advisorName']; ?>' size='70'/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="studentAddressLine1" <?php if(in_array('studentAddressLine1',$errors)) echo "class='error'";?>><span class="required">*</span>Address Line 1</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="studentAddressLine1" readonly name="studentAddressLine1" maxlength="75" value="<?php echo $formData['studentAddressLine1'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="studentAddressLine2">Address Line 2</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="studentAddressLine2" readonly name="studentAddressLine2" maxlength="75" value="<?php echo $formData['studentAddressLine2'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="studentCity" <?php if(in_array('studentCity',$errors)) echo "class='error'";?>><span class="required">*</span>City</label>
            <input type="text" id="studentCity" name="studentCity" maxlength="75" readonly value="<?php echo $formData['studentCity'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="studentCountry" <?php if(in_array('studentCountry',$errors)) echo "class='error'";?>><span class="required">*</span>Country</label>
            <input type="text" id="studentCountry" name="studentCountry" maxlength="75" readonly value="<?php echo $formData['studentCountry'];?>">
        </div>
        <div class="columns small-12 medium-2 large-2">
            <label for="studentState" <?php if(in_array('studentState',$errors)) echo "class='error'";?>>State</label>
            <input type="text" id="studentState" name="studentState" maxlength="75" readonly value="<?php echo $formData['studentState'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="studentZipCode" <?php if(in_array('studentZipCode',$errors)) echo "class='error'";?>><span class="required">*</span>Zip Code</label>
            <input type="text" id="studentZipCode" name="studentZipCode" maxlength="25" readonly value="<?php echo $formData['studentZipCode'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-3 large-3">
            <label for="studentDateOfBirth" <?php if(in_array('studentBirthMonth',$errors) || in_array('studentBirthDay',$errors) || in_array('studentBirthYear',$errors)) echo "class='error'";?>><span class="required">*</span>Date of Birth</label>
                <input type="text" name="studentBirthMonth" readonly  value="<?php echo $formData['studentBirthMonth'];?>">
        </div>
        <div class="columns small-12 medium-2 large-1">
        <label for="studentDateOfBirth"> &nbsp;</label>
                <input type="text" name="studentBirthDay" readonly  value="<?php echo $formData['studentBirthDay'];?>">
        </div>
        <div class="columns small-12 medium-2 large-2">
        <label for="studentDateOfBirth"> &nbsp;</label>
                <input type="text" name="studentBirthYear" readonly  value="<?php echo $formData['studentBirthYear'];?>">
        </div>
        <div class="columns small-12 medium-5 large-6">
            <label for="studentHomePhoneNumber" <?php if(in_array('studentHomePhoneNumber',$errors)) echo "class='error'";?>><span class="required">*</span>Home Phone Number</label>
            <input type="text" id="studentHomePhoneNumber" name="studentHomePhoneNumber" maxlength="20" readonly value="<?php echo $formData['studentHomePhoneNumber']; ?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="studentCellPhoneNumber" <?php if(in_array('studentCellPhoneNumber',$errors)) echo "class='error'";?>><span class="required">*</span>Cell Phone Number</label>
            <input type="text" id="studentCellPhoneNumber" name="studentCellPhoneNumber" maxlength="20" readonly value="<?php echo $formData['studentCellPhoneNumber'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="studentEmailAddress" <?php if(in_array('studentEmailAddress',$errors)) echo "class='error'";?>><span class="required">*</span>Email Address</label>
            <input type="text" id="studentEmailAddress" name="studentEmailAddress" maxlength="40" readonly value="<?php echo $formData['studentEmailAddress'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>If you are an international student, please check this box <input type="checkbox" name="studentIsInternational" disabled size='3' value='Yes' <?php echo ($formData['studentIsInternational'] == "Yes" ?  " checked" : "");?>/> one of our International Student Advisors will contact you upon submitting this application, to discuss the immigration matters.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>If you are 21 and over, please check this box <input type="checkbox" name="studentIs21AndOver" disabled size='3' value='Yes' <?php echo ($formData['studentIs21AndOver'] == 'Yes' ? " checked" : "");?>/> you are not required to fill out the Parent(s)/Guardian(s) Information Section.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>2. Parent(s)/Guardian(s) Information</h3><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h5>Parent/Guardian 1 Information</h5><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="parent1FirstName" <?php if(in_array('parent1FirstName',$errors)) echo "class='error'";?>><span class="required">*</span>Parent 1 First Name</label>
            <input type="text" id="parent1FirstName" name="parent1FirstName" maxlength="50" readonly value="<?php echo $formData['parent1FirstName'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="parent1LastName" <?php if(in_array('parent1LastName',$errors)) echo "class='error'";?>><span class="required">*</span>Parent 1 Last Name</label>
            <input type="text" id="parent1LastName" name="parent1LastName" maxlength="50" readonly value="<?php echo $formData['parent1LastName'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="parent1AddressLine1" <?php if(in_array('parent1AddressLine1',$errors)) echo "class='error'";?>><span class="required">*</span>Address Line 1</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="parent1AddressLine1" maxlength="75" name="parent1AddressLine1" readonly value="<?php echo $formData['parent1AddressLine1'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="parent1AddressLine2">Address Line 2</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="parent1AddressLine2" maxlength="75" name="parent1AddressLine2" readonly value="<?php echo $formData['parent1AddressLine2'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="parent1City" <?php if(in_array('parent1City',$errors)) echo "class='error'";?>><span class="required">*</span>City</label>
            <input type="text" id="parent1City" name="parent1City" maxlength="75" readonly value="<?php echo $formData['parent1City'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="parent1Country" <?php if(in_array('parent1Country',$errors)) echo "class='error'";?>><span class="required">*</span>Country</label>
            <input type="text" id="parent1Country" name="parent1Country" maxlength="75" readonly value="<?php echo $formData['parent1Country'];?>">
        </div>
        <div class="columns small-12 medium-2 large-2">
            <label for="parent1State" <?php if(in_array('parent1State',$errors)) echo "class='error'";?>>State</label>
            <input type="text" id="parent1State" name="parent1State" maxlength="75" readonly value="<?php echo $formData['parent1State'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="parent1ZipCode" <?php if(in_array('parent1ZipCode',$errors)) echo "class='error'";?>><span class="required">*</span>Zip Code</label>
            <input type="text" id="parent1ZipCode" name="parent1ZipCode" maxlength="25" readonly value="<?php echo $formData['parent1ZipCode'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="parent1PhoneNumber" <?php if(in_array('parent1PhoneNumber',$errors)) echo "class='error'";?>><span class="required">*</span>Phone Number</label>
            <input type="text" id="parent1PhoneNumber" name="parent1PhoneNumber" maxlength="20" readonly value="<?php echo $formData['parent1PhoneNumber'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="parent1EmailAddress" <?php if(in_array('parent1EmailAddress',$errors)) echo "class='error'";?>><span class="required">*</span>Email Address</label>
            <input type="text" id="parent1EmailAddress" name="parent1EmailAddress" maxlength="40" readonly value="<?php echo $formData['parent1EmailAddress'];?>">
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h5>Parent/Guardian 2 Information (if applicable)</h5><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="parent2FirstName" <?php if(in_array('parent2FirstName',$errors)) echo "class='error'";?>>Parent 2 First Name</label>
            <input type="text" id="parent2FirstName" name="parent2FirstName" maxlength="50" readonly value="<?php echo $formData['parent2FirstName'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="parent2LastName" <?php if(in_array('parent2LastName',$errors)) echo "class='error'";?>>Parent 2 Last Name</label>
            <input type="text" id="parent2LastName" name="parent2LastName" maxlength="50" readonly value="<?php echo $formData['parent2LastName'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="parent2AddressLine1" <?php if(in_array('parent2AddressLine1',$errors)) echo "class='error'";?>>Address Line 1</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="parent2AddressLine1" maxlength="75" name="parent2AddressLine1" readonly value="<?php echo $formData['parent2AddressLine1'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="parent2AddressLine2">Address Line 2</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="parent2AddressLine2" maxlength="75" name="parent2AddressLine2" readonly value="<?php echo $formData['parent2AddressLine2'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="parent2City" <?php if(in_array('parent2City',$errors)) echo "class='error'";?>>City</label>
            <input type="text" id="parent2City" name="parent2City" maxlength="75" readonly value="<?php echo $formData['parent2City'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="parent2Country" <?php if(in_array('parent2Country',$errors)) echo "class='error'";?>>Country</label>
            <input type="text" id="parent2Country" name="parent2Country" maxlength="75" readonly value="<?php echo $formData['parent2Country'];?>">
        </div>
        <div class="columns small-12 medium-2 large-2">
            <label for="parent2State" <?php if(in_array('parent2State',$errors)) echo "class='error'";?>>State</label>
            <input type="text" id="parent2State" name="parent2State" maxlength="75" readonly value="<?php echo $formData['parent2State'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
            <label for="parent2ZipCode" <?php if(in_array('parent2ZipCode',$errors)) echo "class='error'";?>>Zip Code</label>
            <input type="text" id="parent2ZipCode" name="parent2ZipCode" maxlength="25" readonly value="<?php echo $formData['parent2ZipCode'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="parent2PhoneNumber" <?php if(in_array('parent2PhoneNumber',$errors)) echo "class='error'";?>>Phone Number</label>
            <input type="text" id="parent2PhoneNumber" name="parent2PhoneNumber" maxlength="20" readonly value="<?php echo $formData['parent2PhoneNumber'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="parent2EmailAddress" <?php if(in_array('parent2EmailAddress',$errors)) echo "class='error'";?>>Email Address</label>
            <input type="text" id="parent2EmailAddress" name="parent2EmailAddress" maxlength="40" readonly value="<?php echo $formData['parent2EmailAddress'];?>">
        </div>
    </div>
    <br>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h2>3. Attendance and Application History</h2><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-5 large-5">
            <p <?php if(in_array('monthEntered',$errors) || in_array('yearEntered',$errors)) echo "class='error'";?>><span class="required">*</span>Date you entered the University of Rochester (Month/Year)</p>
            <p <?php if(in_array('monthLeft',$errors) || in_array('yearLeft',$errors)) echo "class='error'";?>><span class="required">*</span>Date you left the University of Rochester (Month/Year)</p>
        </div>
        <div class="columns small-12 medium-4 large-4">
                <input type="text" name="monthEntered" readonly value="<?php echo $formData['monthEntered'];?>">
                <input type="text" name="monthLeft" readonly value="<?php echo $formData['monthLeft'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
                <input type="text" name="yearEntered" readonly value="<?php echo $formData['yearEntered'];?>">
                <input type="text" name="yearLeft" readonly value="<?php echo $formData['yearLeft'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-9 large-9">
            <p <?php if(in_array('appliedBeforeYes',$errors)) echo "class='error'";?>><span class="required">*</span>Have you ever applied to return to University of Rochester through the Re-Enrollment Application before?</p>
        </div>
        <div class="columns small-12 medium-3 large-3">
            <p><input type="checkbox" name="appliedBeforeYes" id="appliedBeforeYes" size='3' disabled value='Yes' <?php echo ($formData['appliedBeforeYes'] == "Yes" ?  " checked" : "");?>/>&nbsp;&nbsp;<b>Yes</b>&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="appliedBeforeNo" id="appliedBeforeNo" size='3' disabled value='Yes' <?php echo ($formData['appliedBeforeNo'] == "Yes" ?  " checked" : "");?>/>&nbsp;&nbsp;<b>No</b>
            </p>           
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-5 large-5">
            <p <?php if(in_array('monthAppliedBefore',$errors) || in_array('yearAppliedBefore',$errors)) echo "class='error'";?>>If yes, when&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        </div>
        <div class="columns small-12 medium-4 large-4">
                <input type="text" name="monthAppliedBefore" readonly value="<?php echo $formData['monthAppliedBefore'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">
                <input type="text" name="yearAppliedBefore" readonly value="<?php echo $formData['yearAppliedBefore'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>4. Arrival Information</h3><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('onCampusRequired',$errors)) echo "class='error'";?>><span class="required">*</span>Reenrolling students are required to be on campus prior to the start of classes to meet with their advisors. Students who are unable to do so will not be readmitted.</p>
            <p><input type="checkbox" name="onCampusRequired" size='3' disabled value='Yes' <?php echo ($formData['onCampusRequired'] == "Yes" ?  " checked" : "");?>/>&nbsp;&nbsp;I <b>acknowledge</b> that If I am readmitted, I will arrive at least 48 hours prior to the start of classes.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>5. Housing Preference</h3><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('liveOnCampusYes',$errors) || in_array('liveOnCampusNo',$errors)) echo "class='error'";?>><span class="required">*</span>Please make us aware of your interest with housing for when you return. Unfortunately, housing is not guaranteed for reenrolling students. Assignments are made on a rolling basis for both fall and spring semesters. More information will be provided once your application is received.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p><input type="checkbox" name="liveOnCampusYes" id="liveOnCampusYes" size='3' disabled value='Yes' <?php echo ($formData['liveOnCampusYes'] == "Yes" ?  " checked" : "");?>/>&nbsp;&nbsp;I <b>desire</b> to live on campus.&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="liveOnCampusNo" id="liveOnCampusNo" size='3' disabled value='Yes' <?php echo ($formData['liveOnCampusNo'] == "Yes" ?  " checked" : "");?>/>&nbsp;&nbsp;I <b>intend</b> to live off campus.
            </p>           
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <h3>6. Financial Information</h3><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>Financial aid deadlines are the same as the re-enrollment deadlines: November 1 and May 1. These are also the deadlines for clearing up any balances that remain on your account from your previous enrollment. You can review information about deadlines and financial aid eligibility on the re-enrollment website <a href="https://www.rochester.edu/college/ccas/handbook/financial-aid.html" target="_new">https://www.rochester.edu/college/ccas/handbook/financial-aid.html</a>. You may contact the University of Rochester’s Bursar Office at <a href="https://www.rochester.edu/adminfinance/bursar/" target="_new">https://www.rochester.edu/adminfinance/bursar/</a> or (585) 275-3931 with any questions you may have regarding your account.</p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <h3>7. Health/Mental Health Care Provider Information (if applicable)</h3>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p <?php if(in_array('medicalYes',$errors)) echo "class='error'";?>>If you are on a medical leave, or if medical issues were a factor in your leaving the University of Rochester, your application will not be complete without approval from UHS or UCC. You should contact UHS (585) 275-2679 or email mlivingston@uhs.rochester.edu) or UCC (585) 275-3115 well before the reenrollment application deadline to discuss your plans to return. The dean of the College, following the recommendation made by UHS/UCC, will make a final decision regarding reenrollment or the deferment of your application to the next semester’s reenrollment cycle.</p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small--12">
            <p><input type="checkbox" name="medicalYes" id="medicalYes" size='3' disabled value='Yes' <?php echo ($formData['medicalYes'] == "Yes" ?  " checked" : "");?>/>&nbsp;&nbsp;I believe that I will need medical clearance in order to reenroll at Rochester.</p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small--12">
            <p><input type="checkbox" name="medicalNo" id="medicalNo" size='3' disabled value='Yes' <?php echo ($formData['medicalNo'] == "Yes" ?  " checked" : "");?>/>&nbsp;&nbsp;I have been away from the College for more than 10 months and know that I must submit a new Health History Form.</p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <h3>8. Academic Work While Away (if applicable)</h3>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>If you took college courses while away, please provide the following information along with an official transcript of work in progress and/or completed.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-7 large-7">
            <label for="firstInstitution" <?php if(in_array('firstInstitution',$errors)) echo "class='error'";?>>Name of First Institution</label>
            <input type="text" id="firstInstitution" name="firstInstitution" maxlength="80" readonly value="<?php echo $formData['firstInstitution']; ?>">
        </div>
        <div class="columns small-12 medium-5 large-5">
            <label for="firstInstitutionDate" <?php if(in_array('firstInstitutionDate',$errors)) echo "class='error'";?>>Dates Attended</label>
            <input type="text" id="firstInstitutionDate" name="firstInstitutionDate" maxlength="40" readonly value="<?php echo $formData['firstInstitutionDate']; ?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-7 large-7">
            <label for="secondInstitution" <?php if(in_array('secondInstitution',$errors)) echo "class='error'";?>>Name of Second Institution</label>
            <input type="text" id="secondInstitution" name="secondInstitution" maxlength="80" readonly value="<?php echo $formData['secondInstitution']; ?>">
        </div>
        <div class="columns small-12 medium-5 large-5">
            <label for="secondInstitutionDate" <?php if(in_array('secondInstitutionDate',$errors)) echo "class='error'";?>>Dates Attended</label>
            <input type="text" id="secondInstitutionDate" name="secondInstitutionDate" maxlength="40" readonly value="<?php echo $formData['secondInstitutionDate']; ?>">
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <h3>9. Employment While Away (if applicable)</h3>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>If you were employed while away, please ask your supervisor to submit a letter of support on your behalf.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="supervisorName" <?php if(in_array('supervisorName',$errors)) echo "class='error'";?>>Name of Supervisor</label>
            <input type="text" id="supervisorName" name="supervisorName" maxlength="90" readonly value="<?php echo $formData['supervisorName'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="companyName" <?php if(in_array('companyName',$errors)) echo "class='error'";?>>Company/Organization</label>
            <input type="text" id="companyName" name="companyName" maxlength="70" readonly value="<?php echo $formData['companyName'];?>">
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-4 large-4">
            <label for="supervisorTitle" <?php if(in_array('supervisorTitle',$errors)) echo "class='error'";?>>Title</label>
            <input type="text" id="supervisorTitle" name="supervisorTitle" maxlength="70" readonly value="<?php echo $formData['supervisorTitle'];?>">
        </div>
        <div class="columns small-12 medium-4 large-4">
            <label for="supervisorPhoneNumber" <?php if(in_array('supervisorPhoneNumber',$errors)) echo "class='error'";?>>Phone Number</label>
            <input type="text" id="supervisorPhoneNumber" maxlength="20" name="supervisorPhoneNumber" readonly value="<?php echo $formData['supervisorPhoneNumber'];?>">
        </div>
        <div class="columns small-12 medium-4 large-4">
            <label for="supervisorEmailAddress" <?php if(in_array('supervisorEmailAddress',$errors)) echo "class='error'";?>>Email Address</label>
            <input type="text" id="supervisorEmailAddress" maxlength="40" name="supervisorEmailAddress" readonly value="<?php echo $formData['supervisorEmailAddress'];?>">
        </div>
    </div>
    <br>
    <div class="row--with--borders">
        <div class="columns small-12">
            <h2>10. Academic Plan</h2>
        </div>
    </div>
    <br/>
    <br/>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>What is your intended plan to complete the Rochester Curriculum?</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="major" <?php if(in_array('major',$errors)) echo "class='error'";?>><span class="required">*</span>Major</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="major" maxlength="70" name="major" readonly value="<?php echo $formData['major'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="minor">Minor</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="minor" name="minor" maxlength="70" readonly value="<?php echo $formData['minor'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="cluster1" <?php if(in_array('cluster1',$errors)) echo "class='error'";?>><span class="required">*</span>Cluster</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="cluster1" maxlength="70" name="cluster1" readonly value="<?php echo $formData['cluster1'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="cluster2">Cluster</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="cluster2" maxlength="70" name="cluster2" readonly value="<?php echo $formData['cluster2'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <label for="academicPlanPerson" <?php if(in_array('academicPlanPerson',$errors)) echo "class='error'";?>><span class="required">*</span>With whom have you discussed your Academic Plan with (i.e. Major Department, OMSA Advisor, College Center for Advising Services Advisor, etc.</label>
        </div>
    </div>
    <hr class="KEEP" />
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><input type="text" id="academicPlanPerson" maxlength="70" name="academicPlanPerson" readonly value="<?php echo $formData['academicPlanPerson'];?>" size="70"/></p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small--12">
            <p><input type="checkbox" name="majorDeclared" size='3' disabled value='Yes' <?php echo ($formData['majorDeclared'] == "Yes" ?  " checked" : "");?>/>&nbsp;&nbsp;I have officially declared this major.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
			<p <?php if(in_array('firstSemester',$errors) || in_array('courseRow',$errors)) echo "class='error'";?>>Please list the courses you plan on taking during your first semester back at the University of Rochester as well as the rationale (i.e.: major, cluster, elective, etc.) for each one.</p>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <table align="center">
                <tbody><tr><th>Rationale</th><th>Course Number</th><th>Course Title</th><th>Credit Hrs</th>
                    <tr><td>e.g., Major<br/></td><td>CSC 172</td><td>Data Structures and Algorithms</td><td>4.0</td></tr>
                    <tr><td align='center'><input type="text" name="courseRationale1" readonly value="<?php echo $formData['courseRationale1'];?>"/></td><td><input type="text" id='courseNumber1' name="courseNumber1" size='15' maxlength='10' readonly value="<?php echo $formData['courseNumber1'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle1" id='courseTitle1' readonly value="<?php echo $formData['courseTitle1'];?>"/></td><td><input type="text" id="courseCredit1" name="courseCredit1" readonly value="<?php echo $formData['courseCredit1'];?>"/></td></tr>
                    <tr><td align='center'><input type="text" name="courseRationale2" readonly value="<?php echo $formData['courseRationale2'];?>"/></td><td><input type="text" id='courseNumber2' name="courseNumber2" size='15' maxlength='10' readonly value="<?php echo $formData['courseNumber2'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle2" id='courseTitle2' readonly value="<?php echo $formData['courseTitle2'];?>"/></td><td><input type="text" id="courseCredit2" name="courseCredit2" readonly value="<?php echo $formData['courseCredit2'];?>"/></td></tr>
                    <tr><td align='center'><input type="text" name="courseRationale3" readonly value="<?php echo $formData['courseRationale3'];?>"/></td><td><input type="text" id='courseNumber3' name="courseNumber3" size='15' maxlength='10' readonly value="<?php echo $formData['courseNumber3'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle3" id='courseTitle3' readonly value="<?php echo $formData['courseTitle3'];?>"/></td><td><input type="text" id="courseCredit3" name="courseCredit3" readonly value="<?php echo $formData['courseCredit3'];?>"/></td></tr>
                    <tr><td align='center'><input type="text" name="courseRationale4" readonly value="<?php echo $formData['courseRationale4'];?>"/></td><td><input type="text" id='courseNumber4' name="courseNumber4" size='15' maxlength='10' readonly value="<?php echo $formData['courseNumber4'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle4" id='courseTitle4' readonly value="<?php echo $formData['courseTitle4'];?>"/></td><td><input type="text" id="courseCredit4" name="courseCredit4" readonly value="<?php echo $formData['courseCredit4'];?>"/></td></tr>
                    <tr><td align='center'><input type="text" name="courseRationale5" readonly value="<?php echo $formData['courseRationale5'];?>"/></td><td><input type="text" id='courseNumber5' name="courseNumber5" size='15' maxlength='10' readonly value="<?php echo $formData['courseNumber5'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle5" id='courseTitle5' readonly value="<?php echo $formData['courseTitle5'];?>"/></td><td><input type="text" id="courseCredit5" name="courseCredit5" readonly value="<?php echo $formData['courseCredit5'];?>"/></td></tr>
                    <tr><td align='center'><input type="text" name="courseRationale6" readonly value="<?php echo $formData['courseRationale6'];?>"/></td><td><input type="text" id='courseNumber6' name="courseNumber6" size='15' maxlength='10' readonly value="<?php echo $formData['courseNumber6'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle6" id='courseTitle6' readonly value="<?php echo $formData['courseTitle6'];?>"/></td><td><input type="text" id="courseCredit6" name="courseCredit6" readonly value="<?php echo $formData['courseCredit6'];?>"/></td></tr>
                    <tr><td align='center'><input type="text" name="courseRationale7" readonly value="<?php echo $formData['courseRationale7'];?>"/></td><td><input type="text" id='courseNumber7' name="courseNumber7" size='15' maxlength='10' readonly value="<?php echo $formData['courseNumber7'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle7" id='courseTitle7' readonly value="<?php echo $formData['courseTitle7'];?>"/></td><td><input type="text" id="courseCredit7" name="courseCredit7" readonly value="<?php echo $formData['courseCredit7'];?>"/></td></tr>
                    <tr><td align='center'><input type="text" name="courseRationale8" readonly value="<?php echo $formData['courseRationale8'];?>"/></td><td><input type="text" id='courseNumber8' name="courseNumber8" size='15' maxlength='10' readonly value="<?php echo $formData['courseNumber8'];?>"/></td><td><input type="text" size='70' maxlength='70' name="courseTitle8" id='courseTitle8' readonly value="<?php echo $formData['courseTitle8'];?>"/></td><td><input type="text" id="courseCredit8" name="courseCredit8" readonly value="<?php echo $formData['courseCredit8'];?>"/></td></tr>
                </tbody>
	        </table>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('returnFullTime',$errors)) echo "class='error'";?>><span class="required">*</span>Please check one of the following boxes to indicate your student status.</p>           
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p>
                <input type="checkbox" name="returnFullTime" id="returnFullTime" size='3' disabled value='Yes' <?php echo ($formData['returnFullTime'] == "Yes" ?  " checked" : "");?>/>&nbsp;&nbsp;I plan to return to <b>Full-Time</b> (12+ credits).&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="returnPartTime" id="returnPartTime" size='3' disabled value='Yes' <?php echo ($formData['returnPartTime'] == "Yes" ?  " checked" : "");?>/>&nbsp;&nbsp;I plan to return <b>Part-Time</b>.
            </p>           
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-5 large-5">
            <p <?php if(in_array('numberOfSemesters',$errors)) echo "class='error'";?>><span class="required">*</span>How many more semesters will it take for you to complete your degree?&nbsp;&nbsp;</p>
            <p <?php if(in_array('graduationMonth',$errors) || in_array('graduationYear',$errors)) echo "class='error'";?>><span class="required">*</span>When do you anticipate graduating?</p>
        </div>
        <div class="columns small-12 medium-4 large-4">    
            <input type="text" name="numberOfSemesters" style="width: 50px;" maxlength="1" readonly value='<?php echo $formData['numberOfSemesters'];?>'/>
                <input type="text" name="graduationMonth" readonly value="<?php echo $formData['graduationMonth'];?>">
        </div>
        <div class="columns small-12 medium-3 large-3">    
            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <input type="text" name="graduationYear" readonly value="<?php echo $formData['graduationYear'];?>">
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <h3>11. Personal Narrative</h3>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p <?php if(in_array('essay',$errors)) echo "class='error'";?>>Please write a short essay (no more than 2-4 pages) that addresses the following questions:</p>
            <ul>
                <li>Why did you leave the University of Rochester?</li>
                <li>Describe the particular difficulties you encountered when you were previously enrolled.</li>
                <li>What have you done since leaving, and how have these activities contributed to your readiness to return to full-time study?</li>
                <li>Explain your reasons for wanting to return now. (If you withdrew from the College because of medical reasons, please explain why you feel ready to return.)</li>
                <li>What are your plans for maintaining personal and academic supports when you return?</li>
                <li>Please elaborate on your academic plans as well as anything else you think we should know.</li>
            </ul>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <?php
            if($essayUploaded)
            {
                echo "<p style='color:blue;'>You last uploaded an essay named <b>" . $essayUploadedName . "</b> on <b>" . $essayUploadedDate . "</b>. Upload a new essay to update this document.</p>";
            }
            else
            {
                echo $fmessage;
                echo $dump;
                echo $message;
                echo "<p style='color:red;'>You have not yet uploaded an essay.</p>";
            }
            ?>
        </div>
    </div>
    <br>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p align="center"><b>NOTE:</b> By clicking the submit application button, your account will be suspended and a copy of your application will be emailed to you. Once submitted you will not be able to modify any of the information in the application. Please make sure you review all information prior to submission. You will not be able to submit your application if you have not filled out all the required fields.</p><br>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="text-center columns small-12">
            <input class="small button secondary button-pop" name="Submit" type="submit" value="Submit Application" <?php echo $disabled;?>/>
        </div>
    </div>
    </form>

<?php 
}
}   // close $whichForm condition
?>
</fieldset>
<br/>
</article>
<script>

function checkAppliedBeforeYes()
{
    if(document.getElementById("appliedBeforeYes").checked == true)
    {
        document.getElementById("appliedBeforeNo").checked = false;
    }
}
function checkAppliedBeforeNo()
{
    if(document.getElementById("appliedBeforeNo").checked == true)
    {
        document.getElementById("appliedBeforeYes").checked = false;
    }
}

function checkLiveOnCampusYes()
{
    if(document.getElementById("liveOnCampusYes").checked == true)
    {
        document.getElementById("liveOnCampusNo").checked = false;
    }
}
function checkLiveOnCampusNo()
{
    if(document.getElementById("liveOnCampusNo").checked == true)
    {
        document.getElementById("liveOnCampusYes").checked = false;
    }
}

function checkMedicalYes()
{
    if(document.getElementById("medicalYes").checked == true)
    {
        document.getElementById("medicalNo").checked = false;
    }
}
function checkMedicalNo()
{
    if(document.getElementById("medicalNo").checked == true)
    {
        document.getElementById("medicalYes").checked = false;
    }
}

function checkReturnFullTime()
{
    if(document.getElementById("returnFullTime").checked == true)
    {
        document.getElementById("returnPartTime").checked = false;
    }
}
function checkReturnPartTime()
{
    if(document.getElementById("returnPartTime").checked == true)
    {
        document.getElementById("returnFullTime").checked = false;
    }
}

$(document).ready(function () {
    $("#courseRow2").hide();
    $("#courseRow3").hide();
    $("#courseRow4").hide();
    $("#courseRow5").hide();
    $("#courseRow6").hide();
    $("#courseRow7").hide();
    $("#courseRow8").hide();

    var cntCourses = 1;

    if (($('#courseRationale2').val() != '') || ($('#courseNumber2').val() != '') || ($('#courseTitle2').val() != '') || ($('#courseCredit2').val() != '')) {  
        $("#courseRow2").show();
        cntCourses = 2;
    }
    if (($('#courseRationale3').val() != '') || ($('#courseNumber3').val() != '') || ($('#courseTitle3').val() != '') || ($('#courseCredit3').val() != '')) {  
        $("#courseRow3").show();
        cntCourses = 3;
    }
    if (($('#courseRationale4').val() != '') || ($('#courseNumber4').val() != '') || ($('#courseTitle4').val() != '') || ($('#courseCredit4').val() != '')) {  
        $("#courseRow4").show();
        cntCourses = 4;
    }
    if (($('#courseRationale5').val() != '') || ($('#courseNumber5').val() != '') || ($('#courseTitle5').val() != '') || ($('#courseCredit5').val() != '')) {  
        $("#courseRow5").show();
        cntCourses = 5;
    }
    if (($('#courseRationale6').val() != '') || ($('#courseNumber6').val() != '') || ($('#courseTitle6').val() != '') || ($('#courseCredit6').val() != '')) {  
        $("#courseRow6").show();
        cntCourses = 6;
    }
    if (($('#courseRationale7').val() != '') || ($('#courseNumber7').val() != '') || ($('#courseTitle7').val() != '') || ($('#courseCredit7').val() != '')) {  
        $("#courseRow7").show();
        cntCourses = 7;
    }
    if (($('#courseRationale8').val() != '') || ($('#courseNumber8').val() != '') || ($('#courseTitle8').val() != '') || ($('#courseCredit8').val() != '')) {  
        $("#courseRow8").show();
        cntCourses = 8;
    }

    $("#addCourseRow").click(function() {
        cntCourses++;
        if(cntCourses == 2)
        {
            $("#courseRow2").show();
        }
        else if(cntCourses == 3)
        {
            $("#courseRow3").show();
        }
        else if(cntCourses == 4)
        {
            $("#courseRow4").show();
        }
        else if(cntCourses == 5)
        {
            $("#courseRow5").show();
        }
        else if(cntCourses == 6)
        {
            $("#courseRow6").show();
        }
        else if(cntCourses == 7)
        {
            $("#courseRow7").show();
        }
        else if(cntCourses == 8)
        {
            $("#courseRow8").show();
        }
        else
        {
            alert("Only 8 course rows permitted.");
            cntCourses = 8;
        }
    });

    $("#removeCourseRow").click(function() {
        if(cntCourses == 2)
        {
            $("#courseRow2").hide();
            $("#courseRationale2").val('');$("#courseNumber2").val('');$("#courseTitle2").val('');$("#courseCredit2").val('');
            cntCourses--;
        }
        else if(cntCourses == 3)
        {
            $("#courseRow3").hide();
            $("#courseRationale3").val('');$("#courseNumber3").val('');$("#courseTitle3").val('');$("#courseCredit3").val('');
            cntCourses--;
        }
        else if(cntCourses == 4)
        {
            $("#courseRow4").hide();
            $("#courseRationale4").val('');$("#courseNumber4").val('');$("#courseTitle4").val('');$("#courseCredit4").val('');
            cntCourses--;
        }
        else if(cntCourses == 5)
        {
            $("#courseRow5").hide();
            $("#courseRationale5").val('');$("#courseNumber5").val('');$("#courseTitle5").val('');$("#courseCredit5").val('');
            cntCourses--;
        }
        else if(cntCourses == 6)
        {
            $("#courseRow6").hide();
            $("#courseRationale6").val('');$("#courseNumber6").val('');$("#courseTitle6").val('');$("#courseCredit6").val('');
            cntCourses--;
        }
        else if(cntCourses == 7)
        {
            $("#courseRow7").hide();
            $("#courseRationale7").val('');$("#courseNumber7").val('');$("#courseTitle7").val('');$("#courseCredit7").val('');
            cntCourses--;
        }
        else if(cntCourses == 8)
        {
            $("#courseRow8").hide();
            $("#courseRationale8").val('');$("#courseNumber8").val('');$("#courseTitle8").val('');$("#courseCredit8").val('');
            cntCourses--;
        }
        else
        {
            alert("Cannot remove anymore course rows.");
        }
    });

});

</script>

<?php		
	$html .= ob_get_contents();
	ob_end_clean();
}

$html .= "</div>";	//Make sure we close the page container.

$style = "style_riverbank.css";
$pageTitle = "Reenrollment Application";
$pageHeader = "Reenrollment Application";
$pageContent = $html;


include_once('templates/responsive_riverbank.php');
?>