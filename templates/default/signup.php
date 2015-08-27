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
    $fileError = security_file_check();
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
        add_table_signup($firstName, $lastName, $email, $phone, $position,"","","","","","",$formtoken);
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
    <form id="newSignup" method="POST" action="<?php echo SITEURL."signup/confirm";?>">
        <input type="hidden" name = "newSignup" value = 'newSignup'/> 
        <h3>Basic Information:</h3>
        <?php echo $confirm?"<p style='color:red;'>".$submit_message."</p>":""; ?>
        <table id="basicinfo">
            <tr><td>First Name:*</td><td><input type="text" name="firstName" autocomplete="off" value="<?php echo $firstName;?>" style="border-color:<?php echo $firstNameError?'red':'initial';?>"/></td></tr>
            <tr><td>Last Name:*</td><td><input type="text" name="lastName" autocomplete="off" value="<?php echo $lastName;?>" style="border-color:<?php echo $lastNameError?'red':'initial';?>"/></td></tr>
            <tr><td>Email:*</td><td><input type="email" name="email" autocomplete="off" value="<?php echo $email;?>"style="border-color:<?php echo $emailError?'red':'initial';?>"/></td></tr>
            <tr><td>Phone:</td><td><input type="phone" name="phone" autocomplete="off" value="<?php echo $phone;?>"style="border-color:<?php echo $phoneError?'red':'initial';?>"/></td></tr>
                    <tr><td>I want to be a (Pick 2 max)</td><td <?php echo $positionError?"style='border: 2px ridge red'":"";?>>
                                <?php 
                                foreach ($possiblepositions as $positionname) {
                                    $positionname_search = preg_replace('/\s+/','_', $positionname);
                                    echo "<input type='checkbox' name='$positionname' ";
                                    echo array_search($positionname_search, $position)!==false?"checked ":"";
                                    echo ">".  ucwords($positionname)."<br>";
                                }
                                ?>
                            </select>
                </td></tr>
                    <tr><td>Why do you want to join the Pioneer?*</td><td><textarea name="whyinterested" style="border-color:<?php echo $whyinterestedError?'red':'initial';?>"/><?php echo $whyinterested;?></textarea></td></tr>
                    <tr><td>What previous experience do you have with a publication? (Not needed to apply but will be considered)</td><td><textarea name="previousexperience" style="border-color:<?php echo $previousexperienceError?'red':'initial';?>"/><?php echo $previousexperience;?></textarea></td></tr>
                    <tr><td></td><td style="">   <input type="checkbox" name ="indesign_experience" /> Do you have experience with Indesign? </td></tr>
                    <tr><td></td><td style="" >   <input type="checkbox" name ="htmlcss_experience" /> Do you have experience with HTML/CSS? </td></tr>
                    
                    <tr><td>Tell us something cool about yourself!</td><td><textarea name="selfstory" style="border-color:<?php echo $selfstoryError?'red':'initial';?>"/><?php echo $selfstoryError;?></textarea></td></tr>
                    <tr><td style="text-align: right; padding-top: 10px;" colspan="2">    <input type="submit" name ="submit" value="Submit" class="form_input form_submit_input submit_btn"/></td></tr>
        </table>
    </form>
</div>