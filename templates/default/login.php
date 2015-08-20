<?php 
 if (!isset($loginAction)) {$loginAction = "";}
if (isset($_POST['username'])&&isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rememberMe = (isset($_POST['rememberMe'])?true:false);
    $login = $user->login($username, $password, $rememberMe);
    echo $login;
} elseif (isset ($_POST['logout'])||$loginAction=="logout") {
    $logout = $user->logout();
    echo $logout;
}
if ($user->isLoggedIn) {
    header("Location: ".BASEDIR);
} else {
?>
        <h1>Login:</h1>
        <form id="login" method="POST" action="<?php echo BASEDIR.'login/';?>">
        <?php form_saveToken(); ?>
        <input type="hidden" name = "login" value = 'login'/>
        Username: <input type="text" name ="username" placeholder="Username" class="form_input form_text_input"/>
        Password: <input type="password" name ="password" placeholder="Password" class="form_input form_password_input"/>
        Remember Me? <input type="checkbox" name ="rememberMe" class="form_input form_checkbox_input"/>
        <input type="submit" name ="submit" value="Submit" class="form_input form_submit_input"/>
        </form>
  <?php 
} 
            ?>