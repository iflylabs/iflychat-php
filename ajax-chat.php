<?php
/**
 * Include iFlyChat PHP SDK files
 * Ensure that path to iFlyChat PHP SDK files are correct
 *
**/
require_once(dirname(__FILE__) . '/iflychatsettings.php');
require_once(dirname(__FILE__) . '/iflychatuserdetails.php');
require_once(dirname(__FILE__) . '/iflychat.php');

/** 
 *
 * Details of current logged-in user
 * Retreive from database or PHP session
 *
**/
// $user_details = array(
//   'name' => 'admin', 
//   'id' => '1', 
//   'is_admin' => TRUE, 
//   'avatar_url' => '/path/to/my_picture.jpg', 
//   'upl' => 'link_to_profile_of_current_user.php',
//   'room_roles' => array(), 
//   'user_groups' => array(),
// );

/**
 *
 * Pass empty array if the user is not logged-in/unregistered/guest (anonymous user)
 *
 *
**/ 

//$user_details = array();

/**
 * Use iFlyChat class to render user chat code
 *
**/
global $iflychat_userinfo;
$iflychat_settings = new iFlyChatSettings();
$iflychat = new iFlyChat($iflychat_settings->iflychat_settings, $iflychat_userinfo->getUserDetails());
$ifly_render_chat_code = $iflychat->renderChatAjax();

/**
 * Print iFlyChat user chat code
 *
**/ 
header('Content-type: application/json');
print $ifly_render_chat_code;

?>