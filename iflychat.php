<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class iFlyChat {

  protected $timers, $defset, $user_details, $settings;
  
  /*
   * Initialise iFlyChat class with settings and user details array
   */
  function __construct($settings = array(), $user_detail = array()) {
    
    if( version_compare(phpversion(), '5.4.0', '>=') ) {
      if(session_status() === PHP_SESSION_NONE) {
        session_start();
      }
    } else {
      if(session_id() === '') {
        session_start();
      }
    }
    
    $this->defset = array(
      'name' => NULL,
      'id' => 0,
      'avatar_url' => FALSE,
      'is_admin' => FALSE,
      'relationships_set' => FALSE,
      'upl' => FALSE,
      'room_roles' => array(),
      'user_groups' => array(),
    );

    $this->settings = array(
      'base' => '',
      'version' => 'PHP-1.1.1',
      'HOST' => 'http://api.iflychat.com',
      'A_HOST' => 'https://api.iflychat.com',
      'PORT' => 80,
      'A_PORT' => 443,
    );

    $this->settings = array_merge($this->settings, $settings);
  //  print_r($user_detail);
    $this->user_details = array_merge($this->defset, $user_detail);
  //  print_r($this->user_details);
  }

  /*
   * Getter for base path of website
   */
  protected function getBaseUrl() {

      return $this->settings['base'];

  }

  /*
   * Getter for path of iflychat static files
   */
  protected function getPath() {

        return $this->settings['path'];
    }

    /*
     * Getter for iFlyChat html code
     */
    public function getHtmlCode($set = array())
    {
        $json['is_admin'] = $this->user_details['is_admin'];
        $json = (array)$this->getKey();
        $r = '<script>';
        if ($this->getUserId() && $this->getUserName()) {
            $r .= 'var iflychat_auth_url = "' . $this->getBaseUrl() . $this->settings['ajax_file'] . '";';
        }
        $r .= '</script>';
        if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
            $r .= '<script> var iflychat_auth_token = "' . $_SESSION['token'] . '";</script>';
        }
        $r .= '<div id="app"></div>';
        $r .= '<script type="text/javascript" src="//web.iflychatdev.com:9000/js/bundle.js?cid='
            . $this->settings['app_id'] . '"</script>';
//    }
        return $r;
    }

    /*
     * Renders iFlyChat by returning array of user info and key
     */
    public function renderChatAjax($set = array())
    {

        //  $this->user_details = array_merge($this->defset, $set);
        if ($this->settings['api_key'] == '' || empty($this->settings['api_key'])) {
            return;
        } else {
            $json = (array)$this->getKey();

//      $json = array_merge($this->user_details, $json);

      return json_encode($json);
    }
  }

    protected function t($key)
    {
        return $key;
    }
    /*
     * Method for sending HTTP Request
     */
    protected function extendedHttpRequest($url, $data_json)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $res_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = json_decode($result);
        $result->code = $res_code;
        curl_close($ch);

        return $result;
    }

    /*
     * Get auth (key, css) from iFlyChat
     */
    private function getKey()
    {
        $id = $this->getUserId();
        $name = $this->getUserName();
        if ($id && $name) {
            $data = array(
                'uname' => $name,
                'uid' => $id,
                'api_key' => $this->settings['api_key'],
                'image_path' => $this->getPath() . 'themes/' . $this->settings['theme'] . '/images',
                'isLog' => TRUE,
                'whichTheme' => 'blue',
                'enableStatus' => TRUE,
                'role' => ($this->user_details['is_admin']) ? "admin" : "normal",
                'validState' => array('available', 'offline', 'busy', 'idle'),
            );
        }

        if ($this->user_details['is_admin']) {
            $data['role'] = "admin";
            $data['allRoles'] = $this->user_details['all_roles'];
        } else {
            $data['role'] = array();
            foreach ($this->user_details['room_roles'] as $rkey => $rvalue) {
                $data['role'][$rkey] = $rvalue;
            }
        }

        if ($this->settings['user_picture']) {
            $data['up'] = $this->getUserPictureUrl();
        }

        $data['upl'] = $this->getUserProfileLink();

        if ($this->settings['enable_user_relationships']) {
            if (isset($this->user_details['relationships_set'])) {
                $data['rel'] = '1';
                $data['valid_uids'] = $this->user_details['relationships_set'];
            }
        } else {
            $data['rel'] = '0';
        }

        if ($this->settings['enable_user_group_filtering']) {
            $data['rel'] = '0';
            $data['valid_groups'] = array();
            foreach ($this->user_details['user_groups'] as $rkey => $rvalue) {
                $data['valid_groups'][$rkey] = $rvalue;
            }
        }
        $data = json_encode($data);
        $result = $this->extendedHttpRequest($this->settings['HOST'] . ':' . $this->settings['PORT'] . '/api/1.0/token/generate', $data);
        if ($result->code == 200) {
            if ($this->getUserId() && $this->getUserName()) {
                $_SESSION['token'] = $result->key;
            };
            return $result;
        } else {
            return array();
        }
    }

    /*
     * Check if server is SSL secure
     */
    private function checkSSL()
    {
        return (isset($_SERVER) && isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != "off"));
    }

    /*
     * Get url for user avatar
     */
    private function getUserPictureUrl()
    {
        if (!($this->user_details['avatar_url'])) {
            $this->user_details['agetUserId()vatar_url'] = $this->getPath() . 'themes/' . $this->settings['theme'] . '/images/default_avatar.png';
        }
        return $this->user_details['avatar_url'];
    }

    /*
     * Get url for user profile
     */
    private function getUserProfileLink()
    {
        if (!($this->user_details['upl'])) {
            $this->user_details['upl'] = 'javascript:void(0)';
        }
        return $this->user_details['upl'];
    }

    /*
     * Get ID for current User (Guest/Registered)
     */
    private function getUserId()
    {
        if (($this->user_details['id'])) {
            $_SESSION['iflychat_id'] = $this->user_details['id'];
            return $this->user_details['id'];
        } else {
            return false;
        }
    }

    /*
     * Get name for current User (Guest/Registered)
     */
    private function getUserName()
    {
        if (isset($this->user_details['name'])) {
            $_SESSION['iflychat_name'] = $this->user_details['name'];
            return $this->user_details['name'];
        } else {
            return false;
        }
    }

    public function getMessageThread($id1 = "1", $id2 = "2")
    {
        //global $_iflychat, $iflychat;
        $data = json_encode(array(
            'uid1' => $id1,
            'uid2' => $id2,
            'api_key' => $this->settings['api_key'],
        ));
        $result = $this->extendedHttpRequest($this->settings['A_HOST'] . ':' . $this->settings['A_PORT'] . '/q/', $data);
        $q = json_decode($result->data);
        return $q;
    }

    public function getMessageInbox($id1 = "1")
    {
        //global $_iflychat, $iflychat;
        $data = json_encode(array(
            'uid' => $id1,
            'api_key' => $this->settings['api_key'],
        ));
        $result = $this->extendedHttpRequest($this->settings['A_HOST'] . ':' . $this->settings['A_PORT'] . '/r/', $data);
        $q = json_decode($result->data);
        return $q;
    }

    public function renderChatMobile()
    {

        $data = array('settings' => array());
        $data['settings']['authUrl'] = $this->getBaseUrl() . $this->settings['ajax_file'];
        $data['settings']['host'] = (($this->checkSSL()) ? ($this->settings['A_HOST']) : ($this->settings['HOST']));
        $data['settings']['port'] = (($this->checkSSL()) ? ($this->settings['A_PORT']) : ($this->settings['PORT']));
        $data = json_encode($data);

        $result = $this->extendedHttpRequest($this->settings['A_HOST'] . ':' . $this->settings['A_PORT'] . '/m/v1/app/', $data);

        return $result->data;
    }
}
