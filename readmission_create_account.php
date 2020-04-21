<?php
//*******************************************************************************************************
//	readmission_create_account.php 
//
//	Author: Linda Vasavong
//	Date Created: ????
//	Date Modified: Linda Vasavong
//*******************************************************************************************************


require_once('form_processors/readmission_manage_account/m_create_account.php');

$html .= "<div class='page row'>";   // Setup stock responsive header and page container

if($status == "OK")
{

ob_start();
?>

<article class="columns small-12">
<br/>
<fieldset class="formField">
    <br/>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>Your account has been created. You will recieve an email about your account information.</p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>Please click the login button below to go back to the login page.</p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="text-center columns small-12">
            <form action="?" method="POST">
                <input class="small button secondary button-pop" name="login" type="submit" value="Login"/>
            </form>
        </div>
    </div>
</fieldset>
<br/>
</article>

<?php
$html .= ob_get_contents();
ob_end_clean();
}
else
{

ob_start();

if($status == "DB_ERR")
	$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>There was a problem submitting this form to the Database, the database could currently be offline. Contact the College Center for Advising Services (585) 275-2354 for further assistance.</div></div>";
	
if(!$valid && !empty($errors))
	$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_fail medium-centered section--thick'>One or more required fields indicated below have been left blank!</div></div>"; 
	
if(!$valid && !empty($error_messages))
    echo $common->GetErrorDisplay($error_messages); 

?>

<article class="columns small-12">
<br/>
<fieldset class="formField">
    <br/>
    <div class="row--with--borders">
        <form action="?" method="POST">
            <div class="columns-small-12">
                <p>&nbsp;&nbsp;&nbsp;<input class="small button secondary button-pop" name="back" type="submit" value="Go Back"/></p>
            </div>
        </form>
    </div>
    <div class="row--with--borders">
        <h2>Create Account</h2>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>Please fill out the fields below to make an account. Use your netID for your user name. <a href='http://www.rochester.edu/its/netid/' target='_blank'>What is NetID?/Help</a></p>
        </div>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>If you have previously submitted a Reenrollment Application and want to submit another application, please make an account.</p>
        </div>
    </div>
    <form action="?" method="POST">
        <div class="row--with--borders">
            <div class="columns small-12 medium-6 large-6">
                <label for="studentEmail1" <?php if(in_array("studentEmail1", $errors)) echo "class='error'";?>><span class="required">*</span>Email Address</label>
                <input type="text" name="studentEmail1" value="<?php echo $formData['studentEmail1'];?>"/>
            </div>
            <div class="columns small-12 medium-6 large-6">
                <label for="studentEmail2" <?php if(in_array("studentEmail2", $errors)) echo "class='error'";?>><span class="required">*</span>Confirm Email Address</label>
                <input type="text" name="studentEmail2" value="<?php echo $formData['studentEmail2'];?>"/>
            </div>
        </div>
        <div class="row--with--borders">
            <div class="columns small-12 medium-6 large-6">
                <label for="userName1" <?php if(in_array("userName1", $errors)) echo "class='error'";?>><span class="required">*</span>User Name</label>
                <input type="text" name="userName1" value="<?php echo $formData['userName1'];?>"/>
            </div>
            <div class="columns small-12 medium-6 large-6">
                <label for="userName2" <?php if(in_array("userName2", $errors)) echo "class='error'";?>><span class="required">*</span>Confirm User Name</label>
                <input type="text" name="userName2" value="<?php echo $formData['userName2'];?>"/>
            </div>
        </div>
        <div class="row--with--borders">
            <div class="columns small-12 medium-6 large-6">
                <label for="password1" <?php if(in_array("password1", $errors)) echo "class='error'";?>><span class="required">*</span>Password</label>
                <input type="password" name="password1" id="input1" value="<?php echo $formData['password1'];?>"/>
            </div>
            <div class="columns small-12 medium-6 large-6">
                <label for="password2" <?php if(in_array("password2", $errors)) echo "class='error'";?>><span class="required">*</span>Confirm Password</label>
                <input type="password" name="password2" id="input2" value="<?php echo $formData['password2'];?>"/>
            </div>
        </div>
        <div class="row--with--borders">
            <div class="columns small-12 medium-6 large-6">
                <p><input type="checkbox" size='3' onclick="showInput1()"/>&nbsp;&nbsp;Show Password</p>
            </div>
            <div class="columns small-12 medium-6 large-6">
                <p><input type="checkbox" size='3' onclick="showInput2()"/>&nbsp;&nbsp;Show Password</p>
            </div>
        </div>
        <br>&nbsp;</br>
        <div class="row--with-borders">
            <div class="text-center columns small-12">
                <input class="small button secondary button-pop" name="createAccount" type="submit" value="Submit"/>
            </div>
        </div>
    </form>

</fieldset>
<br/>
</article>

<script>
function showInput1() {
  var x = document.getElementById("input1");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
function showInput2() {
  var x = document.getElementById("input2");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>

<?php		
	$html .= ob_get_contents();
    ob_end_clean();
}

$html .= "</div>";	//Make sure we close the page container.

$style = "style_riverbank.css";
$pageTitle = "Reenrollment Application Account Management";
$pageHeader = "Reenrollment Application Account Management";
$pageContent = $html;


include_once('templates/responsive_riverbank.php');
?>