<h1 class="adminContent_header">Articles</h1>
    <div id="admin_posts" class="admin_box">
        <h2>Articles</h2>
        <?php if ($totalposts == 0) { echo "There are no articles in the database."; } else {
            $showing = 25;
            $order = "DESC";
            if ($order == "ASC") {
                $posts = get_table_posts_order_dateASC($showing); 
            } else {
                $posts = get_table_posts_order_dateDESC($showing);
            }
            echo "<table><tr>
    <th>Name</th>
    <th>Description</th>
    <th>Date Published</th>
    <th>Status</th>
  </tr>";
            foreach ($posts as $post) {
                $notes = get_table_posts_collab_byPostID($post['ID'])[0]['notes'];
                echo "<tr><td>".$post['title']."</td><td>".$notes."</td><td>".$post['dateCreated']."</td><td>".$post['publish']."</td><td>".  admin_make_anchor("posts/edit/".$post['name'], "Edit")."</td></tr>";
            }
            echo "</table>";
          } ?>
    </div>