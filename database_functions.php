<?php
/*
 * Functions in this file pull information from the database's tables.
 * Not all functions that query info from the database are in this file, however.
 * -----------------------------
 *| Every table should have:  |
   | add                                 |
   | update                            |
   | delete                              |
   | getbyID  and/or              |
   | getbyName                     |
 * -----------------------------
 *  */
require_once 'config.php';
//Formats a query statement then executes it using the Communicate class's method query().
function prepare_query($statement = "", $array = [], $limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
    global $conn;
    return $conn->query($statement.prepare_query_limitOffset($limit, $offset), $array);
}
//Appends limit and offset to query statement.
function prepare_query_limitOffset($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
    //If limit = 0, return ALL rows.
    $logic_limit = $limit==0? 18446744073 : $limit;
    return " LIMIT ".$logic_limit." OFFSET ".$offset ;
}
//Fetches rows after query is executed.
function getRows($query = false)
{
    global $conn;
    $rows = $query!=false? $conn->getRows($query) : false;
    return ($rows != false && $rows > 0) ? $rows : false; 
}
function countRows($rows = false)
{
    return $rows != false?count($rows):0;
}
/**************************************************************************************/
 /**************************POSTS FUNCTIONS**********************************/
 /**************************************************************************************/
/*
 * add_table_posts - inserts a post into database table "posts". Must be formatted to match database table.
 * @param multiple - These match the columns in the table.
 * @return boolean - true = success, false = error writing to db.
 */
function add_table_posts($authorID = 0, $name = "", $title = "", $body = "", $dateCreated = "NOW()", $dateModified = "NOW()", $publish = "draft", $issueID = 1)
{
    global $conn;
    $ID = null;
    $post = array(
        $ID,
        $authorID,
        $name,
        $title,
        $body,
        $dateCreated,
        $dateModified,
        $publish,
        $issueID
    );
    $query = $conn->insert("posts", $post);
    return $query;
}

/*
 * update_table_posts_byID - Updated a post in the table Posts by using its ID.
 * @param ID int - ID of row to update.
 * @param fields array - An array of column names formatted to match database table..
 * @param fieldValues array - an array corresponding to array fields, of values to update.
 * @return boolean - true = success, false = error writing to db.
 */
function update_table_posts_byID($ID = null, $fields = array(), $fieldValues = array())
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->update("posts", $fields, $fieldValues, "ID", $ID);
        return $query;
    } else {
        return false;
    }
}
/*
 * delete_table_posts_byID - Delete a post in the table Posts by using its ID.
 * @param type int - ID.
 * @return boolean - true = success, false = error writing to db.
 */
function delete_table_posts_byID( $ID = null)
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->delete("posts", "ID", $ID);
    } else {
        $query = false;
    }
    return $query;
}
//Gets all table rows
function get_table_posts($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
     return getRows(prepare_query('SELECT * FROM posts', array(), $limit, $offset));
}
//Gets all table rows
function get_table_posts_staffpicks($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
     return getRows(prepare_query('SELECT * FROM posts WHERE staffpick="yes"', array(), $limit, $offset));
}
//Gets all table rows
function get_table_posts_order_dateDESC($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
     return getRows(prepare_query('SELECT * FROM posts ORDER BY dateCreated DESC', array(), $limit, $offset));
}//Gets all table rows
function get_table_posts_order_dateASC($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
     return getRows(prepare_query('SELECT * FROM posts ORDER BY dateCreated ASC', array(), $limit, $offset));
}
/*
* table_posts_getPostbyID - fetches a post from the table "Posts" by ID of post.
* @param postID int - ID of post you want to get.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_posts_byID($postID = null)
{
    if (is_numeric($postID) && !is_null($postID)) {
        return getRows(prepare_query('SELECT * FROM posts WHERE ID = ?', array($postID)));
    }
    else {
        return false;
    }
}
/*
* table_posts_byName - fetches a post from the table "Posts" by exact Name of post.
* @param postName string - Unique name of post you want to get.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_posts_byName($postName = null)
{
    if ($postName !="" && !is_null($postName)) {
        return getRows(prepare_query('SELECT * FROM posts WHERE name = "' . $postName . '"'));
    }
    else {
        return false;
    }
}
/*
* get_table_posts_byUserID - fetches posts from the table "Posts" that have a UserID.
* @param userID int - ID of post you want to get.
* @param limit,offset int - how many result you want back
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_posts_byUserID($userID = null, $limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
    if (is_numeric($userID) && !is_null($userID)) {
        return getRows(prepare_query('SELECT * FROM posts WHERE userID = ?"', array($userID), $limit, $offset));
    }
    else {
        return false;
    }
}
/*
* get_table_posts_byDate - fetches a post from database using its date.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_posts_byDate($year = null, $month = null, $day = null, $array = [], $limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
    if (!is_null($day)&&$day!="") {
        return getRows(prepare_query("SELECT * FROM posts WHERE"
                ." DAYOFMONTH(dateCreated) = $day AND"
                . " MONTH(dateCreated) = $month AND"
                . " YEAR(dateCreated) = $year", $array, $limit, $offset));
    } elseif (!is_null($month)&&$month!="") {
        return getRows(prepare_query("SELECT * FROM posts WHERE"
                . " MONTH(dateCreated) = $month AND"
                . " YEAR(dateCreated) = $year", $array, $limit, $offset));
    } elseif (!is_null($year)&&$year!="") {
        return getRows(prepare_query("SELECT * FROM posts WHERE"
                . " YEAR(dateCreated) = $year", $array, $limit, $offset));
    } else {
        return false;
    }
}

//*******Security threat because of all columns search? Look into it later..//
function searchPostsColumns($keyword)
{
    global $conn;
    $getcolumns = $conn->query("Describe posts", array());
    $columns = $getcolumns->fetchAll(PDO::FETCH_COLUMN);
    $column_names = '';
    foreach ($columns as $column) {
        $column_names = $column_names .' '. $column . ' like "%' . $keyword . '%" OR ';
    }
    return getRows(prepare_query('SELECT * FROM posts WHERE '.substr($column_names, 0, -4), array()));
}

/**************************************************************************************/
 /**************************COMMENTS FUNCTIONS**********************************/
 /**************************************************************************************/
/*
 * add_table_comments - inserts a comment into database table "comments". Must be formatted to match database table.
 * @param multiple - These match the columns in the table.
 * @return boolean - true = success, false = error writing to db.
 */
function add_table_comments($userID = null, $postID = null, $content = "", $date = "NOW()", $approved = "no")
{
    global $conn;
    $ID = null;
    $comment = array(
        $ID, 
        $userID, 
        $postID, 
        $content, 
        $date, 
        $approved
    );
    $query = $conn->insert("comments", $comment);
    return $query;
}

/*
 * update_table_comments_byID - Updated a comment in the table Comments by using its ID.
 * @param ID int - ID of row to update.
 * @param fields array - An array of column names formatted to match database table..
 * @param fieldValues array - an array corresponding to array fields, of values to update.
 * @return boolean - true = success, false = error writing to db.
 */
function update_table_comments_byID($ID = null, $fields = array(), $fieldValues = array())
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->update("comments", $fields, $fieldValues, "ID", $ID);
        return $query;
    } else {
        return false;
    }
}
/*
 * delete_table_comments_byID - Delete a comment in the table Comments by using its ID.
 * @param type int - ID.
 * @return boolean - true = success, false = error writing to db.
 */
function delete_table_comments_byID( $ID = null)
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->delete("comments", "ID", $ID);
    } else {
        $query = false;
    }
    return $query;
}
//Gets all table rows
function get_table_comments($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
     return getRows(prepare_query('SELECT * FROM comments', array(), $limit, $offset));
}
/*
* get_table_comments_byID - fetches a comment from the table "Comments" by ID of comment.
* @param commentID int - ID of comment you want to get.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_comments_byID($commentID = null)
{
    if (is_numeric($commentID) && !is_null($commentID)) {
        return getRows(prepare_query('SELECT * FROM comments WHERE ID = ?', array($commentID)));
    }
    else {
        return false;
    }
}
/*
* get_table_comments_byUserID - fetches comments from the table "Comments" that have a UserID.
* @param userID int - ID of comment you want to get.
* @param limit,offset int - how many result you want back
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_comments_byUserID($userID = null, $limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
    if (is_numeric($userID) && !is_null($userID)) {
        return getRows(prepare_query('SELECT * FROM comments WHERE userID = ?"', array($userID), $limit, $offset));
    }
    else {
        return false;
    }
}
/*
* get_table_comments_byPostID - fetches comments from the table "Comments" that have a UserID.
* @param userID int - ID of comment you want to get.
* @param limit,offset int - how many result you want back
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_comments_byPostID($postID = null, $limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
    if (is_numeric($postID) && !is_null($postID)) {
        return getRows(prepare_query('SELECT * FROM comments WHERE postID = ?"', array($postID), $limit, $offset));
    }
    else {
        return false;
    }
}
/*
* get_table_comments_byDate - fetches a comment from database using its date.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_comments_byDate($year = null, $month = null, $day = null, $array = [], $limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
    if (!is_null($day)&&$day!="") {
        return getRows(prepare_query("SELECT * FROM comments WHERE"
                ." DAYOFMONTH(dateCreated) = $day AND"
                . " MONTH(dateCreated) = $month AND"
                . " YEAR(dateCreated) = $year", $array, $limit, $offset));
    } elseif (!is_null($month)&&$month!="") {
        return getRows(prepare_query("SELECT * FROM comments WHERE"
                . " MONTH(dateCreated) = $month AND"
                . " YEAR(dateCreated) = $year", $array, $limit, $offset));
    } elseif (!is_null($year)&&$year!="") {
        return getRows(prepare_query("SELECT * FROM comments WHERE"
                . " YEAR(dateCreated) = $year", $array, $limit, $offset));
    } else {
        return false;
    }
}

/**************************************************************************************/
 /**************************ISSUES FUNCTIONS**********************************/
 /**************************************************************************************/
/*
 * add_table_issues - inserts a issue into database table "issues". Must be formatted to match database table.
 * @param multiple - These match the columns in the table.
 * @return boolean - true = success, false = error writing to db.
 */
function add_table_issues($name = "", $description = "", $date = "NOW()", $publish = "draft")
{
    global $conn;
    $ID = null;
    $issue = array(
        $ID,
        $name,
        $description,
        $date,
        $publish
    );
    $query = $conn->insert("issues", $issue);
    return $query;
}

/*
 * update_table_issues_byID - Updated a issue in the table Issues by using its ID.
 * @param ID int - ID of row to update.
 * @param fields array - An array of column names formatted to match database table..
 * @param fieldValues array - an array corresponding to array fields, of values to update.
 * @return boolean - true = success, false = error writing to db.
 */
function update_table_issues_byID($ID = null, $fields = array(), $fieldValues = array())
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->update("issues", $fields, $fieldValues, "ID", $ID);
        return $query;
    } else {
        return false;
    }
}
/*
 * delete_table_issues_byID - Delete a issue in the table Issues by using its ID.
 * @param type int - ID.
 * @return boolean - true = success, false = error writing to db.
 */
function delete_table_issues_byID( $ID = null)
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->delete("issues", "ID", $ID);
    } else {
        $query = false;
    }
    return $query;
}
//Gets all table rows
function get_table_issues($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
     return getRows(prepare_query('SELECT * FROM issues', array(), $limit, $offset));
}
/*
* get_table_issues_byID - fetches a issue from the table "Issues" by ID of issue.
* @param issueID int - ID of issue you want to get.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_issues_byID($issueID = null)
{
    if (is_numeric($issueID) && !is_null($issueID)) {
        return getRows(prepare_query('SELECT * FROM issues WHERE ID = ?', array($issueID)));
    }
    else {
        return false;
    }
}
/*
* table_issues_byName - fetches a issue from the table "Issues" by exact Name of issue.
* @param issueName string - Unique name of issue you want to get.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_issues_byName($issueName = null)
{
    if ($issueName !="" && !is_null($issueName)) {
        return getRows(prepare_query('SELECT * FROM issues WHERE name = "' . $issueName . '"'));
    }
    else {
        return false;
    }
}
/*
* get_table_issues_byDate - fetches a issue from database using its date.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_issues_byDate($year = null, $month = null, $day = null, $array = [], $limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
    if (!is_null($day)&&$day!="") {
        return getRows(prepare_query("SELECT * FROM issues WHERE"
                ." DAYOFMONTH(dateCreated) = $day AND"
                . " MONTH(dateCreated) = $month AND"
                . " YEAR(dateCreated) = $year", $array, $limit, $offset));
    } elseif (!is_null($month)&&$month!="") {
        return getRows(prepare_query("SELECT * FROM issues WHERE"
                . " MONTH(dateCreated) = $month AND"
                . " YEAR(dateCreated) = $year", $array, $limit, $offset));
    } elseif (!is_null($year)&&$year!="") {
        return getRows(prepare_query("SELECT * FROM issues WHERE"
                . " YEAR(dateCreated) = $year", $array, $limit, $offset));
    } else {
        return false;
    }
}

/**************************************************************************************/
 /**************************USERS FUNCTIONS**********************************/
 /**************************************************************************************/
/*
 * add_table_users - inserts a user into database table "users". Must be formatted to match database table.
 * @param multiple - These match the columns in the table.
 * @return boolean - true = success, false = error writing to db.
 */
function add_table_users($username = "", $firstName = "", $lastName = "", $email = "", $password = "", $phone = "", $website = "", 
        $major = "",  $graduationDate = "NOW()", $DOB = "NOW()", $country = "", $bio = "", $dateJoined = "NOW()", $roleID = "-1")
{
    global $conn;
    $ID = null;
    $user = array(
        $ID,
        $username , 
        $firstName ,
        $lastName , 
        $email , 
        security_password_encrypt($password), 
        $phone , 
        $website , 
        $major ,  
        $graduationDate, 
        $DOB , 
        $country , 
        $bio , 
        $dateJoined, 
        $roleID
    );
    $query = $conn->insert("users", $user);
    return $query;
}

/*
 * update_table_users_byID - Updated a user in the table Users by using its ID.
 * @param ID int - ID of row to update.
 * @param fields array - An array of column names formatted to match database table..
 * @param fieldValues array - an array corresponding to array fields, of values to update.
 * @return boolean - true = success, false = error writing to db.
 */
function update_table_users_byID($ID = null, $fields = array(), $fieldValues = array())
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        //if password given, hash it.
        $ifpassword = array_search("password", $fields);
        if ($ifpassword!=false) {
            $fieldValues[$ifpassword] = security_password_encrypt($fieldValues[$ifpassword]);
        }
        $query = $conn->update("users", $fields, $fieldValues, "userID", $ID);
        return $query;
    } else {
        return false;
    }
}
/*
 * delete_table_users_byID - Delete a user in the table Users by using its ID.
 * @param type int - ID.
 * @return boolean - true = success, false = error writing to db.
 */
function delete_table_users_byID( $ID = null)
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->delete("users", "userID", $ID);
    } else {
        $query = false;
    }
    return $query;
}
//Gets all table rows
function get_table_users($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
     return getRows(prepare_query('SELECT * FROM users', array(), $limit, $offset));
}
/*
* get_table_users_byID - fetches a user from the table "Users" by ID of user.
* @param userID int - ID of user you want to get.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_users_byID($userID = null)
{
    if (is_numeric($userID) && !is_null($userID)) {
        return getRows(prepare_query('SELECT * FROM users WHERE userID = ?', array($userID)));
    }
    else {
        return false;
    }
}
/*
* get_table_users_byName - fetches a user from the table "Users" by Name of user.
* @param userID int - ID of user you want to get.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_users_byName($username = "")
{
    if ($username != "") {
        return getRows(prepare_query('SELECT * FROM users WHERE username = ?', array($username)));
    }
    else {
        return false;
    }
}
/**************************************************************************************/
 /**************************ROLES FUNCTIONS**********************************/
 /**************************************************************************************/
/*
 * add_table_roles - inserts a role into database table "roles". Must be formatted to match database table.
 * @param multiple - These match the columns in the table.
 * @return boolean - true = success, false = error writing to db.
 */
function add_table_roles($roleName = "", $capabilities = "")
{
    global $conn;
    $ID = null;
    $role = array(
        $ID,
        $roleName,
        $capabilities
    );
    $query = $conn->insert("roles", $role);
    return $query;
}

/*
 * update_table_roles_byID - Updated a role in the table Roles by using its ID.
 * @param ID int - ID of row to update.
 * @param fields array - An array of column names formatted to match database table..
 * @param fieldValues array - an array corresponding to array fields, of values to update.
 * @return boolean - true = success, false = error writing to db.
 */
function update_table_roles_byID($ID = null, $fields = array(), $fieldValues = array())
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->update("roles", $fields, $fieldValues, "roleID", $ID);
        return $query;
    } else {
        return false;
    }
}
/*
 * delete_table_roles_byID - Delete a role in the table Roles by using its ID.
 * @param type int - ID.
 * @return boolean - true = success, false = error writing to db.
 */
function delete_table_roles_byID( $ID = null)
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->delete("roles", "roleID", $ID);
    } else {
        $query = false;
    }
    return $query;
}
/*
* get_table_roles_byID - fetches a role from the table "Roles" by ID of role.
* @param roleID int - ID of role you want to get.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_roles_byID($roleID = null)
{
    if (is_numeric($roleID) && !is_null($roleID)) {
        return getRows(prepare_query('SELECT * FROM roles WHERE roleID = ?', array($roleID)));
    }
    else {
        return false;
    }
}
/**************************************************************************************/
 /**************************POSTS_COLLAB FUNCTIONS**********************************/
 /**************************************************************************************/
/*
 * add_table_posts_collab - inserts a  into database table "posts_collab". Must be formatted to match database table.
 * @param multiple - These match the columns in the table.
 * @return boolean - true = success, false = error writing to db.
 */
function add_table_posts_collab($postID = null, $userIDs = "", $status = "draft", $notes = "", $startDate = "NOW()", $dueDate = "NOW()")
{
    global $conn;
    $ID = null;
    $posts_collab = array(
        $ID,
        $postID,
        $userIDs,
        $status,
        $notes,
        $startDate,
        $dueDate
    );
    $query = $conn->insert("posts_collab", $posts_collab);
    return $query;
}

/*
 * update_table_posts_collab_byID - Updated a role in the table Posts_collab by using its ID.
 * @param ID int - ID of row to update.
 * @param fields array - An array of column names formatted to match database table..
 * @param fieldValues array - an array corresponding to array fields, of values to update.
 * @return boolean - true = success, false = error writing to db.
 */
function update_table_posts_collab_byID($ID = null, $fields = array(), $fieldValues = array())
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->update("posts_collab", $fields, $fieldValues, "ID", $ID);
        return $query;
    } else {
        return false;
    }
}
/*
 * delete_table_posts_collab_byID - Delete a  in the table Posts_collab by using its ID.
 * @param type int - ID.
 * @return boolean - true = success, false = error writing to db.
 */
function delete_table_posts_collab_byID( $ID = null)
{
    global $conn;
    if (!is_null($ID)&&is_numeric($ID)) {
        $query = $conn->delete("posts_collab", "ID", $ID);
    } else {
        $query = false;
    }
    return $query;
}
//Gets all table rows
function get_table_posts_collab($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
     return getRows(prepare_query('SELECT * FROM posts_collab', array(), $limit, $offset));
}
//Gets all table rows
function get_table_posts_collab_order_dateDESC($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
     return getRows(prepare_query('SELECT * FROM posts_collab ORDER BY LayoutEditorDueDate DESC', array(), $limit, $offset));
}//Gets all table rows
function get_table_posts_collab_order_dateASC($limit = DEFAULT_LIMIT, $offset = DEFAULT_OFFSET)
{
     return getRows(prepare_query('SELECT * FROM posts_collab ORDER BY LayoutEditorDueDate ASC', array(), $limit, $offset));
}
/*
* get_table_posts_collab_byID - fetches a  from the table "Posts_collab" by ID of .
* @param ID int - ID of  you want to get.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_posts_collab_byID($ID = null)
{
    if (is_numeric($ID) && !is_null($ID)) {
        return getRows(prepare_query('SELECT * FROM posts_collab WHERE ID = ?', array($ID)));
    }
    else {
        return false;
    }
}
/*
* get_table_posts_collab_byPostID - fetches a  from the table "Posts_collab" by postID of .
* @param ID int - ID of  you want to get.
* @return array - a PDO array with column names as keys. $result['column']; // returns value of column
*/
function get_table_posts_collab_byPostID($ID = null)
{
    if (is_numeric($ID) && !is_null($ID)) {
        return getRows(prepare_query('SELECT * FROM posts_collab WHERE postID = ?', array($ID)));
    }
    else {
        return false;
    }
}