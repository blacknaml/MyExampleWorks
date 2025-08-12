<?php

class ycombinator {

	public $input;
	public $output;

	private $host;
	private $proxy;
	private $cookie_file;
	private $useragent_list;
	private $useragent;
	private $use_proxy;

	private $upvote_link;
	private $text_link;

	private $err_conn;
	private $verbose_message;

	function __construct(){
		/* General params */
		$this->host = 'https://news.ycombinator.com/';
		/*$this->proxy = '127.0.0.1:8118';*/
		$this->use_proxy = TRUE;
		$this->useragent_list = array(
			'Mozilla/5.0 (Linux; U; Android 4.0.3; ko-kr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
			'Mozilla/5.0 (Linux; U; Android 4.0.3; de-ch; HTC Sensation Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
			'Mozilla/5.0 (Linux; U; Android 2.3; en-us) AppleWebKit/999+ (KHTML, like Gecko) Safari/999.9',
			'Mozilla/5.0 (Linux; U; Android 2.3.5; zh-cn; HTC_IncredibleS_S710e Build/GRJ90) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
			'Mozilla/5.0 (Linux; U; Android 2.3.5; en-us; HTC Vision Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
			'Mozilla/5.0 (Linux; U; Android 2.3.4; fr-fr; HTC Desire Build/GRJ22) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
			'Mozilla/5.0 (Linux; U; Android 2.3.4; en-us; T-Mobile myTouch 3G Slide Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
			'Mozilla/5.0 (Linux; U; Android 2.3.3; zh-tw; HTC_Pyramid Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
			'Mozilla/5.0 (Linux; U; Android 2.3.3; zh-tw; HTC_Pyramid Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari',
			'Mozilla/5.0 (Linux; U; Android 2.3.3; zh-tw; HTC Pyramid Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.346 Mobile Safari/534.11+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9860; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.254 Mobile Safari/534.11+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9850; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.254 Mobile Safari/534.11+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9850; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.115 Mobile Safari/534.11+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9850; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.254 Mobile Safari/534.11+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; zh-TW) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.448 Mobile Safari/534.8+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; zh-TW) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.246 Mobile Safari/534.1+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; tr) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.246 Mobile Safari/534.1+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; it) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.668 Mobile Safari/534.8+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; fr) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.246 Mobile Safari/534.1+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-US) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.701 Mobile Safari/534.8+',
			'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-US) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.466 Mobile Safari/534.8+',
			'Mozilla/5.0 (SymbianOS/9.4; Series60/5.0 NokiaC6-00/20.0.042; Profile/MIDP-2.1 Configuration/CLDC-1.1; zh-hk) AppleWebKit/525 (KHTML, like Gecko) BrowserNG/7.2.6.9 3gpp-gba',
			'Mozilla/5.0 (SymbianOS/9.3; Series60/3.2 NokiaE52-1/052.003; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/525 (KHTML, like Gecko) Version/3.0 BrowserNG/7.2.6.2 3gpp-gba',
			'Nokia5250/11.0.008 (SymbianOS/9.4; U; Series60/5.0 Mozilla/5.0; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/525 (KHTML, like Gecko) Safari/525 3gpp-gba',
			'Nokia5250/10.0.011 (SymbianOS/9.4; U; Series60/5.0 Mozilla/5.0; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/525 (KHTML, like Gecko) Safari/525 3gpp-gba',
			'Mozilla/5.0 (Windows; U; Windows CE; Mobile; like iPhone; ko-kr) AppleWebKit/533.3 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.3 Dorothy',
			'Mozilla/5.0 (Windows; U; Windows CE; Mobile; like Android; ko-kr) AppleWebKit/533.3 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.3 Dorothy',
			'Mozilla/5.0 (Windows; U; Mobile; Dorothy Browser; en-US) AppleWebKit/533.3 (KHTML, like Gecko) Version/3.1.2 Mobile Safari/533.3',
			'Mozilla/5.0 (Windows; U; Dorothy Browser; ko-kr) AppleWebKit/533.3 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.3',
			'Mozilla/5.0 (Android; Linux armv7l; rv:9.0) Gecko/20111216 Firefox/9.0 Fennec/9.0',
			'NokiaN85/GoBrowser/1.6.91',
			'NokiaN81/GoBrowser/1.6.91',
			'NokiaE72/GoBrowser/1.6.91',
			'NokiaC5-00/GoBrowser/1.6.91',
			'Nokia6700s/GoBrowser/1.6.91',
			'Nokia5700XpressMusic/GoBrowser/1.6.91',
			'Nokia5630XpressMusic/GoBrowser/1.6.91',
			'Nokia5320XpressMusic/GoBrowser/1.6.91',
			'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0)',
			'HTC_Touch_3G Mozilla/4.0 (compatible; MSIE 6.0; Windows CE; IEMobile 7.11)',
			'Opera/9.80 (J2ME/MIDP; Opera Mini/9.80 (S60; SymbOS; Opera Mobi/23.348; U; en) Presto/2.5.25 Version/10.54',
			'Opera/9.80 (J2ME/MIDP; Opera Mini/9.80 (S60; SymbOS; Opera Mobi/23.334; U; id) Presto/2.5.25 Version/10.54',
			'Opera/9.80 (J2ME/MIDP; Opera Mini/9.80 (J2ME/23.377; U; en) Presto/2.5.25 Version/10.54',
			'Opera/9.80 (J2ME/MIDP; Opera Mini/9.80 (J2ME/22.478; U; en) Presto/2.5.25 Version/10.54',
			'Opera/9.80 (Android; Opera Mini/7.5.33361/31.1350; U; en) Presto/2.8.119 Version/11.10');
		$this->useragent = $this->useragent_list[array_rand($this->useragent_list, 1)];

		/* Connection */
		$this->err_conn = FALSE;
		$this->output['error'] = FALSE;
		$this->output['error_msg'] = '';
		$this->output['verbose_message'] = $this->verbose_message;
	}

	function reset(){
		$this->use_proxy = TRUE;
		$this->useragent = $this->useragent_list[array_rand($this->useragent_list, 1)];

		/* Connection */
		$this->err_conn = FALSE;
		$this->output['error'] = FALSE;
		$this->output['error_msg'] = '';
		$this->output['verbose_message'] = $this->verbose_message;

		/* input */
		$this->input = array();
	}

	function set_cookie_file($path){ 
		/* explode path to array */
		$exp_path = explode('/', $path);
		$eoa = count($exp_path)-1;
		
		/* remove end of array */
		unset($exp_path[$eoa]);
		
		/* Is it folder */
		$directory = implode('/', $exp_path);
		if(is_dir($directory))
			$this->cookie_file = $path;
	}

	function set_proxy($url){
		$this->proxy = $url;
	}

	function disable_proxy(){
		$this->use_proxy = FALSE;
	}

	private function post_build($post_data){
		$post = http_build_query($post_data, '', '&');
		return $post;
	}

	private function set_fail($message=''){
		/* create error message */
		$this->err_conn = TRUE;
		$this->output['error'] = TRUE;
		$this->output['error_msg'] .= $message;
		$this->output['verbose_message'] .= $this->verbose_message;
	}

	private function verbose_curl($curl_handler){
		if($this->is_verbose){
			if(!curl_errno($curl_handler)){ 
				$info = curl_getinfo($curl_handler); 
				$this->verbose_message .= 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'].'<br/>'; 
			} else { 
				$this->verbose_message .= 'Curl error: ' . curl_error($curl_handler).'<br/>'; 
			} 
			$this->verbose_message .= "<hr/>";
		}
	}

	function send($act_type){
		if(isset($this->cookie_file)){
			switch($act_type){
				case 'register':
				$this->home();
				$this->register();
				$this->logout();
				break;

				case 'check':
				$this->home();
				$this->login();
				$this->logout();
				break;

				case 'upvote':
				$this->home();
				$this->login(TRUE);
				$this->upvote();
				$this->logout();
				break;

				case 'comment':
				$this->home();
				$this->login();
				$this->comment();
				$this->logout();
				break;

				case 'submit':
				$this->home();
				$this->login();
				$this->create_news();
				$this->logout();
				break;
			}
		} else {
			$this->set_fail('Cookie File Path not Set');
		}
	}

	public function single_upvote($url){
		if(isset($this->cookie_file)){
			$this->home();
			$this->login();
			$this->upvote($url);
			$this->logout();
		}
	}

	private function home(){
		$curl_handler = curl_init();
		curl_setopt($curl_handler, CURLOPT_URL, $this->host);
		curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
		if($this->use_proxy){
			/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
			curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
			curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		}
		curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($curl_handler, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handler, CURLOPT_FOLLOWLOCATION, TRUE);	
		$response = curl_exec($curl_handler);
		curl_close($curl_handler);

		if(empty($response)){
			$this->set_fail();
		}
	}

	private function register(){
		if($this->err_conn == FALSE){
			$post_data = array(
				'whence' => 'news',
				'creating' => 't',
				'acct' => $this->input['username'],
				'pw' => $this->input['password']);
			$post = $this->post_build($post_data);

			$curl_handler = curl_init();
			curl_setopt($curl_handler, CURLOPT_URL, $this->host.'login');
			curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
			if($this->use_proxy){
				/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
				curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
				curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			}
			curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
			curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
			curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl_handler, CURLOPT_FOLLOWLOCATION, TRUE);	
			curl_setopt($curl_handler, CURLOPT_POST, TRUE);
			curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $post);
			curl_setopt($curl_handler, CURLOPT_FAILONERROR, TRUE);
			$response = curl_exec($curl_handler);
			curl_close($curl_handler);

			if(empty($response) == FALSE){
				libxml_use_internal_errors(true);
				$dom = new DOMDocument;
				$dom->loadHTML($response);
				$xpath = new DOMXpath($dom);
				$logout_q = $xpath->query('//table//a[contains(@href, "logout")]/text()');
				if($logout_q->length == 0){
					$message = '';
					$error_msg = $xpath->query('//body/text()[1]');
					if($error_msg->length > 0){
						$message = $error_msg->item(0)->nodeValue;
						if (strpos($message,'Many new accounts.') !== false) {
							$message = 'Connection time out. ';
						}
					}
					
					$this->set_fail('Could not register. '.$message.'Please, Try again. ');
				} else {
					$up_link = array();
					$this->output['upvote_url'] = '';

					$up_q = $xpath->query('//td//a[contains(@id, "up")]/@href');
					if($up_q->length > 0){
						for($i = 0; $i < $up_q->length; $i++){
							$up_link[] = $up_q->item($i)->nodeValue;
						}
					}

					$upvote_random_list = array_rand($up_link, 1);
					$uv_url = $this->host.$up_link[$upvote_random_list];

					$curl_handler = curl_init();
					curl_setopt($curl_handler, CURLOPT_URL, $uv_url);
					curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
					if($this->use_proxy){
						/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
						curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
						curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
					}
					curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
					curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
					curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
					curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
					$response = curl_exec($curl_handler);
					curl_close($curl_handler);

					$this->output['upvote_url'] = $uv_url;
					
				}
			} else {
				$this->set_fail('Failed to connect to the target server (Time out). Please, Try again!.');
			}
		}
	}

	private function login($link=FALSE){
		/* login?whence=news */
		$curl_handler = curl_init();
		curl_setopt($curl_handler, CURLOPT_URL, $this->host.'login?whence=news');
		curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
		if($this->use_proxy){
			/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
			curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
			curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		}
		curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handler, CURLOPT_FOLLOWLOCATION, TRUE);	
		$response = curl_exec($curl_handler);
		curl_close($curl_handler);

		/* login into application */
		$post_data = array(
			'whence' => 'news',
			'acct' => $this->input['username'],
			'pw' => $this->input['password']);
		$post = $this->post_build($post_data);

		$curl_handler = curl_init();
		curl_setopt($curl_handler, CURLOPT_URL, $this->host.'login');
		curl_setopt($curl_handler, CURLOPT_REFERER, $this->host.'login?whence=news');
		if($this->use_proxy){
			/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
			curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
			curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		}
		curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($curl_handler, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handler, CURLOPT_FOLLOWLOCATION, TRUE);	
		curl_setopt($curl_handler, CURLOPT_POST, TRUE);
		curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $post);
		curl_setopt($curl_handler, CURLOPT_FAILONERROR, TRUE);
		$response = curl_exec($curl_handler);
		curl_close($curl_handler);

		if(empty($response) == FALSE){
			libxml_use_internal_errors(true);
			$dom = new DOMDocument;
			$dom->loadHTML($response);
			$xpath = new DOMXpath($dom);
			$logout_q = $xpath->query('//table//a[contains(@href, "logout")]/text()');
			if($logout_q->length <= 0){
				$message = '';
				$error_msg = $xpath->query('//body/text()[1]');
				$error_msg_all = $xpath->query('//body/text()');
				if($error_msg->length > 0)
					$message = $error_msg->item(0)->nodeValue;
				else 
					$message = ($error_msg_all->length > 0) ? $error_msg_all->item(0)->nodeValue : 'Unknown error.';

				$this->set_fail('Failed. Message: '.$message.'. ');
			} else {
				if($link){
					$up_link = array();
					$text_link = array();

					$up_q = $xpath->query('//td//a[contains(@id, "up") and @style=not("visibility: hidden;")]/@href');
					$text_q = $xpath->query('//td//a[contains(@id, "up") and @style=not("visibility: hidden;")]/../../following-sibling::td[@class="title" and 1]/a/text()');
					if($up_q->length > 0){
						for($i = 0; $i < $up_q->length; $i++){
							$up_link[] = $up_q->item($i)->nodeValue;
							$text_link[] = $text_q->item($i)->nodeValue;
						}
					}

					$this->upvote_link = $up_link;
					$this->text_link = $text_link;
				}
			}
		} else {
			$this->set_fail('Failed to connect to the target server. Please, Try again!.');
		}

	}

	private function logout(){
		$curl_handler = curl_init();
		curl_setopt($curl_handler, CURLOPT_URL, $this->host.'logout?whence=news');
		curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
		if($this->use_proxy){
			/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
			curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
			curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		}
		curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
		curl_setopt($curl_handler, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($curl_handler);
		curl_close($curl_handler);
	}

	private function upvote($url=null){
		if($this->err_conn == FALSE){
			if(is_null($url)){
				if(count($this->upvote_link) >= $this->input['upvote_number']){
					/* Random upvoting */
					$upvote_random_list = array_rand($this->upvote_link, $this->input['upvote_number']);

					$this->output['upvote_url'] = '';

					foreach($upvote_random_list as $val){
						$uv_url = $this->host.$this->upvote_link[$val];
						$uv_text = $this->text_link[$val];

						$curl_handler = curl_init();
						curl_setopt($curl_handler, CURLOPT_URL, $uv_url);
						curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
						if($this->use_proxy){
							/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
							curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
							curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
						}
						curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
						curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
						curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
						curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($curl_handler, CURLOPT_FOLLOWLOCATION, TRUE);
						$response = curl_exec($curl_handler);
						curl_close($curl_handler);

						$this->output['upvote_url'] .= "'".$uv_text."', ";
					}

					$this->output['news_url'] = $this->upvote_link;
				} else {
					$this->set_fail('There are not article for upvoting on page 1.');
				}
			} else {
				/* Single upvoting */
				$curl_handler = curl_init();
				curl_setopt($curl_handler, CURLOPT_URL, $url);
				curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
				if($this->use_proxy){
					/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
					curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
					curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
				}
				curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
				curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
				curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
				$response = curl_exec($curl_handler);
				curl_close($curl_handler);

				if(empty($response) == FALSE){
					$query = parse_url($url, PHP_URL_QUERY);
					parse_str($query);

					libxml_use_internal_errors(true);
					$dom = new DOMDocument;
					$dom->loadHTML($response);
					$xpath = new DOMXpath($dom);

					$a_query = '//td//a[contains(@id, "up_'.$id.'") and @style=not("visibility: hidden;")]/@href';
					$a_href = $xpath->query($a_query);

					if($a_href->length > 0){
						$vote_url = $this->host.$a_href->item(0)->nodeValue;

						$curl_handler = curl_init();
						curl_setopt($curl_handler, CURLOPT_URL, $vote_url);
						curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
						if($this->use_proxy){
							/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
							curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
							curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
						}
						curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
						curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
						curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
						curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($curl_handler, CURLOPT_FOLLOWLOCATION, TRUE);
						$response = curl_exec($curl_handler);
						curl_close($curl_handler);

						if(empty($response) == FALSE){
							libxml_use_internal_errors(true);
							$dom = new DOMDocument;
							$dom->loadHTML($response);
							$xpath = new DOMXpath($dom);
							$a_href = $xpath->query('//td//a[contains(@id, "up_'.$id.'") and @style=not("visibility: hidden;")]/@href');

							if($a_href->length > 0)
								$this->set_fail('[US04] '.$this->input['username']." fails upvoting the article ($url), please try again.");
							else 
								$this->output['error_msg'] = $this->input['username']." has been succeeded upvoting the article ($url).";
						} else {
							$this->set_fail('[US03]'.$this->input['username']." fail upvoting article ($url).");
						}
					} else {
						$this->set_fail('[US02]'.$this->input['username'].". has been ever upvoted $url article.");
					}
				} else {
					$this->set_fail('[US01]'.$this->input['username'].". Failed to connect to the target server ($url). Try again!.");
				}
			}
		}
	}	

	private function create_news(){
		if($this->err_conn == FALSE){
			$curl_handler = curl_init();
			curl_setopt($curl_handler, CURLOPT_URL, $this->host.'submit');
			curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
			if($this->use_proxy){
				/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
				curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
				curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			}
			curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
			curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
			curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
			$response = curl_exec($curl_handler);
			curl_close($curl_handler);

			if(empty($response) == FALSE){
				libxml_use_internal_errors(true);
				$dom = new DOMDocument;
				$dom->loadHTML($response);
				$xpath = new DOMXpath($dom);
				$fnid_q = $xpath->query('//form/input[@name="fnid"]/@value');

				$post_data = array(
					'fnid' => $fnid_q->item(0)->nodeValue,
					'title' => $this->input['article_title'],
					'url' => $this->input['article_url'],
					'text' => $this->input['article_content']
					);
				$post = $this->post_build($post_data);

				$curl_handler = curl_init();
				curl_setopt($curl_handler, CURLOPT_URL, $this->host.'r');
				curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
				if($this->use_proxy){
					/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
					curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
					curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
				}
				curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
				curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
				curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($curl_handler, CURLOPT_FOLLOWLOCATION, TRUE);	
				curl_setopt($curl_handler, CURLOPT_POST, TRUE);
				curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $post);
				curl_setopt($curl_handler, CURLOPT_FAILONERROR, TRUE);
				$response = curl_exec($curl_handler);
				curl_close($curl_handler);

				if(empty($response) == FALSE){
					libxml_use_internal_errors(true);
					$dom = new DOMDocument;
					$dom->loadHTML($response);
					$xpath = new DOMXpath($dom);
					$adminmsg_q = $xpath->query('//table[@id="hnmain"]//td/span[@class="admin"]/center/text()');
					$fnid_q = $xpath->query('//form/input[@name="fnid"]/@value');

					if($adminmsg_q->length > 0)
						$this->set_fail($adminmsg_q->item(0)->nodeValue);

					if($fnid_q->length > 0)
						$this->set_fail("Unknown Error.");

				} else {
					$this->set_fail('[CN02]Failed to connect to the target server. Try again!.');
				}
			} else {
				$this->set_fail('[CN01]Failed to connect to the target server. Try again!.');
			}
		}
	}

	private function comment(){
		if($this->err_conn == FALSE){
			$article_url = $this->input['article_url'];

			$curl_handler = curl_init();
			curl_setopt($curl_handler, CURLOPT_URL, $article_url);
			curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
			if($this->use_proxy){
				/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
				curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
				curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			}
			curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
			curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
			curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
			$response = curl_exec($curl_handler);
			curl_close($curl_handler);

			if(empty($response)){
				$this->set_fail("[CM01] Failed to connect to the article url ($url). Try again!.");
			} else {
				libxml_use_internal_errors(true);
				$dom = new DOMDocument;
				$dom->loadHTML($response);
				$xpath = new DOMXpath($dom);
				$parent_q = $xpath->query('//form/input[@name="parent"]/@value');
				$goto_q = $xpath->query('//form/input[@name="goto"]/@value');
				$hmac_q = $xpath->query('//form/input[@name="hmac"]/@value');

				if($parent_q->length > 0){
					$post_data = array(
						'parent' => $parent_q->item(0)->nodeValue,
						'goto' => $goto_q->item(0)->nodeValue,
						'hmac' => $hmac_q->item(0)->nodeValue,
						'text' => $this->input['comment']);
					$post = $this->post_build($post_data);

					$curl_handler = curl_init();
					curl_setopt($curl_handler, CURLOPT_URL, $this->host.'comment');
					curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
					if($this->use_proxy){
						/*curl_setopt($curl_handler, CURLOPT_HTTPPROXYTUNNEL, 1);*/
						curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
						curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
					}
					curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
					curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->cookie_file);
					curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
					curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($curl_handler, CURLOPT_FOLLOWLOCATION, TRUE);	
					curl_setopt($curl_handler, CURLOPT_POST, TRUE);
					curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $post);
					curl_setopt($curl_handler, CURLOPT_FAILONERROR, TRUE);
					$response = curl_exec($curl_handler);
					curl_close($curl_handler);

					if(empty($response)){
						$this->set_fail('[CO01]Failed to connect to the target server. Try again!.');
					} else {
						libxml_use_internal_errors(true);
						$dom = new DOMDocument;
						$dom->loadHTML($response);
						$xpath = new DOMXpath($dom);
						$edit_q = $xpath->query('//a[contains(@href, "edit")]/@href');
						if($edit_q->length > 0)
							$id = str_replace('edit', 'item', trim($edit_q->item(0)->nodeValue));
						else 
							$id = FALSE;

						$this->output['comment_id'] = $id;
					}
				} else {
					$dead_q = $xpath->query('//span[contains(text(), "[dead]")]');
					$status = ($dead_q->length > 0) ? ' with status [dead]' : '';

					$this->set_fail("You can not comment on this article ($article_url)$status.");
				}
			}
		}
	}
}