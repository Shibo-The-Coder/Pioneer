<?php 
$totalposts = countRows(get_table_posts(0));
?>
<div id='admin_posts' class='admin_container'>
    <?php 
    if ($adminAction == "edit") {
        include_once 'posts_edit.php';
    } elseif ($adminAction == "new")
    {
        include_once 'posts_new.php';
    } else { 
       include_once 'posts_home.php';
    }
    ?>
</div>