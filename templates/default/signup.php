<?php 
    $firstName = isset($_POST['firstName'])? $_POST['firstName']:""; 
    $lastName = isset($_POST['lastName'])? $_POST['lastName']:""; 
    $email = isset($_POST['email'])? $_POST['email']:""; 
    $phone =isset($_POST['phone'])? $_POST['phone']:""; 
    $position = isset($_POST['position'])? $_POST['position']:""; 
    $confirm = false;
    $firstNameError = false;
    $lastNameError = false;
    $emailError = false;
    $phoneError = false;
    $positionError = false;
    $submit_message = "";
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
    $possiblepositions = ["writer", "photographer", "copyeditor", "layouteditor", "publicrelations","webdeveloper"];
    if ($position==""||  array_search($position,$possiblepositions) === false){
        $submit = false;
        $positionError = true;
    }
    if ($submit){
        add_table_signup($firstName, $lastName, $email, $phone, $position);
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
    <?php form_saveToken("newSignup"); ?>
        <input type="hidden" name = "newSignup" value = 'newSignup'/> 
        <h3>Basic Information:</h3>
        <?php echo $confirm?"<p style='color:red;'>".$submit_message."</p>":"";?>
        <table id="basicinfo">
            <tr><td>First Name:*</td><td><input type="text" name="firstName" autocomplete="off" value="<?php echo $firstName;?>" style="border-color:<?php echo $firstNameError?'red':'initial';?>"/></td></tr>
            <tr><td>Last Name:*</td><td><input type="text" name="lastName" autocomplete="off" value="<?php echo $lastName;?>" style="border-color:<?php echo $lastNameError?'red':'initial';?>"/></td></tr>
            <tr><td>Email:*</td><td><input type="email" name="email" autocomplete="off" value="<?php echo $email;?>"style="border-color:<?php echo $emailError?'red':'initial';?>"/></td></tr>
            <tr><td>Phone:</td><td><input type="phone" name="phone" autocomplete="off" value="<?php echo $phone;?>"style="border-color:<?php echo $phoneError?'red':'initial';?>"/></td></tr>
                    <tr><td>I'm want to be a</td><td>
                            <select name="position" style="border-color:<?php echo $positionError?'red':'none';?>"/>>
                                <option value =""></option> 
                                <option value="writer" <?php echo $position=="writer"?"selected='selected'":"";?>>Writer</option>
                                <option value="photographer" <?php echo $position=="photographer"?"selected='selected'":"";?>>Photographer</option>
                                <option value="copyeditor" <?php echo $position=="copyeditor"?"selected='selected'":"";?>>Copy Editor</option
                                <option value="layouteditor" <?php echo $position=="layouteditor"?"selected='selected'":"";?>>Layout Editor</option>
                                <option value="publicrelations" <?php echo $position=="publicrelations"?"selected='selected'":"";?>>Public Relations</option
                                <option value="webdeveloper" <?php echo $position=="webdeveloper"?"selected='selected'":"";?>>Web Developer</option>
                            </select>
                </td></tr>
                    <tr><td style="text-align: right; padding-top: 10px;" colspan="2">
    <input type="submit" name ="submit" value="Submit" class="form_input form_submit_input submit_btn"/></td></tr>
        </table>
    </form>
</div>