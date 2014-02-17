<?php
/**
 * Include iFlyChat PHP SDK files
 * Ensure that path to iFlyChat PHP SDK files are correct
 *
**/
require_once(dirname(__FILE__) . '/iflychat_api_settings.php');
require_once(dirname(__FILE__) . '/iflychat_api.php');

/** 
 *
 * Details of current logged-in user
 * Retreive from database or PHP session
 *
**/
$user_details = array(
  'name' => 'admin', 
  'id' => '1', 
  'is_admin' => TRUE, 
  'avatar_url' => '/path/to/my_picture.jpg', 
  'upl' => 'link_to_profile_of_current_user.php'
);

/**
 *
 * Pass empty array if the user is not logged-in/unregistered/guest (anonymous user)
 *
 *
**/ 

$user_details = array();

/**
 * Use iFlyChat class to render user chat code
 *
**/ 
$iflychat = new iFlyChat($iflychat_settings);
$ifly_render_chat_code = $iflychat->render_chat_ajax($user_details);

/**
 * Print iFlyChat user chat code
 *
**/ 
header('Content-type: application/json');
print $ifly_render_chat_code;

?>