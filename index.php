<?php
require_once('./iflychat_api.php');
$user_details = array('name' => 'admin', 'id' => '1', 'is_admin' => TRUE,);
$ifly_code = iflychat_render_chat($user_details);
echo '<html><head><script src = "https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>'; 
print $ifly_code;
echo '</head><body><h1>TEST.......................................................................................................................</h1><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><h1>TEST</h1></body></html>';
?>
