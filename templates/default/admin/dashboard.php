<?php 
$totalposts = countRows(get_table_posts(0));
$totalusers = countRows(get_table_users(0));
$totalcomments = countRows(get_table_comments(0));
$totalissues = countRows(get_table_issues(0));
$totalcollab = countRows(get_table_posts_collab(0));
?>
    <h1 class="adminContent_header">Admin Dash</h1>
    <div id="admin_statistics" class="admin_box">
        <h2>Statistics</h2>
        <table>
            <tr>
                <td>Total Posts</td><td><?php echo $totalposts;?></td>
            </tr>
            <tr>
                <td>Total Issues</td><td><?php echo $totalissues;?></td>
            </tr>
            <tr>
                <td>Total Users</td><td><?php echo $totalusers;?></td>
            </tr>
            <tr>
                <td>Total Comments</td><td><?php echo $totalcomments;?></td>
            </tr>
        </table>
    </div>
    <div id="admin_collab" class="admin_box">
        <h2>Articles being worked on.</h2>
        <?php if ($totalcollab == 0) { echo "There are no articles being worked on right now."; } else {
            $showing = 15;
            $collabs = get_table_posts_collab_order_dateASC($showing);
            echo "<table class='color_important_gradient'><tr>
    <th>Name</th>
    <th>Notes</th>
    <th>Due Date</th>
  </tr>";
            foreach ($collabs as $post) {
                $postdata = get_table_posts_byID($post['postID'])[0];
                $posttitle = $postdata['title'];
                $postname = $postdata['name'];
                echo "<tr><td>".admin_make_anchor("posts/edit/".$postname, $posttitle)."</td><td>".$post['notes']."</td><td>".$post['LayoutEditorDueDate']."</td></tr>";
            }
            echo "</table>";
          } ?>
    </div>