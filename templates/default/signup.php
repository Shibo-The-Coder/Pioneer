<?php 
if (isset($_POST['newSignup'])) {
    $firstName = $_POST['firstName']; 
    $lastName = $_POST['lastName'];
    $email = $_POST['email']; 
    $phone =$_POST['phone']; 
    $position = $_POST['position'];
    echo $position;
    add_table_signup($firstName, $lastName, $email, $phone, $position);
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
        <table id="basicinfo">
            <tr><td>First Name:*</td><td><input type="text" name="firstName" autocomplete="off"/></td></tr>
            <tr><td>Last Name:*</td><td><input type="text" name="lastName" autocomplete="off"/></td></tr>
            <tr><td>Email:*</td><td><input type="email" name="email" autocomplete="off"/></td></tr>
            <tr><td>Phone:</td><td><input type="phone" name="phone" autocomplete="off"/></td></tr>
                    <tr><td>I'm a</td><td>
                            <select name="position">
                                <option value="writer">Writer</option>
                                <option value="photographer">Photographer</option>
                                <option value="copyeditor">Copy Editor</option
                                <option value="layouteditor">Layout Editor</option>
                                <option value="publicrelations">Public Relations</option
                                <option value="webdeveloper">Web Developer</option>
                            </select>
                </td></tr>
        </table>
    <input type="submit" name ="submit" value="Submit" class="form_input form_submit_input"/>
    </form>
</div>