<!--user-->
<div id='user_{user_id}' class="user_container">
    <h3 class='user_name'>{user_firstName} {user_lastName}</h3>
    <div class='user_infoBox'>
        <h3>Stats</h3>
        <span class='user_infoBox_element'>Member Since {user_dateJoined}</span>
        <span class='user_infoBox_element'>Posts: {user_post_count}</span>
        <span class='user_infoBox_element'>Comments: {user_comments_count}</span>
        <h3>Contacts</h3>
        <span class='user_infoBox_element'>Email: {user_email}</span>
        <span class='user_infoBox_element'>Phone: {user_phone}</span>
        <span class='user_infoBox_element'>Website: <a href="{user_website}">{user_website}</a></span>
        <h3>About Me:</h3>
        <span class='user_infoBox_element'>Major: {user_major}</span>
        <span class='user_infoBox_element'>Graduation Date: {user_graduationDate}</span>
        <span class='user_infoBox_element'>Age: {user_age}</span>
        <span class='user_infoBox_element'>Country: {user_country}</span>
        <p>{bio}</p>
    </div>
</div>
<!--end-->