<?php

require_once(dirname(__FILE__).'/iflychat_api_settings.php'); 

 
function iflychat_render_chat($set = array()) {
  $defset = array(
    'name' => NULL,
	  'id' => 0,
	  'avatar_url' => FALSE,
	  'is_admin' => FALSE,
    'relationships_set' => FALSE,
    'upl' => FALSE,
  );
  $refset = array_merge($defset, $set);
  
  $json['is_admin'] = $refset['is_admin'];
  //$my_settings['session_key'] = $json['key'];
  global $_iflychat, $iflychat;
  $r = '<script type="text/javascript">';
  if($_GET['iflychat_update']=='true' && $_GET['iflychat_save']=='yes') {
    $ures = iflychat_update_settings();
	if($ures->code == 200) {
	  $r .= 'alert(\'iFlyChat Settings updated successfully.\');';
	}
	else {
	  $r .= 'alert("'."Unable to connect to iFlyChat server. Error code - " . $ures->code . ". Error message - " . $ures->error .'.");';
	}
  }
  $json = (array)iflychat_get_key($refset);
  $json = array_merge($refset, $json);

  $r .= 'Drupal={};Drupal.settings={};Drupal.settings.drupalchat={};' . iflychat_arrayToJSObject(array('drupalchat' => iflychat_init($json)), 'Drupal.settings')  . '</script>';
  $r .= '<link type="text/css" rel="stylesheet" href="' . (($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? $_iflychat['A_HOST'] : $_iflychat['HOST']) .  '/i/' . $json['css'] . '/cache.css" media="all" />';
  $r .= '<script type="text/javascript" src="' . (($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? $_iflychat['A_HOST'] : $_iflychat['HOST']) .  '/j/cache.js"></script>';
  $r .= '<script type="text/javascript" src="' . $iflychat['path'] .  'js/ba-emotify.js"></script>';
  $r .= '<script type="text/javascript" src="' . $iflychat['path'] .  'js/jquery.titlealert.min.js"></script>';
  $r .= '<script src="'. (($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? $_iflychat['A_HOST'] : $_iflychat['HOST']) .  '/h/' . $json['css'] . '/cache.js" type=\'text/javascript\'></script>';
  return $r;
}

function iflychat_init($jsset) {
  global $_iflychat, $iflychat;
  $my_settings = array(
    'uid' => $jsset['uid'],
    'username' => $jsset['name'],
    'current_timestamp' => time(),
    'polling_method' => "3",
    'pollUrl' => " ",
    'sendUrl' => " ",
    'statusUrl' => " ",
    'status' => "1",
    'goOnline' => 'Go Online',
    'goIdle' => 'Go Idle',
    'newMessage' => 'New chat message!',
    'images' => $iflychat['path'] . 'themes/light/images/',
    'sound' => $iflychat['path'] . 'swf/sound.swf',
    'noUsers' => "<div class=\"item-list\"><ul><li class=\"drupalchatnousers even first last\">No users online</li></ul></div>",
    'smileyURL' => $iflychat['path'] . 'smileys/very_emotional_emoticons-png/png-32x32/',
    'addUrl' => " ",
    'notificationSound' => "1",
    'basePath' => "/",
    'useStopWordList' => $iflychat['use_stop_word_list'],
    'blockHL' => $iflychat['stop_links'],
    'allowAnonHL' => ($iflychat['allow_anon_links'])?'1':'2',
    'iup' => ($iflychat['user_picture'])?'1':'2',
    'admin' => $jsset['is_admin']?'1':'0',
    'session_key' => $jsset['key'],
  );
	
	if($iflychat['use_stop_word_list'] != '1') {
	  $my_settings['stopWordList'] = $iflychat['stop_word_list'];
	}
	if($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") {
      $my_settings['external_host'] = $_iflychat['A_HOST'];
      $my_settings['external_port'] = $_iflychat['A_PORT'];
      $my_settings['external_a_host'] = $_iflychat['A_HOST'];
      $my_settings['external_a_port'] = $_iflychat['A_PORT'];		
	}
    else {
      $my_settings['external_host'] = $_iflychat['HOST'];
      $my_settings['external_port'] = $_iflychat['PORT'];
      $my_settings['external_a_host'] = $_iflychat['HOST'];
      $my_settings['external_a_port'] = $_iflychat['PORT'];
	}	
	if($iflychat['user_picture']) {
		if(isset($jsset['avatar_url'])) {
	      $my_settings['up'] = $jsset['avatar_url'];
        }
        else {
         $my_settings['up'] = $iflychat['path'] . 'themes/light/images/default_avatar.png';
        }
		$my_settings['default_up'] = $iflychat['path'] . 'themes/light/images/default_avatar.png';
		$my_settings['default_cr'] = $iflychat['path'] . 'themes/light/images/default_room.png';
    }    
  if(isset($jsset['upl']) && $jsset['upl']) {
    $my_settings['upl'] = $jsset['upl'];
  }
  else {
    $my_settings['upl'] = 'user/' . $jsset['uid'];
  }  
	return $my_settings;
}

function iflychat_timer_read($name) {
  global $timers;

  if (isset($timers[$name]['start'])) {
    $stop = microtime(TRUE);
    $diff = round(($stop - $timers[$name]['start']) * 1000, 2);

    if (isset($timers[$name]['time'])) {
      $diff += $timers[$name]['time'];
    }
    return $diff;
  }
  return $timers[$name]['time'];
}

function iflychat_timer_start($name) {
  global $timers;

  $timers[$name]['start'] = microtime(TRUE);
  $timers[$name]['count'] = isset($timers[$name]['count']) ? ++$timers[$name]['count'] : 1;
}

function iflychat_extended_http_request($url, array $options = array()) {
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

  iflychat_timer_start(__FUNCTION__);

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
  $timeout = $options['timeout'] - iflychat_timer_read(__FUNCTION__) / 1000;
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
    $timeout = $options['timeout'] - iflychat_timer_read(__FUNCTION__) / 1000;
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
      $options['timeout'] -= iflychat_timer_read(__FUNCTION__) / 1000;
      if ($options['timeout'] <= 0) {
        $result->code = HTTP_REQUEST_TIMEOUT;
        $result->error = 'request timed out';
      }
      elseif ($options['max_redirects']) {
        // Redirect to the new location.
        $options['max_redirects']--;
        $result = iflychat_extended_http_request($location, $options);
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

function iflychat_get_key($sets) {
  global $_iflychat, $iflychat;
  /*if(isset($_COOKIE['iflychat_c']) && isset($_COOKIE['iflychat_d'])) {
    echo rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($_COOKIE['iflychat_d']), base64_decode($_COOKIE['iflychat_c']), MCRYPT_MODE_CBC, md5(md5($_COOKIE['iflychat_d']))), "\0");
  } */
  if(session_id() == '') {
    session_start();
  }
  if(($sets['name']) && ($sets['id'])) {
    $_SESSION['iflychat_name'] = $sets['name'];
	$_SESSION['iflychat_id'] = $sets['name'];
	$name = $sets['name'];
	$id = $sets['id'];
  } 
  else {
    if((!$sets['name']) && isset($_SESSION['iflychat_name']) && isset($_SESSION['iflychat_id'])) {
      $name = $_SESSION['iflychat_name'];
	  $sets['name'] = $_SESSION['iflychat_name'];
	  $id = $_SESSION['iflychat_id'];
    } 
    if(!$sets['name']) {
      $result = iflychat_extended_http_request($_iflychat['HOST'] .  '/anam/v/' . 'usa', array('Content-Type' => 'application/json',));
	  if($result->code == 200) {
	    $name = 'Guest'  . ' ' . $result->data;
      }
	  $id = '0-' . time();
    }
	$_SESSION['iflychat_name'] = $name;
	$_SESSION['iflychat_id'] = $id;
	//$lk = iflychat_hash_base64(mt_rand());
	//setcookie('iflychat_c', base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($lk), " $name:$id ", MCRYPT_MODE_CBC, md5(md5($lk)))), time()+(3600*24*30));
	//setcookie('iflychat_d', $lk, time()+(3600*24*30));
  }
  $data = array(
    'uname' => $name,
    'uid' => $id,
    'api_key' => $iflychat['api_key'],
	  'image_path' => $iflychat['base'] . $iflychat['path'] . 'themes/light/images',
	  'isLog' => TRUE,
	  'whichTheme' => 'blue',
	  'enableStatus' => TRUE,
	  'role' => ($sets['is_admin'])?"admin":"normal",
	  'validState' => array('available','offline','busy','idle'),
  );
  
  if($iflychat['user_picture']) {
    if(isset($sets['avatar_url'])) {
	    $data['up'] = $sets['avatar_url'];
    }
    else {
      $data['up'] = $iflychat('path') . 'themes/light/images/default_avatar.png';
    }
  }

  if(isset($sets['upl']) && $sets['upl']) {
    $data['upl'] = $sets['upl'];
  }
  else {
    $data['upl'] = 'user/' . $id;
  }

  if($iflychat['enable_relationships']) {
    if(isset($sets['relationships_set'])) {
      $data['rel'] = '1';
      $data['valid_uids'] = $sets['relationships_set'];
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
  $result = iflychat_extended_http_request($_iflychat['A_HOST'] . '/p/', $options);
  $result = json_decode($result->data);
  $result->name = $name;
  $result->uid = $id;
  return $result;
}
function iflychat_arrayToJSObject($array, $varname, $sub = false ) { 
    $jsarray = $sub ? $varname . "{" : $varname . " = {\n"; 
    $varname = "\t$varname"; 
    reset ($array); 

    // Loop through each element of the array 
    while (list($key, $value) = each($array)) { 
        $jskey = "'" . $key . "' : "; 
        
        if (is_array($value)) { 
            // Multi Dimensional Array 
            $temp[] = iflychat_arrayToJSObject($value, $jskey, true); 
        } else { 
            if (is_numeric($value) && $key=="current_timestamp") { 
                $jskey .= "$value"; 
            } elseif (is_bool($value)) { 
                $jskey .= ($value ? 'true' : 'false') . ""; 
            } elseif ($value === NULL) { 
                $jskey .= "null"; 
            } else { 
                static $pattern = array("\\", "'", "\r", "\n"); 
                static $replace = array('\\', '\\\'', '\r', '\n'); 
                $jskey .= "'" . str_replace($pattern, $replace, $value) . "'"; 
            } 
            $temp[] = $jskey; 
        } 
    } 
    $jsarray .= implode(', ', $temp); 

    $jsarray .= "}\n"; 
    return $jsarray; 
} 

function iflychat_hash_base64($data) {
  $hash = base64_encode(hash('sha256', $data, TRUE));
  return strtr($hash, array('+' => '-', '/' => '_', '=' => ''));
}

function iflychat_update_settings() {
  global $_iflychat, $iflychat;
  $data = array(
      'api_key' => $iflychat['api_key'],
	  'enable_chatroom' => ($iflychat['public_chatroom'])?'1':'2',
	  'theme' => $iflychat['theme'],
	  'notify_sound' => ($iflychat['notification_sound'])?'1':'2',
	  'smileys' => ($iflychat['enable_smileys'])?'1':'2',
	  'log_chat' => ($iflychat['log_chat'])?'1':'2',
	  'chat_topbar_color' => $iflychat['chat_topbar_color'],
	  'chat_topbar_text_color' => $iflychat['chat_topbar_text_color'],
	  'font_color' => $iflychat['chat_font_color'],
	  'chat_list_header' => $iflychat['chat_list_header'],
	  'public_chatroom_header' => $iflychat['public_chatroom_header'],
	  'rel' => $iflychat['rel'],
	  'version' => 'php-1.0.0',
	  'show_admin_list' => ($iflychat['show_admin_list'])?'1':'2',
    );
	$data = json_encode($data);
    $options = array(
      'method' => 'POST',
      'data' => $data,
      'timeout' => 15,
      'headers' => array('Content-Type' => 'application/json'),
    );
	$result = iflychat_extended_http_request($_iflychat['A_HOST'] . ':' . $_iflychat['A_PORT'] .  '/z/', $options);
	if($result->code == 200) {
	  //$result = json_decode($result->data);
	  return $result;
	}
	else {
	  return $result;
	  //form_set_error('drupalchat_external_api_key', "Unable to connect to iFlyChat server. Error code - " . $result->code . ". Error message - " . $result->error . ".");
	}
}

function iflychat_get_message_thread($id1 = "1", $id2 = "2") {
  global $_iflychat, $iflychat;
  $data = json_encode(array(
    'uid1' => $id1,
    'uid2' => $id2,
    'api_key' => $iflychat['api_key'],
    ));
  $options = array(
    'method' => 'POST',
    'data' => $data,
    'timeout' => 15,
    'headers' => array('Content-Type' => 'application/json'),
    );
  $result = iflychat_extended_http_request($_iflychat['A_HOST'] . ':' . $_iflychat['A_PORT'] .  '/q/', $options);
  $q = json_decode($result->data);
  return $q;
}

function iflychat_get_message_inbox($id1 = "1") {
  global $_iflychat, $iflychat;
  $data = json_encode(array(
    'uid' => $id1,
    'api_key' => $iflychat['api_key'],
    ));
  $options = array(
    'method' => 'POST',
    'data' => $data,
    'timeout' => 15,
    'headers' => array('Content-Type' => 'application/json'),
    );
  $result = iflychat_extended_http_request($_iflychat['A_HOST'] . ':' . $_iflychat['A_PORT'] .  '/r/', $options);
  $q = json_decode($result->data);
  return $q;
}
