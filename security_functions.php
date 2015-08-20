<?php
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