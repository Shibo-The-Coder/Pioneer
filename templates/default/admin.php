<?php
/*
full capabilities viewPosts/editPosts/newPosts/
 * deletePosts/flagPosts/approvePosts/newComment/
 * editComment/deleteComment/flagComment/approveComments/
 * adminDash/editDash/moderate/addUsers/editUsers/removeUsers/banUsers/
 * adminPosts/adminUsers/adminComments/adminSettings/
 *  */
include_once BASEDIR.'admin_functions.php';
define("ADMIN_TEMPLATE_PATH",TEMPLATE_PATH."admin/");
if (verifyRequestWithUserRole("adminDash")) { //Verify permission to access dash. ?>
<div id="admin_navigation">
    <ul>
        <li><?php echo admin_make_anchor("dashboard/","Dashboard");?></li>
        <li>
            <?php if (verifyRequestWithUserRole("adminPosts")) { //Verify permission to access dash. ?>
            <?php echo admin_make_anchor("posts/","Articles");?>
            <ul>
                <?php if (verifyRequestWithUserRole("newPosts")) { //Verify permission to access dash. ?>
                <li id="admin_new_post"><?php echo admin_make_anchor("posts/new/","New Article");?></li>
                <?php }?>
                
                <?php if (verifyRequestWithUserRole("editPosts")) { //Verify permission to access dash. ?>
                <li id="admin_edit_post"><?php echo admin_make_anchor("posts/drafts/","Drafts");?></li>
            <?php }?>
            </ul>
        </li>
            <?php }?>
        <div style="display:none;">
        <li>
            <?php if (verifyRequestWithUserRole("adminIssues")) { //Verify permission to access dash. ?>
            <?php echo admin_make_anchor("issues/","Issues");?>
            <ul>
                <li id="admin_new_issue"><?php echo admin_make_anchor("issues/new/","New Issue");?></li>
            </ul>
        </li>
        <?php }
        if (verifyRequestWithUserRole("adminUsers")) { //Verify permission to access dash. ?>
        <li>
            <?php echo admin_make_anchor("users/","Users");?>
        </li>
        <?php }
        if (verifyRequestWithUserRole("adminComments")) { //Verify permission to access dash. ?>
        <li>
            <?php echo admin_make_anchor("comments/","Comments");?>
        </li>
        <?php }
        if (verifyRequestWithUserRole("adminSettings")) { //Verify permission to access dash. ?>
        <li>
            <?php echo admin_make_anchor("settings/","Settings");?>
        </li>
        <?php }?>
        </div>
    </ul>
</div>
<div id="admin_content">
    <?php 
        if ($adminPage == "posts") { 
              include ADMIN_TEMPLATE_PATH."posts.php";
          } elseif ($adminPage == "comments") { 
              include ADMIN_TEMPLATE_PATH."comments.php";
          } elseif ($adminPage == "issues") { 
              include ADMIN_TEMPLATE_PATH."issues.php";
          } elseif ($adminPage == "users") { 
              include ADMIN_TEMPLATE_PATH."users.php";
          } elseif ($adminPage == "settings") { 
              include ADMIN_TEMPLATE_PATH."settings.php";
          } else {
              include ADMIN_TEMPLATE_PATH."dashboard.php";
          } 
    } else {
         include LOGIN_PAGE_FILE;
    }
    ?>
</div>