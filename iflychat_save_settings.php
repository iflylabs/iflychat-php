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
 * Save iFlyChat settings. Call this function every time setting is updated.
 *
**/ 
$iflychat = new iFlyChat($iflychat_settings);
$result = $iflychat->iflychat_update_settings();

print_r($result->code);

?>