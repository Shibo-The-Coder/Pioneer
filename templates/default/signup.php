<?php 
 var_dump(add_table_signup("firstName", "lastName", "email", "404451", "type", "position", "whyinterested", "previousexperiences"));
if (isset($_POST['newSignup'])) {
    $firstName = $_POST['firstName']; 
    $lastName = $_POST['lastName'];
    $email = $_POST['email']; 
    $phone =$_POST['phone']; 
    $type = $_POST['type'];
    $position = $_POST['position'];
    $whyinterested = $_POST['whyinterested'];
    $previousexperiences = $_POST['previousexperiences'];
    add_table_signup($firstName, $lastName, $email, $phone, $type, $position, $whyinterested, $previousexperiences);
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
            <tr><td>First Name:*</td><td><input type="text" name="firstName"/></td></tr>
            <tr><td>Last Name:*</td><td><input type="text" name="lastName"/></td></tr>
            <tr><td>Email:*</td><td><input type="email" name="email"/></td></tr>
            <tr><td>Phone:</td><td><input type="phone" name="phone"/></td></tr>
                    <tr><td>I'm a</td><td>
                            <select name="type">
                                <option>Student</option>
                                <option>Alum</option>
                                <option>Staff</option
                                <option>Other</option>
                                Writer
                                Photographer
                                Copy Editor
                                Layout Editor
                                Web Dev
                                Public Relations
                            </select>
                </td></tr>
        </table>
    <input type="submit" name ="submit" value="Submit" class="form_input form_submit_input"/>
    </form>
</div>