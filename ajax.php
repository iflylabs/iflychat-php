<?php

require_once('./iflychat_api.php');
$user_details = array('name' => 'admin', 'id' => '1', 'is_admin' => TRUE, 'avatar_url' => '/path/to/my_picture.jpg', 'upl' => 'link_to_profile_of_current_user.php');
//$user_details = array();
$ifly_code = iflychat_render_chat_ajax($user_details);
print $ifly_code;
?>