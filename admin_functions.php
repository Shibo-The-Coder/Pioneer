<?php
/*
 * @function pageLoad: takes a pre-formatted page as string and splits it into an array of parts/components. 
 * See post.php in the Default template for an example.
 * @param page: a string returned by file_get_contents.
 * @return array: a nested array of part name => part content 
 */

function translateAdminPage($userData)
{
    if ($userData!=false) {
        $pageData = file_get_contents(BASEDIR.TEMPLATE_PATH.'admin.php');
        $user = $userData;
        return strtr($pageData, 
            [
            '{user_id}' => $user['userID'],
            '{user_firstName}' => $user['firstName'], 
            '{user_lastName}' => $user['lastName'],
            '{user_dateJoined}' => $user['dateJoined'],
            '{user_post_count}' => count(getPostByUserID($user['userID'])),
            '{user_comments_count}' => count(getPostComments(null, $user['userID'])),
            '{user_email}' => $user['email'],
            '{user_phone}' => $user['phone'], 
            '{user_website}' =>$user['website'], 
            '{user_major}' => $user['major'], 
            '{user_graduationDate}' => $user['graduationDate'],
            '{user_age}' => $user['age'],
            '{user_country}' => $user['country'],
            '{bio}' => $user['bio']
        ]);
     } else {
        return "Sorry, we couldn't find the person you were looking for! :(";
    }
}
//Will get the names of people from a comma sepeated string of IDs
function get_posts_collab_names($userIDs = "")
{
    $IDs = explode(",",$userIDs);
    $out = "";
    foreach ($IDs as $ID) {
        $person = get_table_users_byID($ID);
        $out = $person[0]['firstName']. " ". $person[0]['lastName']. ", ".$out;
    }
    return $out;
}
//Will output an anchor
function admin_make_anchor($url, $text)
{
    return "<a href ='".ADMIN_PAGE_URL.$url."'>".$text."</a>";
}