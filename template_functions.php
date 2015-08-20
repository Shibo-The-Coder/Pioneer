<?php
/******************************************************/
/***********USER MESSAGES***********************/
/******************************************************/
//Shows the "Welcome User" message.
function putUserMessage(){
    global $user;
    if ($user->isLoggedIn){
        echo $user->getUser()['firstName']."! <a href='".LOGIN_PAGE_URL."logout/'>Logout</a>";
    } else {
        echo "Guest! <a href='".LOGIN_PAGE_URL."'>Login</a>";
    }
}
/******************************************************/
/***********TEMPLATE DISPLAY***********************/
/******************************************************/
/*
 * @function pageLoad: takes a pre-formatted page as string and splits it into an array of parts/components. 
 * See post.php in the Default template for an example.
 * @param page: a string returned by file_get_contents.
 * @return array: a nested array of part name => part content 
 */
function translateHomePage()
{
    $pageData = file_get_contents(TEMPLATE_PATH.'home.php');
    echo strtr($pageData, 
        ['{staffpicks_posts}' => translateSearchPage(get_table_posts_staffpicks(5)),
        '{newarticles_posts}' => translateSearchPage(get_table_posts(5))
    ]);   
}
/*
 * @function printSearch: echos a formatted page for a search result.
 * @param postData array: an array of the searched posts data.
 */
function translateSearchPage($postData)
{
    global $user;
    $resultsArray = [];
    if (count($postData) > 0) { //There are multiple posts requested, so display results only.
        $pageData = file_get_contents(TEMPLATE_PATH.'search.php');
        foreach ($postData as $searchedPost) {
            $date = dateToURI($searchedPost['dateCreated']);
            $author = $user->getUser($searchedPost['authorID']);
            $resultsArray[] =  strtr($pageData, 
                ['{post_id}' => $searchedPost['ID'],
                '{post_url}' => SITEURL.$date.$searchedPost['name'],
                '{post_title}' => $searchedPost['title'],
                '{issue_name}' => get_table_issues_byName($searchedPost['issueID'])['name'],
                '{author_url}' => SITEURL."user/".$author['username'],
                '{author_firstName}' => $author['firstName'],
                '{author_lastName}' => $author['lastName'],
                '{post_date}' => date("Y-m-d", strtotime($searchedPost['dateCreated'])),
                '{post_body}' =>substr($searchedPost['body'],0,140) //140 character limit
            ]);
        }
        return implode($resultsArray);
     } else {
        return "Sorry no results were found.";
    }
}
/*
 * @function printPost: echos a formatted page for a post.
 * @param postData array: an array of the searched posts data.
 */
function translatePostsPage($postData)
{
    global $user;
    if (count($postData) == 1) { //There are multiple posts requested, so display results only.
        $pageData = file_get_contents(TEMPLATE_PATH.'posts.php');
        $post = $postData[0];
        $date = dateToURI($post['dateCreated']);
        $author = get_table_users_byID($post['authorID'])[0];
        return strtr($pageData, 
            ['{post_id}' => $post['ID'],
            '{post_url}' => SITEURL.$date.$post['name'],
            '{post_title}' => $post['title'],
            '{issue_name}' => get_table_issues_byName($post['issueID'])['name'],
            '{author_url}' => SITEURL."user/".$author['username'],
            '{author_firstName}' => $author['firstName'],
            '{author_lastName}' => $author['lastName'],
            '{post_date}' => date("Y-m-d", strtotime($post['dateCreated'])),
            '{post_body}' =>$post['body'],
            '{form_saveToken}' => form_saveToken("post_".$post['ID']."_comment"),
            '{comments}' => putPostComments($post['ID'])
        ]);
     } elseif (count($postData>1)){
         return translateSearchPage($postData);
     } else {
        return "Sorry, the post you were looking for was not found.";
    }
}
/*
 * @function putPostComments: a helper function for printPost(), and it formats the comments into the page.
 * @param postID int: the id of the post you want comments for.
 */
function putPostComments($postID)
{
    $comments = get_table_comments_byPostID($postID);
    if ($comments != false && count($comments) > 0) {
        global $user;
        $commentsArray = [];
        foreach ($comments as $comment) {
            $userData = $user->getUser($comment['userID']);
            $commentsArray[] = strtr("<div id='comment_{comment_ID}' class='comment_box'>"
                    . "<div class='comment_header'>Submitted By: {userName} on {date}</div>"
                    . "<div class='comment_content'>{content}</div>"
                    . "</div>", 
                    [ '{comment_ID}' => $comment['ID'],
                        '{userName}' => $userData['firstName']." ".$userData['lastName'],
                        '{date}' => $comment['date'],
                        '{content}' => $comment['content']
                    ]);
        }
        return "<div class='comment_container'><h5>Comments:</h5>".implode(" ",$commentsArray)."</div>";
    } else {
        return "There are no comments for this post yet.";
    }
}
/*
 * @function printUser: echos a formatted page for a user result.
 * @param userData array: an array of the requested user's data.
 */
function translateUserPage($userData)
{
    if ($userData!=false) {
        $pageData = file_get_contents(TEMPLATE_PATH.'user.php');
        $user = $userData;
        return strtr($pageData, 
            [
            '{user_id}' => $user['userID'],
            '{user_firstName}' => $user['firstName'], 
            '{user_lastName}' => $user['lastName'],
            '{user_dateJoined}' => $user['dateJoined'],
            '{user_post_count}' => count(get_table_posts_byUserID($user['userID'], 0)),
            '{user_comments_count}' => count(get_table_comments_byUserID( $user['userID'],0)),
            '{user_email}' => $user['email'],
            '{user_phone}' => $user['phone'], 
            '{user_website}' =>$user['website'], 
            '{user_major}' => $user['major'], 
            '{user_graduationDate}' => $user['graduationDate'],
            '{user_age}' => $user['DOB'],
            '{user_country}' => $user['country'],
            '{bio}' => $user['bio']
        ]);
     } else {
        return "Sorry, we couldn't find the person you were looking for! :(";
    }
}