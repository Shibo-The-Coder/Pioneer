<?php
//escape post and get requests
foreach (array_keys($_GET) as $key) {
    $_GET[$key] = htmlspecialchars($_GET[$key]);
}
foreach (array_keys($_POST) as $key) {
    $_POST[$key] = htmlspecialchars($_POST[$key]);
}
function security_password_encrypt($password)
{
    return password_hash($password, PASSWORD_DEFAULT); //Low security, but good for now~
}
/*
 * @function getRequestTypeActions: Specifies built-in possible actions for each request type.
 * @param request_type String: a specific request type.
 * @return array : all possible actions or one specified.
 */
function getRequestTypeActions($request_type = "")
{
    $actions = ['post' => explode('viewPosts/editPosts/newPosts/deletePosts/flagPosts/'
                    . 'approvePosts/newComment/editComment/deleteComment/flagComment/approveComments','/'),
                'user' => explode('addUsers/editUsers/removeUsers/banUsers/settings','/'),
                'admin' => explode('adminDash/editDash/moderate', '/')];
    return ($request_type=="") ? $actions : (isset($actions[$request_type]) ? $actions[$request_type] : false);
}
/*
 * @function verifyRequestWithUserRole: verifies if the user is allowed to do an action.
 * @return boolean: If permission exists -> true, else -> false;
 */
function verifyRequestWithUserRole($request_action = "")
{
    global $user;
    //var_dump($user->getUserPermissions());
    $compare = array_search($request_action, $user->getUserPermissions());
    return ($compare!==false)?true:false;
}

function file_upload($inputname, $dir, $formtoken = ""){
    $files = "";
    $error = false;
    if (isset($_FILES[$inputname])){
        $numfiles = count($_FILES[$inputname]['name']);
        for ($x = 0; $x < $numfiles; $x++) {
                $file_tempdir = $_FILES[$inputname]['tmp_name'][$x];
                if ($file_tempdir == "") {
                    return false;
                }
                $file_name = $_FILES[$inputname]['name'][$x];
                $target_dir = UPLOAD_PATH."/".$dir."/";
                $fileinfo = pathinfo($file_name);
                $filename = sha1_file($file_tempdir).".".$fileinfo['extension'];
                $files = $filename . ",". $files;   
                $target_file = $target_dir . $formtoken . "_" . $filename;
                if (!move_uploaded_file($file_tempdir, $target_file)) {
                    return false;
                }
         }
            return $files;
        } else {
            return false;
        }
}