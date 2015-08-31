<?php
//Config
define("SIGNUP_MAX_POSITIONS", 2);
$possiblepositions = ["writer", "photographer", "copyeditor", "layout editor", "public relations","web developer"];
$submit_message = "";
$returning_user = false;
if (!isset($_POST['newSignUp'])&&$signupID!="" &&$signuptoken!="") {
    $returning_user = get_table_signup_byTokenandID($signuptoken, $signupID);
}
if ($returning_user != false) {
    $firstName = $returning_user[0]['firstName'];
    $lastName =$returning_user[0]['lastName'];
    $year =$returning_user[0]['year'];
    $email = $returning_user[0]['email'];
    $phone =$returning_user[0]['phone'];
    $position = [];
        foreach ($possiblepositions as $positionname) {
            $positionname = preg_replace('/\s+/','_', $positionname);
            $allthepositions = explode(",", $returning_user[0]['position']);
            if (array_search($positionname,$allthepositions)){
                $position[] = $positionname;
            }
        }
    $whyinterested = $returning_user[0]['whyinterested'];
    $previousexperience = $returning_user[0]['previousexperiences'];
    $indesign_experience = $returning_user[0]['indesign_experience'];
    $htmlcss_experience = $returning_user[0]['htmlcss_experience'];
    $selfstory = $returning_user[0]['selfstory'];
    $submit_message = "Welcome back! We've loaded your information, but be sure to attach all the files you want us to see again!";
 } else {
    $firstName = isset($_POST['firstName'])? $_POST['firstName']:"";
    $lastName = isset($_POST['lastName'])? $_POST['lastName']:"";
    $year = isset($_POST['year'])? $_POST['year']:"";
    $email = isset($_POST['email'])? $_POST['email']:"";
    $phone =isset($_POST['phone'])? $_POST['phone']:"";
    $position = [];
        foreach ($possiblepositions as $positionname) {
            $positionname = preg_replace('/\s+/','_', $positionname);
            if (isset($_POST[$positionname])){
                $position[] = $positionname;
            }
        }
    $whyinterested = isset($_POST['whyinterested'])? $_POST['whyinterested']:"";
    $previousexperience = isset($_POST['previousexperience'])? $_POST['previousexperience']:"";
    $indesign_experience = isset($_POST['indesign_experience'])? $_POST['indesign_experience']:"no";
    $htmlcss_experience = isset($_POST['htmlcss_experience'])? $_POST['htmlcss_experience']:"no";
    $selfstory = isset($_POST['selfstory'])? $_POST['selfstory']:"";
 }
$attachments = "";
$confirm = false;
//Errors - yah I know I'll use exceptions later :P
$firstNameError = false;
$lastNameError = false;
$emailError = false;
$phoneError = false;
$positionError = false;
$whyinterestedError = false;
$previousexperienceError = false;
$indesign_experienceError = false;
$htmlcss_experienceError = false;
$selfstoryError = false;
$attachmentsError = false;//security_file_check();
$formtoken = form_saveToken("newSignup");
if (isset($_POST['newSignup'])) {
    $confirm = true;
    //Validate input. No need to escape html because prepared statements (using PDO).
    $submit = true;
    $bordercolor = "";
    if ($firstName==""||  is_numeric($firstName)){
        $submit = false;
        $firstNameError = true;
    }
    if ($lastName==""||  is_numeric($lastName)){
        $submit = false;
        $lastNameError = true;
    }
    if ($email==""){
        $submit = false;
        $emailError = true;
    }
    if ($whyinterested==""){
        $submit = false;
        $whyinterestedError = true;
    }
    if (count($position) < 1 || count($position) > SIGNUP_MAX_POSITIONS){
        $submit = false;
        $positionError = true;
    }
    if ($submit){
        $position = implode(",", $position);
        if (get_table_signup_byToken($formtoken)===false) {
            if (isset($_FILES['attachments']) && count($_FILES['attachments'])>0) {
                $attachments = file_upload("attachments", "signup_attachments",$formtoken);
            }
            $returning_user = get_table_signup_byEmail($email);
            if ($returning_user){
                $ID = $returning_user[0]['ID'];
                $fields =["firstName",
        "lastName", 
         "year",
        "email", 
        "phone", 
        "position", 
        "whyinterested", 
        "previousexperiences",
        "indesign_experience", 
        "htmlcss_experience",
        "selfstory",
        "attachments",
        "token"];
                $fieldValues = [$firstName, $lastName, $year,$email, $phone, $position,$whyinterested,$previousexperience,$indesign_experience,$htmlcss_experience,$selfstory,$attachments,$formtoken];
                update_table_signup_byID($ID, $fields, $fieldValues);
            $submit_message = "Thanks! Your information has been updated!";
            } else{            
                add_table_signup($firstName, $lastName, $year, $email, $phone, $position,$whyinterested,$previousexperience,$indesign_experience,$htmlcss_experience,$selfstory,$attachments,$formtoken);
            $submit_message = "Thanks! We will contact you soon!";
            }
            $user = get_table_signup_byToken($formtoken);
            signup_complete_email($formtoken, $user[0]['ID'], $firstName, $email);
        } else {
            $submit_message = "Thanks, we've already saved your registration information!";
        }
    $firstName = ""; 
    $lastName = ""; 
    $year="";
    $email = ""; 
    $phone =""; 
    $position = []; 
    $whyinterested ="";
$previousexperience ="";
$indesign_experience = "no";
$htmlcss_experience = "no";
$selfstory = "";
$attachments = "";
    
    } else {
    $submit_message = "Something's not right..";
    }
}
    ?>   
<div id="stockphoto">
    <img src="<?php echo SITEURL."stock.jpg";?>">
</div>
<div id="signup">
    <h1 style='margin-bottom: -15px;'>Be a part of Pioneer!</h1>
    <form id="newSignup" method="POST" enctype="multipart/form-data" action="<?php echo SITEURL."signup/confirm";?>">
        <input type="hidden" name = "newSignup" value = 'newSignup'/>
        <?php echo $confirm?"<p style='color:red;'>".$submit_message."</p>":""; ?>
        <div id="basicinfo">
                <h4>What's your name?</h4> 
                <div id='firstName' class="form_input_container"><span>First Name:*</span> <input type="text" class= "form_input_container_input" name="firstName" autocomplete="off" value="<?php echo $firstName;?>" style="border-color:<?php echo $firstNameError?'red':'initial';?>"/></div>
                <div id='lastName' class="form_input_container"><span>Last Name:*</span><input type="text" class= "form_input_container_input"  name="lastName" autocomplete="off" value="<?php echo $lastName;?>" style="border-color:<?php echo $lastNameError?'red':'initial';?>"/></div>
                <div id='year' class="form_input_container"><span>Major/Year: </span><input type="text" class= "form_input_container_input"  name="year" autocomplete="off" value="<?php echo $year;?>"/></div>
                <h4>Tell us your email so we can reach you!</h4>
                <div id='email' class="form_input_container"><span>Email:*</span><input type="email"  class= "form_input_container_input" name="email" autocomplete="off" value="<?php echo $email;?>"style="border-color:<?php echo $emailError?'red':'initial';?>"/></div>
                <div id='phone' class="form_input_container"><span>Phone: (optional) </span><input type="phone"  class= "form_input_container_input" name="phone" autocomplete="off" value="<?php echo $phone;?>"style="border-color:<?php echo $phoneError?'red':'initial';?>"/></div>
                <h4>What do you want to do for the Pioneer?</h4>   
                <div id='position' class="form_input_container" <?php echo $positionError?"style='border: 2px ridge red'":"";?>><span style='display:block'>I want to be a* (Pick 2 max) </span>
                                <?php 
                                foreach ($possiblepositions as $positionname) {
                                    $positionname_search = preg_replace('/\s+/','_', $positionname);
                                    echo "<input  class= 'form_input_container_input'  type='checkbox' name='$positionname' ";
                                    echo array_search($positionname_search, $position)!==false?"checked ":"";
                                    echo ">".  ucwords($positionname)."<br>";
                                }
                                ?>
                            </select>
                </div>
                <div id='whyinterested' class="form_input_container"><span>Why do you want to join the Pioneer?*</span><textarea class= "form_input_container_input"  name="whyinterested" style="border-color:<?php echo $whyinterestedError?'red':'initial';?>"/><?php echo $whyinterested;?></textarea></div>
                <div id='previousexperiences' class="form_input_container"><span>What previous experience do you have with a publication? (Not needed to apply but will be considered) </span><textarea name="previousexperience"  class= "form_input_container_input" style="border-color:<?php echo $previousexperienceError?'red':'initial';?>"/><?php echo $previousexperience;?></textarea></div>
                <div id='attachments' class="form_input_container"><span>Please upload samples of previous work, if any.</span>   <input class= "form_input_container_input"  type="file" name ="attachments[]" multiple="" /> </div>
                <div id='indesign_experience' class="form_input_container"><input type="checkbox" name ="indesign_experience" class= "form_input_container_input"  /> <span>Have you worked with InDesign before?</span></div>
                <div id='htmlcss_experience' class="form_input_container"><input type="checkbox" name ="htmlcss_experience" class= "form_input_container_input"  /> <span>Do you have experience with HTML/CSS? </span> </div>
                <div id='selfstory' class="form_input_container"><span>Tell us something cool about yourself!</span><textarea class= "form_input_container_input"  name="selfstory" style="border-color:<?php echo $selfstoryError?'red':'initial';?>"/><?php echo $selfstory;?></textarea></div>
                <div id='submit' class="form_input_container" style="text-align: right; padding-top: 10px;"> <input type="submit" name ="submit" value="Submit" class="form_input form_submit_input submit_btn"/></div>
        </div>
    </form>
        
</div>
    <script>
        $("#firstName>input").focus().css('opacity', '1').parent(this).css('opacity', '1');
  
    $("input").focus(function(){
  $(this).parent(this).css('opacity', '1');
  $(this).css('opacity', '1');
}).blur(function(){
  $(this).css('opacity', '0.5');
  $(this).parent(this).css('opacity', '0.5').hover(function(){$(this).css('opacity','1.0');}, function(){$(this).css('opacity', '0.5');});
});
 $("textarea").focus(function(){
  $(this).parent(this).css('opacity', '1');
  $(this).css('opacity', '1');
}).blur(function(){
  $(this).css('opacity', '0.5');
  $(this).parent(this).css('opacity', '0.5').hover(function(){$(this).css('opacity','1.0');}, function(){$(this).css('opacity', '0.5');});
});

$(document).keydown(function(e) {
    switch(e.which) {
        case 38: // up
        $(':focus').closest(".form_input_container").prevAll().find(".form_input_container_input").focus();
        break;
        case 40: // down
        $(':focus').closest(".form_input_container").next(".form_input_container:not(h4)").find(".form_input_container_input").focus();
        break;
        default: return; // exit this handler for other keys
    }
    e.preventDefault(); // prevent the default action (scroll / move caret)
});
    </script>