<?php
$signups = get_table_signup(0);
$totalsignups = countRows($signups);
if ($user->isLoggedIn == true && isset($_POST['emailSignupsButton'])) {
    if (signup_reminder_email()) {
        echo "Emails sent successfully! :)";
    } else {
        echo "Oh no! There was an error! Admin was notified..";
        email("shibo@gatech.edu", "sign up email problem", "Ughh, you need to fix something.. Do it right this time!");
        elog("Error sending sign up email.");
    }
}
?>
    <h1 class="adminContent_header">Sign Ups</h1>
    
    <div id="admin_signup_review" class="admin_box">
        <h2>Sign Up Review</h2>
        <div>Total Sign-ups: <?php echo $totalsignups;?></div>
        <?php if ($totalsignups == 0) { echo "There are no signups currently."; } else { 
                echo "<div>Latest sign up: ".$signups[0]['firstName']." ".$signups[0]['lastName']."</div>";
          } ?>
    </div>
    
        <?php if ($totalsignups == 0) { } else { ?>
    <div id="admin_signup_email class" class="admin_box">
        <h2>Sign Up Reminder:</h2>
        Email Preview:
        <div><?php echo "<div style='border: solid #000 1px;'>Title:<h3 style='display:inline;'>".SIGNUP_REMINDER_EMAIL_TITLE."</h3><br><br>".nl2br(SIGNUP_REMINDER_EMAIL_BODY)."</div>";?></div>
        <div>
            <form action="<?php echo ADMIN_PAGE_URL."signup/email";?>" method="POST">
                <input type="submit" value="Email all signed up students!" name="emailSignupsButton"/>
            </form>
        </div>
        </div> <?php } ?>
