<?php
/**
 * Controls user related tasks such as login, registering, etc..
 *
 * @author ShiboTheCoder <shehabattia96@gmail.com>
 */
class User
{
    private $sessionID, $userID = -1, $userRole, $userPermissions;
    public $isLoggedIn = false;
    /*
    * @function __construct: Starts session and calls checkLogin() to check if a user is logged in.
    */
    public function __construct()
    {
        session_set_cookie_params(0, "/", "", false, false);
        session_start();
        $this->sessionID = session_ID();
        $this->isLoggedIn = $this->checkLogin();
        $this->userRole = $this->getUserRoleID($this->userID);
        $this->userPermissions = $this->fetchUserPermissions($this->userRole);
    }
    /*
    * @function checkLogin: checks if a user is logged in via session or cookie, otherwise records him as guest.
    */
    public function checkLogin()
    {
        if (isset($_SESSION['userID'])) { //Determines if a session ID is still active.
            return $this->verifyActiveSessionID();
        } elseif(isset($_COOKIE['rememberMe']) && !is_null($_COOKIE['rememberMe'])) { //If there's a remember me cookie
            return $this->checkRememberMe();
        } else { //Log the guest.
            global $conn;
            $userIP = getenv('REMOTE_ADDR'); //Gets User IP
            $conn->insert("sessions",array(null,$this->sessionID,'-1','0',$userIP,"NOW()",""));
            $_SESSION['userID'] = -1;
            return false;
        }
    }
    /*
    * @function verifyUsernamePassword: Checks that Username and Password match. Also creates session variables.
    * @param username string: username - can be used as username or email.
     * @param password string: password
     * @return array or boolean: Array of SQL rows -> matches, false -> doesn't.
    */
    private function verifyUsernamePassword($username, $password)
    {
        global $conn; 
       $userData = $conn->getRows($conn->query("SELECT * FROM users WHERE (email=?)", array($username)));
        if (count($userData) > 0) { //Username exists
            $pass = $userData[0]['password'];
            if (password_verify($password,$pass)) {
                $this->isLoggedIn = true;
                return $userData;
            } else {
                return false;
            }
         } else {
             return false;
         }
    }
    /*
    * @function checkActiveSession: Checks that a user is still logged in by comparing session ID with db. 
     * Also renews session ID to keep out sniffers.
     * @return boolean: true-> logged in, false-> not logged in.
    */
    private function verifyActiveSessionID() //Checks that that the logged in user is real and not a fake.
    {
        global $conn;
        if (isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];
            $query = $conn->query("Select * FROM sessions WHERE "
                    . "sessionID = ? AND userID = ?", array($this->sessionID, $userID));
            if ($query->rowCount() > 0) {  //It's safe, he's in. But.. Renew Session ID so sniffer can get frustrated:
                $oldSessionID = $this->sessionID;
                $this->userID = $userID;
                session_regenerate_id();
                $this->sessionID = session_id();
                //Save new id:
                $conn->update("sessions", array("sessionID", "lastOnline"), array($this->sessionID, "NOW()"), 
                    array("sessionID", "userID"), array($oldSessionID, $userID));
                return ($_SESSION['userID'] != -1)?true:false;
            } else {
                unset($_SESSION['userID']);
                return false; //He's not logged in anymore.
            }
        } else { //If no userID Session, then he's not logged in.
            return false;
        }
    }
    /*
    * @function checkRememberMe: If a remember me cookie is set, log in with it.
     * @return boolean: true -> logged in. false -> not logged in.
    */
    private function checkRememberMe()
    {
        global $conn;
        if (isset($_COOKIE['rememberMe']) && isset($_COOKIE['userID'])) {
            $rememberToken = $_COOKIE['rememberMe'];
            $userID = $_COOKIE['userID'];
            $query = $conn->query("Select * FROM sessions WHERE rememberMe = ? "
                    . "AND userID = ?", array($rememberToken, $userID));
            if ($query->rowCount() > 0){  //It's safe, he's in. But.. Renew Session ID so sniffer can get frustrated:
                session_regenerate_id();
                $this->sessionID = session_id();
                $this->userID = $userID;
                $_SESSION['userID'] = $this->userID;
                $query = $conn->query("UPDATE sessions SET  `sessionID` = ?, "
                        . "lastOnline = NOW() WHERE rememberMe = ? AND userID = ?;",
                        array($this->sessionID, $rememberToken, $userID));
                return true;
            } else {
                return false; //He's not logged in anymore.
            }
        } else { //If no RememberMe cookie, then he's not logged in.
            return false;
        }
    }
    /*
    * @function login: Creates session variables and cookies..
    * @param username string: username - can be used as username or email.
     * @param password string: password
     * @param rememberMe boolean: if true, saves a rememberMe cookie.
     * @return string: Sucess or error message.
    */
    public function login($username, $password, $rememberMe = false)
    {
        if ($this->isLoggedIn) { //Already logged in.
            return "You're already logged in.";
         } else {
             unset($_SESSION['userID']);
             $isloggedin = $this->verifyUsernamePassword($username, $password);
             if ($isloggedin != false) {  //Password matches.
                 return $this->createLoginSession($isloggedin, $rememberMe);
           } else {  //Check if username/email even exists.
               return "Wrong Username/Password.";
           }
        }
    }
    /*
    * @function createLoginSession: Creates session variables and cookies.
    * @param userData array: Array with SQL fetch results.
     * @param rememberMe boolean: if true, saves a rememberMe cookie.
     * @return string: Sucess message.
    */
    private function createLoginSession($userData, $rememberMe = false)
    {
        global $conn;
        $this->userID = $userData[0]['userID'];
        $_SESSION['userID'] = $userData[0]['userID'];
        $userIP = getenv('REMOTE_ADDR');  //Gets User IP
        $rememberToken = md5(uniqid(rand(), true));
        $conn->query("INSERT INTO sessions VALUES (NULL, ?, ?, ?, ?, NOW(), ?);", 
                  array($this->sessionID, $this->userID, '1', $userIP, ($rememberMe? $rememberToken:"")));
        //$_COOKIE['hi'] = 1;
        return sprintf("Success. Welcome back %s.", $userData[0]['firstName']);
    }
    
    /*
    * @function logout: Destroys session and updates db session entry.
     * @return string: success or error message.
    */
    public function logout()
    {
        global $conn;
        if ($this->isLoggedIn) {
            $update = $conn->update("sessions", array("sessionID", "lastOnline"), array("0", "NOW()"), 
                    array("sessionID", "userID"), array($this->sessionID, $this->userID));
            if (!$update) {
                //Something went wrong, or it was deleted before.. Something's fishy.
                return "Something went wrong.. Please try again.";
            } else {
                session_destroy();
                setcookie("rememberME","", time()-3600);
                setcookie("userID","", time()-3600);
                return "You're now logged out.";
            }
        }
    }
    /**************************************************************************************/
    /**************************OTHER USER FUNCTIONS**********************************/
    /**************************************************************************************/
    /*
    * @function getUserRoleID: gets ID of role of the user from Roles_Users table.
    */
   public function getUser($userID = null)
   {
       global $conn;
       if ($userID =="" &&!$this->isLoggedIn) {
           return false;
       } else {
            $userID = $this->userID;
        }
        $user = $conn->getRows($conn->query("SELECT * FROM users "
                . "WHERE userID = ? or username LIKE '%".$userID."%'", array($userID)));
        return (count($user)>0?$user[0]:false);
   }
    /*
    * @function getUserRoleID: gets ID of role of the user from Roles_Users table.
    */
    private function getUserRoleID($userID = null)
    {
        global $conn;
        if (is_null($userID)) {
            $userID = $this->userID;
        }
        $userRole = $conn->getRows($conn->query("SELECT * FROM users "
                . "WHERE userID = ?", array($userID)));
        return (count($userRole)>0?$userRole[0]['roleID']:false);
    }
    /*
    * @function setUserRole: sets user role to Roles_Users table.
     * @returns boolean: true -> success, false -> error.
    */
    public function setUserRole($userID, $roleID)
    {
        global $conn;
        $checkRole = $this->getUserRoleID();
        if ($checkRole != false) { //User already assigned a role, so we need to update it.
            $do = $conn->update("roles_users", array("roleID"), array($roleID), array("userID"),array($userID));
        } else { //insert a new row
            $do = $conn->insert("roles_users",array($userID,$roleID));
        }
        return $do?true:false;
    }
    /*
    * @function getRole: gets name or ID of role from Roles table.
     * @param roleNameOrID mix => string -> searches name like role. => int -> searched ID of role => string "all" returns
     * the whole table.
     * @returns array: roleName, roleID or capabilities as keys
    */
    private function getRole($roleNameOrID = null)
    {
        global $conn;
        if (is_null($roleNameOrID)) {
            $roleNameOrID = $this->getUserRoleID();
        }
        if ($roleNameOrID == "all") {
            return $conn->getRows($conn->query('SELECT * FROM roles'));
        } else {
        $role = $conn->getRows($conn->query('SELECT * FROM roles WHERE roleID = ? '
                . 'OR roleName Like "%'.$roleNameOrID.'%"',array($roleNameOrID)));
        return count($role)>0?$role[0]:false;
        }
    }
    /*
    * @function setRole: Adds or updates a role in the Roles table
     * @param roleID int: roleID in Roles table.
     * @param roleName string: name of role.
     * @param capabilities string: a / delimited string with all possible user access permissions
     * @returns boolean: true -> success, false -> error.
    */
    public function setRole($roleID = null, $roleName = "", $capabilities = "")
    {
        global $conn;
        $checkRole = $this->getRole($roleID);
        if ($checkRole != false) { //User already assigned a role, so we need to update it.
            $do = $conn->update("roles", array("roleName", "capabilities"), array($roleName, $capabilities), array("roleID"),array($roleID));
        } else { //insert a new row
            $do = $conn->insert("roles",array(null, $roleName,$capabilities));
        }
        return $do?true:false;
    }
    /*
    * @function getPermissions: gets permissions for the user role using role.
     * @param roleNameOrID mix => string -> searches name like role. => int -> searched ID of role => string "all" returns
     * the whole table.
     * @returns array: roleName, roleID or capabilities as keys
    */
    private function fetchUserPermissions($roleNameOrID = null)
    {
        global $conn;
        if (is_null($roleNameOrID)) {
            $roleNameOrID = $this->getUserRoleID();
        }
        $permissions = $conn->getRows($conn->query('SELECT capabilities FROM roles WHERE roleID = ? '
                . 'OR roleName Like "%'.$roleNameOrID.'%"',array($roleNameOrID)));
        return count($permissions)>0?explode('/', $permissions[0]['capabilities']):false;
    }
    public function getUserPermissions()
    {
        return $this->userPermissions;
    }
}

