<?php

class iFlyChat
{

    protected $user_details, $settings;

    /*
     * Initialise iFlyChat class with settings and user details array
     */
    function __construct($api_key = '', $app_id = '', $settings = array())
    {

        if (version_compare(phpversion(), '5.4.0', '>=')) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        } else {
            if (session_id() === '') {
                session_start();
            }
        }
        $this->user_details = array(
            'user_name' => NULL,
            'user_id' => 0,
            'user_avatar_url' => FALSE,
            'is_admin' => FALSE,
            'relationships_set' => FALSE,
            'user_profile_url' => FALSE,
            'user_roles' => array(),
            'user_groups' => array(),
            'user_relationships' => array(),
            'all_roles' => array()
        );
        $this->settings = array(
            'base' => '',
            'version' => 'PHP-1.1.1',
            'HOST' => 'http://api.iflychat.com',
            'A_HOST' => 'https://api.iflychat.com',
            'PORT' => 80,
            'A_PORT' => 443,
        );
        $this->settings['api_key'] = $api_key;
        $this->settings['app_id'] = $app_id;
        $this->settings['popup'] = $settings['SHOW_POP_UP_CHAT'];
    }


    /*
     * Getter for base path of website
     */
    protected function getBaseUrl()
    {
        return $this->settings['base'];
    }

    /*
     * Getter for path of iflychat static files
     */
    protected function getPath()
    {
        return $this->settings['path'];
    }

    /*
     * Getter for iFlyChat html code
     */
    public function getHtmlCode()
    {
        $r = '';
        if ($this->settings['popup'] === true) {
            $r .= '<script>var iFlyChatDiv = document.createElement("div");';
            $r .= 'iFlyChatDiv.className = \'iflychat-popup\';';
            $r .= 'document.body.appendChild(iFlyChatDiv);';
            $r .= '</script>';
        }
        $token = $this->getToken();
        if ($token) $r .= '<script> var iflychat_auth_token = "' . $token . '";</script>';
        $r .= '<script>var iFlyChatDiv2 = document.createElement("script");';
        $r .= 'iFlyChatDiv2.src = "//cdn.iflychat.com/js/iflychat-v2.min.js?app_id='. $this->settings['app_id'].'";';
        $r .= 'iFlyChatDiv2.async = true;';
        $r .= 'document.body.appendChild(iFlyChatDiv2);';
        $r .= '</script>';
        return $r;
    }

    protected function t($key)
    {
        return $key;
    }

    /*
     * Method for sending HTTP Request
     */
    private function extendedHttpRequest($url, $data_json)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $res_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = json_decode($result);
        $result->code = $res_code;
        curl_close($ch);

        return $result;
    }

    /*
     * Method for get token
     */
    public function getToken()
    {
        if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
            return $_SESSION['token'];
        } else if ($this->user_details['user_name'] && $this->user_details['user_id']) {
            $json = $this->generateToken($this->user_details);
            return $json->key;
        } else {
            return false;
        }
    }

    /*
     * Get auth (key) from iFlyChat
     */
    public function generateToken($user = array())
    {
        $data = array(
            'user_name' => $user['user_name'],
            'user_id' => $user['user_id'],
            'api_key' => $this->settings['api_key'],
            'user_roles' => ($this->user_details['is_admin']) ? "admin" : "normal",
            'app_id' => $this->settings['app_id'],
            'version' => $this->settings['version']
        );
        if (isset($this->user_details['is_admin'])) {
            $data['user_roles'] = "admin";
            $data['user_site_roles'] = $this->user_details['all_roles'];
        } else {
            $data['user_roles'] = array();
            foreach ($this->user_details['user_roles'] as $rkey => $rvalue) {
                $data['user_roles'][$rkey] = $rvalue;
            }
        }
        if (isset($user['user_avatar_url'])) {
            $data['user_avatar_url'] = $user['user_avatar_url'];
        }
        $data['user_profile_url'] = $user['user_profile_url'];

        if (isset($user['relationships_set'])) {
            if (isset($this->user_details['relationships_set'])) {
                $data['user_list_filter'] = 'friend';
                $data['user_relationships'] = $user['relationships_set'];
            }
        } else {
            $data['user_list_filter'] = 'all';
        }

        if (isset($user['user_groups'])) {
            $data['user_list_filter'] = 'group';
            $data['user_groups'] = array();
            foreach ($user['user_groups'] as $rkey => $rvalue) {
                $data['user_groups'][$rkey] = $rvalue;
            }
        }
        $data = json_encode($data);
        $result = $this->extendedHttpRequest($this->settings['A_HOST'] . ':' . $this->settings['A_PORT'] . '/api/1.1/token/generate', $data);
        if ($result->code == 200) {
            $_SESSION['token'] = $result->key;
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

    public function deleteToken()
    {
        $data = array(
            'api_key' => $this->settings['api_key'],
        );
        $data = json_encode($data);
        if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
            $result = $this->extendedHttpRequest($this->settings['A_HOST'] . ':' . $this->settings['A_PORT'] . '/api/1.1/token/'
                . $_SESSION['token'] . '/delete', $data);
            if ($result->code == 200) {
                unset($_SESSION['token']);
            } else {
                print_r('error in deleting token');
            }
        } else {
            print_r('Token not set');
        }
    }

    public function setUser($user = array())
    {
        $this->user_details['user_name'] = $user['user_name'];
        $this->user_details['user_id'] = $user['user_id'];
        if (isset($user['is_admin'])) {
            $this->user_details['is_admin'] = $user['is_admin'];
        }
        if (isset($user['user_avatar_url'])) {
            $this->user_details['user_avatar_url'] = $user['user_avatar_url'];
        }
        if (isset($user['user_profile_url'])) {
            $this->user_details['user_profile_url'] = $user['user_profile_url'];
        }
        if (isset($user['user_roles'])) {
            $this->user_details['user_roles'] = $user['user_roles'];
        }
        if (isset($user['user_groups'])) {
            $this->user_details['user_groups'] = $user['user_groups'];
        }
        if (isset($user['user_relationships'])) {
            $this->user_details['user_relationships'] = $user['user_relationships'];
        }
    }

    public function setIsAdmin($is_admin = FALSE)
    {
        $this->user_details['is_admin'] = $is_admin;
    }

    public function setAvatarUrl($avatar_url = '')
    {
        $this->user_details['user_avatar_url'] = $avatar_url;
    }

    public function setProfileLink($upl = '')
    {
        $this->user_details['user_profile_url'] = $upl;
    }

    public function setRelationshipSet($relationships_set = FALSE)
    {
        $this->user_details['relationships_set'] = $relationships_set;
    }

    public function setRoomRoles($room_roles = array())
    {
        $this->user_details['user_roles'] = $room_roles;
    }

    public function setUserGroups($user_groups = array())
    {
        $this->user_details['user_groups'] = $user_groups;
    }

    public function setUserRelationships($user_relationships = array())
    {
        $this->user_details['user_relationships'] = $user_relationships;
    }

    public function setAllRoles($all_roles = array())
    {
        $this->user_details['all_roles'] = $all_roles;
    }
}
