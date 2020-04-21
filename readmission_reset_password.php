<?php
//*******************************************************************************************************
//	readmission_reset_password.php 
//
//	Author: Linda Vasavong
//	Date Created: ????
//	Date Modified: Linda Vasavong
//*******************************************************************************************************


require_once('form_processors/readmission_manage_account/m_reset_password.php');

$html .= "<div class='page row'>";   // Setup stock responsive header and page container

if($layout == "READY")
{
    
ob_start();
?>

<article class="columns small-12">
<br/>
<fieldset class="formField">
    <br/>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>Your password has been changed. You will recieve an email about your new password.</p>
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
	$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>There was a problem submitting a request to reset your password to the Database, the database could currently be offline. Contact the College Center for Advising Services (585) 275-2354 for further assistance.</div></div>";
	
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
        <h2>Reset Password</h2>
    </div>
    <div class="row--with--borders">
        <div class="columns small-12">
            <p>Please enter your email address below and we will send you a new password.</p>
        </div>
    </div>
    <form action="?" method="POST">
        <div class="row--with--borders">
            <div class="columns small-12 medium-6 large-6">
                <label for="studentEmail" <?php if(in_array("studentEmail", $errors)) echo "class='error'";?>><span class="required">*</span>Email Address</label>
                <input type="text" name="studentEmail" value="<?php echo $formData['studentEmail'];?>"/>
            </div>
        </div>
        <br>&nbsp;</br>
        <div class="row--with-borders">
            <div class="text-center columns small-12">
                <input class="small button secondary button-pop" name="resetPassword" type="submit" value="Reset Password"/>
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