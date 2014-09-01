<?php
/**
 * Include iFlyChat PHP SDK files
 * Ensure that path to iFlyChat PHP SDK files are correct
 *
**/
require_once(dirname(__FILE__) . '/iflychat_api_settings.php');
require_once(dirname(__FILE__) . '/iflychat_api.php');

/**
 * Use iFlyChat class to render browser based mobile chat application
 *
**/ 
$iflychat = new iFlyChat($iflychat_settings);
$ifly_render_mobile_app = $iflychat->render_chat_mobile();

/**
 * Print iFlyChat user chat code
 *
**/ 
header('Content-type: text/html');
print $ifly_render_mobile_app;

?>