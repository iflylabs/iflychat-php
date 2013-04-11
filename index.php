<?php
require_once('./iflychat_api.php');
$user_details = array('name' => 'admin', 'id' => '1', 'is_admin' => TRUE, 'avatar_url' => '/path/to/my_picture.jpg');
//$user_details = array();
$ifly_code = iflychat_render_chat($user_details);
echo '<html><head><script src = "https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>'; 
print $ifly_code;
echo '</head><body><h1>TEST.......................................................................................................................</h1><br>';

?>

<style>
.drupalchat-embed-chatroom-content {height: 550px !important;}
</style>
 
<script type="text/javascript">
Drupal.settings.drupalchat.embed = "1";
Drupal.settings.drupalchat.ur_hy = "1";
Drupal.settings.drupalchat.embed_msg = 'Type your message here. Press Enter to send.';
Drupal.settings.drupalchat.embed_online_user_text = 'Online Users';
</script>
 
<div id="drupalchat-embed-chatroom-0" class="drupalchat-embed-chatroom-container"></div>

<?php
echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1>TEST</h1></body></html>';
?>
