<?php 
//This page can be used to manage the order of elements on the page.
?>
<div id="navigation">
    <ul>
        <li><a href="<?php echo BASEDIR; ?>">Home</a></li>
        <li><a href="<?php echo BASEDIR."contactus/"; ?>">Contact Us</a></li>
        <?php if (verifyRequestWithUserRole("adminDash")) { //Verify permission to access dash. ?>
        <li><a href="<?php echo BASEDIR."staff/"; ?>">Staff Page</a></li>
        <?php }?>        
    </ul>
</div>
<div id="user">
    <h4>Welcome, <?php putUserMessage();?> </h4>
</div>
<div id="header">
    <h4 id="welcome_text">Welcome to the</h4>
    <h1 class="logo"><?php echo SITE_NAME; ?></h1>
    <h4 class="slogan"><?php echo SITE_SLOGAN;?></h4>
</div>
<hr>
<div id="content">
    <?php include TEMPLATE_PATH . 'content.php'; ?>
</div>
<hr>

<footer><?php echo FOOTER_CONTENT; ?></footer>