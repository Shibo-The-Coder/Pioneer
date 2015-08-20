<?php
/**
 * Loads defined template or displays an error, and calls __autoload.
 *
 * @author ShiboTheCoder <shehabattia96@gmail.com>
 * 
 * @global $conn - Object with connection to db.
 */

require_once 'general_functions.php';
require_once 'database_functions.php';
require_once 'security_functions.php';
__autoload(); //Loads all classes.
$conn = new Communicate(); //Creates a PDO connection to the db.
$user = new User(); //Creates a user object
getSiteInfo(); //Fetches basic site information from db.
define("TEMPLATEDIR", "/templates/".TEMPLATE."/");
define( "TEMPLATE_PATH", BASEDIR.TEMPLATEDIR); //TEMPLATE is a constant defined by general_function.php::getSiteInfo();
if (is_dir(TEMPLATE_PATH)) {
    include TEMPLATE_PATH.'index.php';
} else {
    elog("Template not found!");
    die("Template not found.");
}
