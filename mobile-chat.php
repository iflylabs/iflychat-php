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
 * Use iFlyChat class to render browser based mobile chat application
 *
**/ 
global $iflychat_userinfo;
$iflychat_settings = new iFlyChatSettings();
$iflychat = new iFlyChat($iflychat_settings->iflychat_settings, $iflychat_userinfo->getUserDetails());
$ifly_render_mobile_app = $iflychat->renderChatMobile();

/**
 * Print iFlyChat user chat code
 *
**/ 
header('Content-type: text/html');
print $ifly_render_mobile_app;

?>