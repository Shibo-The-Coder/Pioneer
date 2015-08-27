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

//Not my script, but using it for now, will change it later~~
function security_file_check(){
    try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['upfile']['error']) ||
        is_array($_FILES['upfile']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['upfile']['error'] value.
    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here. 
    if ($_FILES['upfile']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['upfile']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    }

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    if (!move_uploaded_file(
        $_FILES['upfile']['tmp_name'],
        sprintf('./uploads/%s.%s',
            sha1_file($_FILES['upfile']['tmp_name']),
            $ext
        )
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
    return true;

} catch (RuntimeException $e) {
    return false;
}
}