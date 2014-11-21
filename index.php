<?php
/**
 * Include iFlyChat PHP SDK files
 * Ensure that path to iFlyChat PHP SDK files are correct
 *
**/
require_once('./iflychatsettings.php');
require_once('./iflychatuserdetails.php');
require_once('./iflychat.php');

/**
 *
 * Use iFlyChat class get_html_code() function to render chat HTML code.
 * This code should be printed on all pages where you want chat to be present.
 *
**/
global $iflychat_userinfo;
$iflychat_settings = new iFlyChatSettings();
$iflychat = new iFlyChat($iflychat_settings->iflychat_settings, $iflychat_userinfo->getUserDetails());
$ifly_html_code = $iflychat->getHtmlCode();

?>
<html>
<head>
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body>
<h1>How to include iFlyChat code in a sample PHP page?</h1>
<!-- iFlyChat code begins -->
<?php print $ifly_html_code; ?>
<!-- iFlyChat code ends -->
<br>


<!-- iFlyChat Embed Code (will work only available in premium plans)  begins -->
<style>
.drupalchat-embed-chatroom-content {height: 550px !important;}
</style>
<script type="text/javascript">
if(typeof iflyembed == "undefined") {
  iflyembed = {};
  iflyembed.settings = {};
  iflyembed.settings.ifly = {};
}
iflyembed.settings.ifly.embed = "1";
iflyembed.settings.ifly.ur_hy = "1";
iflyembed.settings.ifly.embed_msg = 'Type your message here. Press Enter to send.';
iflyembed.settings.ifly.embed_online_user_text = 'Online Users';
</script> 
<div id="drupalchat-embed-chatroom-0" class="drupalchat-embed-chatroom-container"></div>
<!-- iFlyChat Embed Code (will work only available in premium plans)  ends -->

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</body>
</html>

