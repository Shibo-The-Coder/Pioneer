<?php 
//This page can be used to manage the order of elements on the page.
?>
<div id="topContainer">
<div id="navigation">
    <br>
    <ul>
        <li><a href="<?php echo HOME_PAGE_URL; ?>">Home</a></li>
        <li><a href="<?php echo CONTACTUS_PAGE_URL; ?>">Contact Us</a></li>
        <li><a href="<?php echo SIGNUP_PAGE_URL; ?>">Sign Up!</a></li>
        <?php if (verifyRequestWithUserRole("adminDash")) { //Verify permission to access dash. ?>
        <li><a href="<?php echo ADMIN_PAGE_URL; ?>">Staff Page</a></li>
        <?php }?>        
    </ul>
</div>
<div id="user">
    <h4>Welcome, <?php putUserMessage();?> </h4>
</div>
<div id="header">
    <h4 id="welcome_text">Welcome to the</h4>
    <h1 class="logo"><?php echo SITE_NAME; ?></h1>
    <h4 class="slogan"><?php echo SITE_SLOGAN;?><br></h4>
</div>
</div>
<div id="content">
    <?php include TEMPLATE_PATH . 'content.php'; ?>
</div>

<footer>
    <?php echo FOOTER_CONTENT; ?>
</footer>