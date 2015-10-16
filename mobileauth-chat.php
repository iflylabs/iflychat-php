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
 * Use iFlyChat class to render mobile auth
 *
**/ 
if(isset($_POST['username']) && isset($_POST['password'])){
if(userAuthenticate($_POST['username'],$_POST['password'])){

global $iflychat_userinfo;
$iflychat_settings = new iFlyChatSettings();
$iflychat = new iFlyChat($iflychat_settings->iflychat_settings, $iflychat_userinfo->getUserDetails());
$ifly_render_chat_code = $iflychat->renderChatAjax();
header('Content-type: application/json');
print $ifly_render_chat_code;
}
}
else{
	print_r('Access Denied');
}

/**
 * Print iFlyChat user chat code
 *
**/ 
function userAuthenticate($username,$password){
/**
  *$username = 'admin123';
  *$password = 'admin123';
  *details of logged-in user
  * authenticate the username and password using the database
**/ 
return true;
}


?>