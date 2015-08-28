<?php 
//Config
define("SIGNUP_MAX_POSITIONS", 2);
$possiblepositions = ["writer", "photographer", "copyeditor", "layout editor", "public relations","web developer"];

    $firstName = isset($_POST['firstName'])? $_POST['firstName']:""; 
    $lastName = isset($_POST['lastName'])? $_POST['lastName']:""; 
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
    $submit_message = "";
    
    $formtoken = form_saveToken("newSignup");
    //Fields "element" => "name" => "type" => "additional"\
    /* Work on this later
    $fields = [
        "input" => ["firstName" => ["text"]],
        "input" => ["lastName" => ["text"]],
        "input" => ["email" => ["email"]],
        "input" => ["phone" => ["text"]]
    ];
     */
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
        if (isset($_FILES['attachments']) && count($_FILES['attachments'])>0) {
            $attachments = file_upload("attachments", "signup_attachments",$formtoken);
        }
        add_table_signup($firstName, $lastName, $email, $phone, $position,$whyinterested,$previousexperience,$indesign_experience,$htmlcss_experience,$selfstory,$attachments,$formtoken);
        $user = get_table_signup_byToken($formtoken);
        signup_email($formtoken, $user[0]['ID']);
    $submit_message = "Thanks!";
        $firstName = ""; 
    $lastName = ""; 
    $email = ""; 
    $phone =""; 
    $position = ""; 
    } else {
    $submit_message = "Something's not right..";
    }
}
    ?>   
<div id="stockphoto">
    <img src="<?php echo SITEURL."stock.jpg";?>">
</div>
<div id="signup">
    <h1>Be a part of Pioneer!</h1>
    <!-- Change the width and height values to suit you best -->
<div class="typeform-widget" data-url="https://shehabattia96.typeform.com/to/bMOOzy" data-text="Pioneer" style="width:100%;height:500px;"></div>
<script>(function(){var qs,js,q,s,d=document,gi=d.getElementById,ce=d.createElement,gt=d.getElementsByTagName,id='typef_orm',b='https://s3-eu-west-1.amazonaws.com/share.typeform.com/';if(!gi.call(d,id)){js=ce.call(d,'script');js.id=id;js.src=b+'widget.js';q=gt.call(d,'script')[0];q.parentNode.insertBefore(js,q)}})()</script>
    <div style="font-family: Sans-Serif;font-size: 12px;color: #999;opacity: 0.5; padding-top: 5px;">Powered by <a href="http://www.typeform.com/?utm_campaign=typeform_bMOOzy&amp;utm_source=website&amp;utm_medium=typeform&amp;utm_content=typeform-embedded&amp;utm_term=English" style="color: #999" target="_blank">Typeform</a></div>

    </div>
    <div>
        
</div>