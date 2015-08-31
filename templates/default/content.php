<?php
//This returns different page content depending on what is requested.
    if ($typeOfRequest == "home") {
        echo translateHomePage();
    } elseif($typeOfRequest == "signup"){
        $signupID = $URLData['signup'][2];
        $signuptoken = $URLData['signup'][1];
        include TEMPLATE_PATH."signup.php";
    } elseif($typeOfRequest == "contactus") {
        include_once TEMPLATE_PATH.'/contactus.php';
    } elseif ($typeOfRequest == "post") {
    $postName = $URLData['post'][3];
    $postYear = $URLData['post'][0];
    $postMonth = $URLData['post'][1];
    $postDay = $URLData['post'][2];
    $postAction = $URLData['post'][4];
    if (!is_null($postName)&&$postName!="") { //get specific post
        $postData = get_table_posts_byName($postName);
        echo translatePostsPage($postData);
    } else { //get all posts by date       %%%%%%%%Security issues here~~
        $postData = get_table_posts_byDate($postYear, $postMonth, $postDay);
        echo translateSearchPage($postData);
    }
} elseif ($typeOfRequest == "user") {
    $userName = $URLData['user'][1];
    $userAction = $URLData['user'][2];
    $userData = get_table_users_byName($userName)[0];
    if ($userData != false) {
        echo translateUserPage($userData);
    } else {
        echo "Sorry, we couldn't find the person you were looking for! :(";
    }
} elseif ($typeOfRequest == "issue") {
    $issueName = $URLData['issue'][1];
    $issueData = getIssueByName($issueName);
    if (count($issueData)>0) {
        $postsData = searchPosts("issueID = ".$issueData['ID'], 10);
        if ($issueName!=""&&!is_null($issueName)&& count($issueData)>0) {
            echo translateSearchPage($postsData);
        } else {
        echo "Sorry, we couldn't find the issue you're looking for! :(";
    }
    } else {
        echo "Sorry, we couldn't find the issue you're looking for! :(";
    }
} elseif ($typeOfRequest == "staff") {
    $adminPage = $URLData['staff'][1];
    $adminAction = $URLData['staff'][2];
    $adminElement = $URLData['staff'][3];
    include_once TEMPLATE_PATH.'/admin.php';
} elseif ($typeOfRequest == "login") {
    $loginAction = $URLData['login'][1];
    include_once TEMPLATE_PATH.'/login.php';
} else{
    include TEMPLATE_PATH . '404error.php';
}
