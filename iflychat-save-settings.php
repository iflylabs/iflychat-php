<?php
/**
 * Include iFlyChat PHP SDK files
 * Ensure that path to iFlyChat PHP SDK files are correct
 *
**/
require_once(dirname(__FILE__) . '/iflychatsettings.php');
require_once(dirname(__FILE__) . '/iflychat.php');
require_once(dirname(__FILE__) . '/iflychatuserdetails.php');


/**
 *
 * Save iFlyChat settings. Call this function every time setting is updated.
 *
**/ 
global $iflychat_userinfo;
$iflychat_settings = new iFlyChatSettings();
$iflychat = new iFlyChat($iflychat_settings->iflychat_settings, $iflychat_userinfo->getUserDetails());
$result = $iflychat->updateSettings();

if($result->code == 200) {
	echo "iFlyChat settings have been successfully updated.";
}
else {
	echo "Error occurred while updating iFlyChat settings. Try again.</br>";
	echo "Response Code: {$result->code}. Error : {$result->error}</br>";
	echo "Please contact iFlyChat team at iflychat.com.";
}
//print_r($result->code);

?>