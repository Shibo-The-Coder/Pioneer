<?php
require_once './config.php';
/*
 * @function __autoload: includes classes in the CLASS_PATH.
 */
function __autoload($classname = null)
{
    if (is_null($classname)) {
        $dir = array_diff(scandir(CLASSES_PATH), array('..', '.'));
        foreach ($dir as $inc) {
            require_once CLASSES_PATH . $inc;
        }
    } else {
        require_once CLASSES_PATH . $classname . ".php";
    }
}
/*
 * @function elog: Writes to error.txt a timestamped error message.
 */
function elog($message)
{
    error_log(gmdate("Y-m-d H:i:s", time()).": ".$message."\n",3,"./errors.txt");
}
/*
 * @function getSiteInfo: Asks database for contents of Config table and defines them as constants.
 */
function getSiteInfo()
{
    global $conn;
    $query = $conn->getRows($conn->query("SELECT * FROM config"));
    foreach ($query as $option) {
        define($option['name'],$option['value']);
    }
}
/**************************************************************************************/
/**************************PAGE CONTROL FUNCTIONS**********************************/
/**************************************************************************************/

/**************************************************************************************/
/**************************FORMS FUNCTIONS**********************************/
/**************************************************************************************/
/*
 * Tokenizing Forms to protect from XSS attacks:
 */
function form_saveToken($name = "") //Sets token to database.
{ 
    $token = md5(uniqid(rand(),true));
    echo "<input type='hidden' name = 'token' value = '$token' />";
    global $conn;
    $conn->insert("forms", array($name, $token));   
}
function form_getToken($name = "", $token = "")
{
    global $conn;
    $query = $conn->query("Select * FROM forms WHERE token = ? AND name = ?", array($token, $name));
    return ($query->rowCount() > 0) ? true : false;
}
/**************************************************************************************/
/**************************URL PARSE FUNCTIONS**********************************/
/**************************************************************************************/

/*
 * @function getURI: retrieves Server's Request URI and cleans it.
 * @return string: URI.
 */
function getURI()
{
    $requesturi = BASENAME == "/" ? trim($_SERVER['REQUEST_URI'],"/") :  str_replace(BASENAME, "",$_SERVER['REQUEST_URI']);
    return explode("/", $requesturi);
}
/*
 * @function getRequest: returns the value of a request.
 * @return string: value of a request.
 */
function getRequest($key = null)
{
    return (!is_null($key) && isset($_REQUEST[$key])) ? $_REQUEST[$key] :null;
}
/*
 * @function getURLData: logically determines how the URL should be parsed: using permalink strtok or GET.
 * @return array: an array of content type and values stored in URL.
 */
function getURLData()
{
    return count($_GET) > 0 ? getURLTypeFromGET() : getURLTypeFromURI();
}
/*
 * @function getURLTypeFromGET: returns the most probable request type from GET requests in the URL.
 * @return array: type of content to expect and data it's holding.
 */
function getURLTypeFromGET()
{
    $values = [];
    $requests = $_REQUEST;
    if (isset($requests['post'])||isset($requests['year'])||isset($requests['month'])||isset($requests['day'])) {
        return ["post"=>[getRequest("year"),getRequest("month"),getRequest("day"),getRequest("post"),getRequest("action")]];
    } elseif (isset($requests['user'])) {
        return ['user'=>[getRequest("user"), getRequest("action")]];
    } elseif (isset($requests['search'])) {
        return ['search'=>[getRequest("search"), getRequest("action")]];
    } elseif (isset($requests['staff'])) {
        return ['staff'=>[getRequest("staff"), getRequest("action"), getRequest("element")]];
    } elseif (isset($requests['login'])) {
        return ['login'=>[getRequest("login"), getRequest("action")]];
    } elseif(isset($requests['contactus'])) {
        return ['contactus'=>[getRequest("contactus"), getRequest("action")]];
    } elseif(isset($requests['signup'])) {
        return ['signup'=>[getRequest("signup"), getRequest("action")]];
    }else {
        return false;
    }
}
/*
 * @function getURLTypeFromURI: returns the most probable request type from breaking down the URI.
 * @return array: type of content to expect and data it's holding.
 */
function getURLTypeFromURI()
{
    $url_parts = getURI();
    switch ($url_parts[0]) {
        case 'search':
            return ['search' =>$url_parts];
        case 'user':
            while (count($url_parts) < 3) { //Normalize the array
                $url_parts[] = null;
            }
            return ['user' =>$url_parts];
        case 'staff':
            while (count($url_parts) < 4) { //Normalize the array
                $url_parts[] = null;
            }
            return ['staff' =>$url_parts];
        case '' :
            return ['home' =>$url_parts];
        case 'index.php' :
            return ['home' =>$url_parts];
        case 'login.php' :
            return ['login' =>$url_parts];
        case 'login' :
            return ['login' =>$url_parts];
        case 'issue' :
            while (count($url_parts) < 2) { //Normalize the array
                $url_parts[] = null;
            }
            return ['issue' => $url_parts];
        case 'contactus' :
            return ['contactus' => $url_parts];
        case 'signup' :
            return ['signup' => $url_parts];
        default :
            break;
    }
    if (preg_match("/^[0-9]*$/", $url_parts[0])&&$url_parts[0]!="") { //Most likely a date for finding a post.
        while (count($url_parts) < 5) { //Normalize the array
            $url_parts[] = null;
        }
        return ['post' =>$url_parts];
    } else {
        return false;
    }
}
function dateToURI($date)
{
    return str_replace("-", "/", substr($date, 0,10))."/";
}
