
    <?php 
    if ($adminElement == "") {
        include_once 'posts_home.php';
    } else { ?>
            <h1>Edit an article</h1>
    <div id="admin_posts" class="admin_box">
    <?php 
    $post = get_table_posts_byName($adminElement);
    if ($post !=false) {
        $post = $post[0];
        $collab = get_table_posts_collab_byPostID($post['ID'])[0];
    } else {
        echo "The article you're looking for doesn't exist.";
    }
    }
?>
    </div>