<?php

class iFlyChat {tyq1OrjSHU14b6b4f6w3eOpRoHjj4wvBx_Pkpha9grkW8287

  private $timers, $defset, $user_details, $settings;
  
  function __construct($settings = array()) {
    
    $this->defset = array(
      'name' => NULL,
	    'id' => 0,
	    'avatar_url' => FALSE,
	    'is_admin' => FALSE,
      'relationships_set' => FALSE,
      'upl' => FALSE,
      'role' => array(),
    );
    
    $this->settings = array(
      'base' => '',
      'version' => 'PHP-1.1.0',
      'HOST' => 'http://api.iflychat.com',
      'A_HOST' => 'https://api.iflychat.com',
      'PORT' => 80,
      'A_PORT' => 443,
    );
    
    $this->settings = array_merge($this->settings, $settings);
  
  }
  
  
  public function get_html_code($set = array()) {
    
    $this->user_details = array_merge($this->defset, $set);
  
    $json['is_admin'] = $this->user_details['is_admin'];
    //$my_settings['session_key'] = $json['key'];
    //global $_iflychat, $iflychat;
    $r = '<script type="text/javascript">';
    /*
    if($_GET['iflychat_update']=='true' && $_GET['iflychat_save']=='yes') {
      $ures = iflychat_update_settings();
	    if($ures->code == 200) {
	      $r .= 'alert(\'iFlyChat Settings updated successfully.\');';
	    }
	    else {
	      $r .= 'alert("'."Unable to connect to iFlyChat server. Error code - " . $ures->code . ". Error message - " . $ures->error .'.");';
	    }
    }
    */
    if(!$this->settings['load_async']) {
      $json = (array)$this->iflychat_get_key($this->user_details);
    }
    else {
      $json['key'] = '';
    }
  
    $json = array_merge($this->user_details, $json);

    $r .= 'Drupal={};Drupal.settings={};Drupal.settings.drupalchat=' . json_encode($this->iflychat_init($json))  . ';</script>';
    
    if(!$this->settings['load_async']) {
      $r .= '<link type="text/css" rel="stylesheet" href="' . (($this->check_ssl()) ? $this->settings['A_HOST'] : $this->settings['HOST']) .  '/i/' . $json['css'] . '/cache.css" media="all" />';
      $r .= '<script type="text/javascript" src="' . (($this->check_ssl()) ? $this->settings['A_HOST'] : $this->settings['HOST']) .  '/j/cache.js"></script>';
      $r .= '<script type="text/javascript" src="' . $this->settings['path'] .  'js/ba-emotify.js"></script>';
      $r .= '<script type="text/javascript" src="' . $this->settings['path'] .  'js/jquery.titlealert.min.js"></script>';
      $r .= '<script src="'. (($this->check_ssl()) ? $this->settings['A_HOST'] : $this->settings['HOST']) .  '/h/' . $json['css'] . '/cache.js" type=\'text/javascript\'></script>';
    }
    else {
      $r .= '<script type="text/javascript" src="' . $this->settings['path'] .  'js/iflychat.js"></script>';
    }
    return $r;
  }
  
  public function render_chat_ajax($set = array()) {
    
    $this->user_details = array_merge($this->defset, $set);

    $json = (array)$this->iflychat_get_key($this->user_details);

    $json = array_merge($this->user_details, $json);

    return json_encode($json);
  }
  
  public function iflychat_init($jsset) {
    //global $_iflychat, $iflychat;
    $my_settings = array(
      //'uid' => $jsset['uid'],
      //'username' => $jsset['name'],
      'current_timestamp' => time(),
      'polling_method' => "3",
      'pollUrl' => " ",
      'sendUrl' => " ",
      'statusUrl' => " ",
      'status' => "1",
      'goOnline' => 'Go Online',
      'goIdle' => 'Go Idle',
      'newMessage' => 'New chat message!',
      'images' => $this->settings['path'] . 'themes/' . $this->settings['theme'] . '/images/',
      'sound' => $this->settings['path'] . 'swf/sound.swf',
      'noUsers' => "<div class=\"item-list\"><ul><li class=\"drupalchatnousers even first last\">No users online</li></ul></div>",
      'smileyURL' => $this->settings['path'] . 'smileys/very_emotional_emoticons-png/png-32x32/',
      'addUrl' => " ",
      'notificationSound' => "1",
      'basePath' => "/",
      'useStopWordList' => $this->settings['use_stop_word_list'],
      'blockHL' => $this->settings['stop_links'],
      'allowAnonHL' => ($this->settings['allow_anon_links'])?'1':'2',
      'iup' => ($this->settings['user_picture'])?'1':'2',
      'theme' => $this->settings['theme'],
      //'admin' => $jsset['is_admin']?'1':'0',
      //'session_key' => $jsset['key'],
    );
	  
    if(!$this->settings['load_async']) {
      $my_settings['uid'] = $jsset['uid'];
      $my_settings['username'] = $jsset['name'];
      $my_settings['admin'] = $jsset['is_admin']?'1':'0';
      $my_settings['session_key'] = $jsset['key'];
    }
        
	  if($this->settings['use_stop_word_list'] != '1') {
	    $my_settings['stopWordList'] = $this->settings['stop_word_list'];
	  }

    if($this->settings['load_async']) {
      $my_settings['exurl'] = $this->settings['path'] .  $this->settings['ajax_file'];
    }
  
    if($this->settings['minimize_chat_user_list']) {
      $my_settings['open_chatlist_default'] = "2";
    }
    else {
      $my_settings['open_chatlist_default'] = "1";
    }

    $my_settings['guestPrefix'] = ($this->settings['anon_prefix'] . " ");
    $my_settings['mobileWebUrl'] = $this->settings['path'] .  $this->settings['mobile_file'];
    $my_settings['chat_type'] = $this->settings['chat_type'];
  

	  if($this->check_ssl()) {
      $my_settings['external_host'] = $this->settings['A_HOST'];
      $my_settings['external_port'] = $this->settings['A_PORT'];
      $my_settings['external_a_host'] = $this->settings['A_HOST'];
      $my_settings['external_a_port'] = $this->settings['A_PORT'];		
	  }
    else {
      $my_settings['external_host'] = $this->settings['HOST'];
      $my_settings['external_port'] = $this->settings['PORT'];
      $my_settings['external_a_host'] = $this->settings['HOST'];
      $my_settings['external_a_port'] = $this->settings['PORT'];
	  }	
	  if($this->settings['user_picture'] && !$this->settings['load_async']) {
		  if(isset($jsset['avatar_url'])) {
	      $my_settings['up'] = $jsset['avatar_url'];
        }
        else {
         $my_settings['up'] = $this->settings['path'] . 'themes/' . $this->settings['theme'] . '/images/default_avatar.png';
        }
    }
    
    $my_settings['default_up'] = $this->settings['path'] . 'themes/' . $this->settings['theme'] . '/images/default_avatar.png';
    $my_settings['default_cr'] = $this->settings['path'] . 'themes/' . $this->settings['theme'] . '/images/default_room.png';
    
    if(!$this->settings['load_async']) {
      if(isset($jsset['upl']) && $jsset['upl']) {
        $my_settings['upl'] = $jsset['upl'];
      }
      else {
        $my_settings['upl'] = 'user/' . $jsset['uid'];
      }  
	  }
  
    return $my_settings;
    
  }
  
  private function iflychat_timer_read($name) {
    //global $this->timers;

    if (isset($this->timers[$name]['start'])) {
      $stop = microtime(TRUE);
      $diff = round(($stop - $this->timers[$name]['start']) * 1000, 2);

      if (isset($this->timers[$name]['time'])) {
        $diff += $this->timers[$name]['time'];
      }
      return $diff;
    }
    return $this->timers[$name]['time'];
  } 

  private function iflychat_timer_start($name) {
    //global $this->timers;

    $this->timers[$name]['start'] = microtime(TRUE);
    $this->timers[$name]['count'] = isset($this->timers[$name]['count']) ? ++$this->timers[$name]['count'] : 1;
  }
  
  private function iflychat_extended_http_request($url, array $options = array()) {
    $result = new stdClass();
    // Parse the URL and make sure we can handle the schema.
    $uri = @parse_url($url);
    if ($uri == FALSE) {
      $result->error = 'unable to parse URL';
      $result->code = -1001;
      return $result;
    }

    if (!isset($uri['scheme'])) {
      $result->error = 'missing schema';
      $result->code = -1002;
      return $result;
    }

    $this->iflychat_timer_start(__FUNCTION__);

    // Merge the default options.
    $options += array(
      'headers' => array(), 
      'method' => 'GET', 
      'data' => NULL, 
      'max_redirects' => 3, 
      'timeout' => 30.0, 
      'context' => NULL,
    );

    // Merge the default headers.
    $options['headers'] += array(
      'User-Agent' => 'Drupal (+http://drupal.org/)',
    );

    // stream_socket_client() requires timeout to be a float.
    $options['timeout'] = (float) $options['timeout'];

    // Use a proxy if one is defined and the host is not on the excluded list.
    $proxy_server = '';
    if ($proxy_server && _drupal_http_use_proxy($uri['host'])) {
      // Set the scheme so we open a socket to the proxy server.
      $uri['scheme'] = 'proxy';
      // Set the path to be the full URL.
      $uri['path'] = $url;
      // Since the URL is passed as the path, we won't use the parsed query.
      unset($uri['query']);

      // Add in username and password to Proxy-Authorization header if needed.
      if ($proxy_username = '') {
        $proxy_password = '';
        $options['headers']['Proxy-Authorization'] = 'Basic ' . base64_encode($proxy_username . (!empty($proxy_password) ? ":" . $proxy_password : ''));
      }
      // Some proxies reject requests with any User-Agent headers, while others
      // require a specific one.
      $proxy_user_agent = '';
      // The default value matches neither condition.
      if ($proxy_user_agent === NULL) {
        unset($options['headers']['User-Agent']);
      }
      elseif ($proxy_user_agent) {
        $options['headers']['User-Agent'] = $proxy_user_agent;
      }
    }

    switch ($uri['scheme']) {
      case 'proxy':
        // Make the socket connection to a proxy server.
        $socket = 'tcp://' . $proxy_server . ':' . 8080;
        // The Host header still needs to match the real request.
        $options['headers']['Host'] = $uri['host'];
        $options['headers']['Host'] .= isset($uri['port']) && $uri['port'] != 80 ? ':' . $uri['port'] : '';
        break;

      case 'http':
      case 'feed':
        $port = isset($uri['port']) ? $uri['port'] : 80;
        $socket = 'tcp://' . $uri['host'] . ':' . $port;
        // RFC 2616: "non-standard ports MUST, default ports MAY be included".
        // We don't add the standard port to prevent from breaking rewrite rules
        // checking the host that do not take into account the port number.
        $options['headers']['Host'] = $uri['host'] . ($port != 80 ? ':' . $port : '');
        break;

      case 'https':
        // Note: Only works when PHP is compiled with OpenSSL support.
        $port = isset($uri['port']) ? $uri['port'] : 443;
        $socket = 'ssl://' . $uri['host'] . ':' . $port;
        $options['headers']['Host'] = $uri['host'] . ($port != 443 ? ':' . $port : '');
        break;

      default:
        $result->error = 'invalid schema ' . $uri['scheme'];
        $result->code = -1003;
        return $result;
    }

    if (empty($options['context'])) {
      $fp = @stream_socket_client($socket, $errno, $errstr, $options['timeout']);
    }
    else {
      // Create a stream with context. Allows verification of a SSL certificate.
      $fp = @stream_socket_client($socket, $errno, $errstr, $options['timeout'], STREAM_CLIENT_CONNECT, $options['context']);
    }

    // Make sure the socket opened properly.
    if (!$fp) {
      // When a network error occurs, we use a negative number so it does not
      // clash with the HTTP status codes.
      $result->code = -$errno;
      $result->error = trim($errstr) ? trim($errstr) : t('Error opening socket @socket', array('@socket' => $socket));

      // Mark that this request failed. This will trigger a check of the web
      // server's ability to make outgoing HTTP requests the next time that
      // requirements checking is performed.
      // See system_requirements().
      //variable_set('drupal_http_request_fails', TRUE);

      return $result;
    }

    // Construct the path to act on.
    $path = isset($uri['path']) ? $uri['path'] : '/';
    if (isset($uri['query'])) {
      $path .= '?' . $uri['query'];
    }

    // Only add Content-Length if we actually have any content or if it is a POST
    // or PUT request. Some non-standard servers get confused by Content-Length in
    // at least HEAD/GET requests, and Squid always requires Content-Length in
    // POST/PUT requests.
    $content_length = strlen($options['data']);
    if ($content_length > 0 || $options['method'] == 'POST' || $options['method'] == 'PUT') {
      $options['headers']['Content-Length'] = $content_length;
    }

    // If the server URL has a user then attempt to use basic authentication.
    if (isset($uri['user'])) {
      $options['headers']['Authorization'] = 'Basic ' . base64_encode($uri['user'] . (isset($uri['pass']) ? ':' . $uri['pass'] : ''));
    }

    // If the database prefix is being used by SimpleTest to run the tests in a copied
    // database then set the user-agent header to the database prefix so that any
    // calls to other Drupal pages will run the SimpleTest prefixed database. The
    // user-agent is used to ensure that multiple testing sessions running at the
    // same time won't interfere with each other as they would if the database
    // prefix were stored statically in a file or database variable.
    $test_info = &$GLOBALS['drupal_test_info'];
    if (!empty($test_info['test_run_id'])) {
      $options['headers']['User-Agent'] = drupal_generate_test_ua($test_info['test_run_id']);
    }

    $request = $options['method'] . ' ' . $path . " HTTP/1.0\r\n";
    foreach ($options['headers'] as $name => $value) {
      $request .= $name . ': ' . trim($value) . "\r\n";
    }
    $request .= "\r\n" . $options['data'];
    $result->request = $request;
    // Calculate how much time is left of the original timeout value.
    $timeout = $options['timeout'] - $this->iflychat_timer_read(__FUNCTION__) / 1000;
    if ($timeout > 0) {
      stream_set_timeout($fp, floor($timeout), floor(1000000 * fmod($timeout, 1)));
      fwrite($fp, $request);
    }

    // Fetch response. Due to PHP bugs like http://bugs.php.net/bug.php?id=43782
    // and http://bugs.php.net/bug.php?id=46049 we can't rely on feof(), but
    // instead must invoke stream_get_meta_data() each iteration.
    $info = stream_get_meta_data($fp);
    $alive = !$info['eof'] && !$info['timed_out'];
    $response = '';

    while ($alive) {
      // Calculate how much time is left of the original timeout value.
      $timeout = $options['timeout'] - $this->iflychat_timer_read(__FUNCTION__) / 1000;
      if ($timeout <= 0) {
        $info['timed_out'] = TRUE;
        break;
      }
      stream_set_timeout($fp, floor($timeout), floor(1000000 * fmod($timeout, 1)));
      $chunk = fread($fp, 1024);
      $response .= $chunk;
      $info = stream_get_meta_data($fp);
      $alive = !$info['eof'] && !$info['timed_out'] && $chunk;
    }
    fclose($fp);

    if ($info['timed_out']) {
      $result->code = HTTP_REQUEST_TIMEOUT;
      $result->error = 'request timed out';
      return $result;
    }
    // Parse response headers from the response body.
    // Be tolerant of malformed HTTP responses that separate header and body with
    // \n\n or \r\r instead of \r\n\r\n.
    list($response, $result->data) = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
    $response = preg_split("/\r\n|\n|\r/", $response);

    // Parse the response status line.
    list($protocol, $code, $status_message) = explode(' ', trim(array_shift($response)), 3);
    $result->protocol = $protocol;
    $result->status_message = $status_message;

    $result->headers = array();

    // Parse the response headers.
    while ($line = trim(array_shift($response))) {
      list($name, $value) = explode(':', $line, 2);
      $name = strtolower($name);
      if (isset($result->headers[$name]) && $name == 'set-cookie') {
        // RFC 2109: the Set-Cookie response header comprises the token Set-
        // Cookie:, followed by a comma-separated list of one or more cookies.
        $result->headers[$name] .= ',' . trim($value);
      }
      else {
        $result->headers[$name] = trim($value);
      }
    }

    $responses = array(
      100 => 'Continue', 
      101 => 'Switching Protocols', 
      200 => 'OK', 
      201 => 'Created', 
      202 => 'Accepted', 
      203 => 'Non-Authoritative Information', 
      204 => 'No Content', 
      205 => 'Reset Content', 
      206 => 'Partial Content', 
      300 => 'Multiple Choices', 
      301 => 'Moved Permanently', 
      302 => 'Found', 
      303 => 'See Other', 
      304 => 'Not Modified', 
      305 => 'Use Proxy', 
      307 => 'Temporary Redirect', 
      400 => 'Bad Request', 
      401 => 'Unauthorized', 
      402 => 'Payment Required', 
      403 => 'Forbidden', 
      404 => 'Not Found', 
      405 => 'Method Not Allowed', 
      406 => 'Not Acceptable', 
      407 => 'Proxy Authentication Required', 
      408 => 'Request Time-out', 
      409 => 'Conflict', 
      410 => 'Gone', 
      411 => 'Length Required', 
      412 => 'Precondition Failed', 
      413 => 'Request Entity Too Large', 
      414 => 'Request-URI Too Large', 
      415 => 'Unsupported Media Type', 
      416 => 'Requested range not satisfiable', 
      417 => 'Expectation Failed', 
      500 => 'Internal Server Error', 
      501 => 'Not Implemented', 
      502 => 'Bad Gateway', 
      503 => 'Service Unavailable', 
      504 => 'Gateway Time-out', 
      505 => 'HTTP Version not supported',
    );
    // RFC 2616 states that all unknown HTTP codes must be treated the same as the
    // base code in their class.
    if (!isset($responses[$code])) {
      $code = floor($code / 100) * 100;
    }
    $result->code = $code;

    switch ($code) {
      case 200: // OK
      case 304: // Not modified
        break;
      case 301: // Moved permanently
      case 302: // Moved temporarily
      case 307: // Moved temporarily
        $location = $result->headers['location'];
        $options['timeout'] -= $this->iflychat_timer_read(__FUNCTION__) / 1000;
        if ($options['timeout'] <= 0) {
          $result->code = HTTP_REQUEST_TIMEOUT;
          $result->error = 'request timed out';
        }
        elseif ($options['max_redirects']) {
          // Redirect to the new location.
          $options['max_redirects']--;
          $result = $this->iflychat_extended_http_request($location, $options);
          $result->redirect_code = $code;
        }
        if (!isset($result->redirect_url)) {
          $result->redirect_url = $location;
        }
        break;
      default:
        $result->error = $status_message;
    }

    return $result;
  }
  
  private function iflychat_get_key() {
    //global $_iflychat, $iflychat;
    /*if(isset($_COOKIE['iflychat_c']) && isset($_COOKIE['iflychat_d'])) {
      echo rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($_COOKIE['iflychat_d']), base64_decode($_COOKIE['iflychat_c']), MCRYPT_MODE_CBC, md5(md5($_COOKIE['iflychat_d']))), "\0");
    } */
    if(session_id() == '') {
      session_start();
    }
    
    $id = $this->get_user_id();
    $name = $this->get_user_name();
    
    $data = array(
      'uname' => $name,
      'uid' => $id,
      'api_key' => $this->settings['api_key'],
      'image_path' => $this->settings['base'] . $this->settings['path'] . 'themes/' . $this->settings['theme'] . '/images',
      'isLog' => TRUE,
      'whichTheme' => 'blue',
      'enableStatus' => TRUE,
      'role' => ($this->user_details['is_admin'])?"admin":"normal",
      'validState' => array('available','offline','busy','idle'),
    );

    if($this->user_details['is_admin']) {
      $data['role'] = "admin";
    }
    else {
      $data['role'] = array();
      foreach ($this->user_details['role'] as $rkey => $rvalue) {
        $data['role'][$rkey] = $rvalue;
      }
    }
    
    if($this->settings['user_picture']) {
      $data['up'] = $this->get_user_picture_url();    
    }

    $data['upl'] = $this->get_user_profile_link();

    if($this->settings['enable_user_relationships']) {
      if(isset($this->user_details['relationships_set'])) {
        $data['rel'] = '1';
        $data['valid_uids'] = $this->user_details['relationships_set'];
      }
    }
    else {
      $data['rel'] = '0';
    }

    if($this->settings['enable_user_group_filtering']) {
      $data['rel'] = '0';
      $data['valid_groups'] = array();
      foreach ($this->user_details['role'] as $rkey => $rvalue) {
        $data['valid_groups'][$rkey] = $rvalue;
      }
    }
    
    $data = json_encode($data);
    $options = array(
      'method' => 'POST',
      'data' => $data,
      'timeout' => 15,
      'headers' => array('Content-Type' => 'application/json'),
    );
    $result = NULL;
    $result = $this->iflychat_extended_http_request($this->settings['A_HOST'] . '/p/', $options);
    $result = json_decode($result->data);
    $result->name = $name;
    $result->uid = $id;
    return $result;
  }
  
  private function check_ssl() {
    return (isset($_SERVER) && isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != "off"));
  }
  
  private function get_user_picture_url() {
    if(!($this->user_details['avatar_url']))  {
      $this->user_details['avatar_url'] = $this->settings['path'] . 'themes/' . $this->settings['theme'] . '/images/default_avatar.png';
    }
    return $this->user_details['avatar_url'];
  }
  
  private function get_user_profile_link() {
    if(!($this->user_details['upl'])) {
      $this->user_details['upl'] = 'javascript:void(0)';
    }
    return $this->user_details['upl'];
  }
  
  private function get_user_id() {
    if(($this->user_details['id'])) {
      $_SESSION['iflychat_id'] = $this->user_details['id'];
      return $this->user_details['id'];
    } 
    else {
      if((!$this->user_details['id']) && isset($_SESSION['iflychat_id']) && $this->check_session_guest()) {
        return $_SESSION['iflychat_id'];
      }
      else {
        $id = $this->get_guest_new_id();
        $_SESSION['iflychat_id'] = $id;
        return $id;
      }
    }
  }
  
  private function get_user_name() {
    if(($this->user_details['name'])) {
      $_SESSION['iflychat_name'] = $this->user_details['name'];
      return $this->user_details['name'];
    } 
    else {
      if((!$this->user_details['name']) && isset($_SESSION['iflychat_name']) && $this->check_session_guest()) {
        return $_SESSION['iflychat_name'];
      }
      else {
        $name = $this->get_guest_new_name();
        $_SESSION['iflychat_name'] = $name;
        return $name;
      }
    }
  }
  
  private function check_session_guest() {
    return (isset($_SESSION['iflychat_id']) && ($_SESSION['iflychat_id'][0] == "0") && ($_SESSION['iflychat_id'][1] == "-"));
  }
  
  private function get_guest_new_id() {
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $id = '0-'.time();
    for ($i = 0; $i < 5; $i++) {
      $id .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $id;
  }
  
  private function get_guest_new_name() {
    $path = dirname(__FILE__) . "/guest_names/drupalchat_guest_random_names.txt";
    $f_contents = file($path); 
    $line = trim($f_contents[rand(0, count($f_contents) - 1)]);
    return $this->settings['anon_prefix'] . $line;
  }

  private function iflychat_hash_base64($data) {
    $hash = base64_encode(hash('sha256', $data, TRUE));
    return strtr($hash, array('+' => '-', '/' => '_', '=' => ''));
  }

  public function iflychat_update_settings() {
    //global $_iflychat, $iflychat;
    $data = array(
      'api_key' => $this->settings['api_key'],
      'enable_chatroom' => ($this->settings['public_chatroom'])?'1':'2',
      'theme' => $this->settings['theme'],
      'notify_sound' => ($this->settings['notification_sound'])?'1':'2',
      'smileys' => ($this->settings['enable_smileys'])?'1':'2',
      'log_chat' => ($this->settings['log_chat'])?'1':'2',
      'chat_topbar_color' => $this->settings['chat_topbar_color'],
      'chat_topbar_text_color' => $this->settings['chat_topbar_text_color'],
      'font_color' => $this->settings['chat_font_color'],
      'chat_list_header' => $this->settings['chat_list_header'],
      'public_chatroom_header' => $this->settings['public_chatroom_header'],
      'rel' => ($this->settings['enable_user_relationships'])?'1':'0',
      'version' => $this->settings['version'],
      'show_admin_list' => ($this->settings['chat_type'] == '1')?'1':'2',
      'clear' => $this->settings['allow_single_message_delete'],
      'delmessage' => $this->settings['allow_clear_room_history'],
      'ufc' => ($this->settings['allow_user_font_color'])?'1':'2',    
      'guest_prefix' => $this->settings['anon_prefix'],
      'use_stop_word_list' => $this->settings['use_stop_word_list'],
      'stop_word_list' => $this->settings['stop_word_list'],
      'file_attachment' => ($this->settings['enable_file_attachment'])?'1':'2',
      'mobile_browser_app' => ($this->settings['enable_mobile_browser_app'])?'1':'2',
      'enable_groups' => ($this->settings['enable_user_group_filtering'])?'1':'2',
    );
    
    $data = json_encode($data);
      
    $options = array(
      'method' => 'POST',
      'data' => $data,
      'timeout' => 15,
      'headers' => array('Content-Type' => 'application/json'),
    );
    
    $result = $this->iflychat_extended_http_request($this->settings['A_HOST'] . ':' . $this->settings['A_PORT'] .  '/z/', $options);
    if($result->code == 200) {
      //$result = json_decode($result->data);
      return $result;
    }
    else {
      return $result;
      //form_set_error('drupalchat_external_api_key', "Unable to connect to iFlyChat server. Error code - " . $result->code . ". Error message - " . $result->error . ".");
    }
  }

  public function iflychat_get_message_thread($id1 = "1", $id2 = "2") {
    //global $_iflychat, $iflychat;
    $data = json_encode(array(
      'uid1' => $id1,
      'uid2' => $id2,
      'api_key' => $this->settings['api_key'],
      ));
    $options = array(
      'method' => 'POST',
      'data' => $data,
      'timeout' => 15,
      'headers' => array('Content-Type' => 'application/json'),
      );
    $result = $this->iflychat_extended_http_request($this->settings['A_HOST'] . ':' . $this->settings['A_PORT'] .  '/q/', $options);
    $q = json_decode($result->data);
    return $q;
  }

  public function iflychat_get_message_inbox($id1 = "1") {
    //global $_iflychat, $iflychat;
    $data = json_encode(array(
      'uid' => $id1,
      'api_key' => $this->settings['api_key'],
      ));
    $options = array(
      'method' => 'POST',
      'data' => $data,
      'timeout' => 15,
      'headers' => array('Content-Type' => 'application/json'),
      );
    $result = $this->iflychat_extended_http_request($this->settings['A_HOST'] . ':' . $this->settings['A_PORT'] .  '/r/', $options);
    $q = json_decode($result->data);
    return $q;
  }

  public function render_chat_mobile() {

    $data = array('settings' => array());
    $data['settings']['authUrl'] = $this->settings['path'] .  $this->settings['ajax_file'];
    $data['settings']['host'] = (($this->check_ssl())?($this->settings['A_HOST']):($this->settings['HOST']));
    $data['settings']['port'] = (($this->check_ssl())?($this->settings['A_PORT']):($this->settings['PORT']));
    $data = json_encode($data);
    
    $options = array(
      'method' => 'POST',
      'data' => $data,
      'timeout' => 15,
      'headers' => array('Content-Type' => 'application/json'),
      );
    $result = $this->iflychat_extended_http_request($this->settings['A_HOST'] . ':' . $this->settings['A_PORT'] .  '/m/v1/app/', $options);
    
    return $result->data;
  }
}
