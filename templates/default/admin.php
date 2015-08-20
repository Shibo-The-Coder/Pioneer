<?php
/*
full capabilities viewPosts/editPosts/newPosts/
 * deletePosts/flagPosts/approvePosts/newComment/
 * editComment/deleteComment/flagComment/approveComments/
 * adminDash/editDash/moderate/addUsers/editUsers/removeUsers/banUsers/
 * adminPosts/adminUsers/adminComments/adminSettings/
 *  */
include_once '/admin_functions.php';
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
    </ul>
</div>
<?php 
    if ($adminPage == "posts") { 
          include "/admin/posts.php";
      } elseif ($adminPage == "comments") { 
          include "/admin/comments.php";
      } elseif ($adminPage == "issues") { 
          include "/admin/issues.php";
      } elseif ($adminPage == "users") { 
          include "/admin/users.php";
      } elseif ($adminPage == "settings") { 
          include "/admin/settings.php";
      } else {
          include "/admin/dashboard.php";
      } 
} else {
    include LOGIN_PAGE_FILE;
}
?>