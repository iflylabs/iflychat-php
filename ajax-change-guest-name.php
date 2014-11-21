<?php

  require_once(dirname(__FILE__) . '/iflychatsettings.php');
  require_once(dirname(__FILE__) . '/iflychat.php');
  require_once(dirname(__FILE__) . '/iflychatuserdetails.php');
  
  /*
   * Initialise iFlyChatSettings and iFlyChatUserDetails
   * $iflychat_settings = $iFlyChatSettings_obj->iflychat_settings;	
   * $iflychat_userinfo = $iFlyChatUserDetails_obj->getUserDetails();
   * $obj = new iFlyChat($iflychat_settings, $iflychat_userinfo)
   */

  $iflychat_settings = new iFlyChatSettings();
$iflychat_userinfo = new iFlyChatUserDetails();
$iflychat = new iFlyChat($iflychat_settings->iflychat_settings, $iflychat_userinfo->getUserDetails());//, $iflychat_userinfo->getUserDetails());
//  get_currentuserinfo();
  $iflychat->changeGuestName();

?>