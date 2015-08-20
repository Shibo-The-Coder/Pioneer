<!--posts-->
<div id='{post_id}' class='post_container'>
    <h1 class='post_title'><a href='{post_url}'>{post_title}</a></h1>
    <div class='post_infoBox'>
    <span class='post_infoBox_element'> Category: {issue_name}</span>
    <span class='post_infoBox_element'> By: <a href='{author_url}'>{author_firstName} {author_lastName}</a></span>
    <span class='post_infoBox_element'> Date: {post_date}</span>
    </div>
    <div class='post_body'>{post_body}</div>
</div>
<!--comments_reply_box-->
<div class='post_reply_box'>
<h5>Type a comment:</h5>
<form id='post_{post_id}_comment' action="./">
    <div>{form_saveToken}</div>
    <div><textarea class='post_replyTextArea' name='replyBox'></textarea></div>
    <div><input class='post_submitButton' type='submit' value='reply'/></div>
</form>
</div>
<!--comments-->
{comments}
<!--end-->