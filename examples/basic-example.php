<?php
/**
 * Include iFlyChat PHP Client
 * Ensure that path to iflychat.php is correct
 *
**/

require_once('../lib/iflychat.php');

/**
 * Get your APP ID and API Key from https://iflychat.com
**/

const APP_ID = 'YOUR_APP_ID';
const API_KEY =  'YOUR_API_KEY';

$iflychat = new \Iflylabs\iFlyChat(APP_ID, API_KEY);

$iflychat_code = $iflychat->getHtmlCode();

?>

<html>
<head>
</head>
<body>
<h1>How to include iFlyChat in your PHP website?</h1>


<!-- iFlyChat Engine Code Begins -->
<?php print $iflychat_code; ?>
<!-- iFlyChat Engine Code Ends -->


</body>
</html>
