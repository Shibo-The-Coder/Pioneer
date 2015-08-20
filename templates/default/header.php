<?php 
include_once BASEDIR.'template_functions.php';
/*Parse the URL for request data:*/
$URLData = getURLData();
/*Return the type of request determined by getURLData*/
$typeOfRequest = ($URLData!=false)?array_keys($URLData)[0]:false;
$pageName = ($typeOfRequest!=false) ? " || ".  ucfirst($typeOfRequest): " || Error";
/*Config*/
define("ADMIN_PAGE_FILE", TEMPLATE_PATH."admin.php");
define("ISSUE_PAGE_FILE", TEMPLATE_PATH."issue.php");
define("SEARCH_PAGE_FILE", TEMPLATE_PATH."search.php");
define("USER_PAGE_FILE", TEMPLATE_PATH."user.php");
define("LOGIN_PAGE_FILE", TEMPLATE_PATH."login.php");

/*
 * CLEAN UP LATER!!
 */
define("MAIN_STYLESHEET", SITEURL.TEMPLATEDIR."style.css");
define("HOME_PAGE_URL", SITEURL);
define("ADMIN_PAGE_URL", SITEURL."staff/");
define("ISSUE_PAGE_URL", SITEURL."issue/");
define("SEARCH_PAGE_URL", SITEURL."search/");
define("USER_PAGE_URL", SITEURL."user/");
define("LOGIN_PAGE_URL", SITEURL."login/");
define("CONTACTUS_PAGE_URL", SITEURL."contactus/");
?>
<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="utf-8" />
    <title><?php echo SITE_NAME . $pageName; ?></title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="<?php echo MAIN_STYLESHEET; ?>" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>