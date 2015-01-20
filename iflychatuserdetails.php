<?php
	

	class iFlyChatUserDetails
	{
		private $user_details = array();

		public function __construct($name = NULL, $id = 0) {
			$this->user_details['name']				= $name;
			$this->user_details['id'] 				= $id;
			$this->user_details['is_admin'] 			= FALSE;
			$this->user_details['avatar_url'] 		= '';
			$this->user_details['upl'] 				= '';
			$this->user_details['relationships_set'] 	= FALSE;
			$this->user_details['room_roles'] 		= array();
			$this->user_details['user_groups'] 		= array();
		}

		public function getUserDetails() {
	    	
	   		return $this->user_details;
		}

		public function setIsAdmin($is_admin = FALSE) {
			$this->user_details['is_admin'] = $is_admin;
		}

		public function setAvatarUrl($avatar_url = '') {
			$this->user_details['avatar_url'] = $avatar_url;
		}

		public function setProfileLink($upl = '') {
			$this->user_details['upl'] = $upl;
		}
		
		public function setRelationshipSet($relationships_set = FALSE) {
			$this->user_details['relationships_set'] = $relationships_set;
		}

		public function setRoomRoles($room_roles = array()) {
			$this->user_details['room_roles'] = $room_roles;
		}

		public function setUserGroups($user_groups = array()) {
			$this->user_details['user_groups'] = $user_groups;
		}
		
		 public function setUserRoles($all_roles = array()){
            		$this->user_details['all_roles'] = $all_roles;
        }
	}

	/**
	 * Details of current logged-in user
   * Retreive from database or PHP session
	 */
	
  //Uncomment the code below to pass the details of logged-in user
  
  /*
  
  global $iflychat_userinfo;
	$iflychat_userinfo = new iFlyChatUserDetails('admin', 1);
	$iflychat_userinfo->setIsAdmin(TRUE);
	$iflychat_userinfo->setAvatarUrl('https://iflychat.com/sites/all/modules/drupalchat/themes/light/images/default_avatar.png');
	$iflychat_userinfo->setProfileLink('/user.php?id=1');
  $iflychat_userinfo->setRoomRoles(array());
	$iflychat_userinfo->setRelationshipSet(FALSE);
	$iflychat_userinfo->setUserRoles(array('1'=>'admin'));
  
  */

	/**
	 * Pass no parameters if the user is NOT logged-in/unregistered/guest (anonymous user)
	 * 
	 */
  
  $iflychat_userinfo = new iFlyChatUserDetails();
?>
