<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author: Debi Praharadika
 */
class test extends CI_Controller {
	private $data;
	private $host;
	private $proxy;
	private $useragent;

	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'Test',
			'small_content_header' => '',
			'content' => '');

		/* General params */
		$this->host = 'http://74.82.164.30/ws/index.php/show_header';
		$this->proxy = '85.105.115.59:8080';
		$upvote_random_list = 
		$useragent_list = array(
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
		$this->useragent = $useragent_list[array_rand($useragent_list, 1)];

		$this->load->model('general_model');
	}

	function index() {
		if(check_user_session() == FALSE) redirect('auth/logout');

		$this->data['content'] = $this->load->view('admin/test/form', array('ua'=>$this->useragent), TRUE);
		
		$this->load->view('admin/home', $this->data);
	}

	private function post_build($post_data){
		$post = http_build_query($post_data, '', '&');
		return $post;
	}

	function get_proxy(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$curl_handler = curl_init();
		curl_setopt($curl_handler, CURLOPT_URL, "http://www.proxz.com/proxy_list_high_anonymous_0.html"); 
		curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);		
		curl_setopt($curl_handler, CURLOPT_FAILONERROR, TRUE);
		$response = curl_exec($curl_handler); 
		curl_close($curl_handler);

		if($response !== TRUE){
			$proxy = array();

			libxml_use_internal_errors(true);
			$dom = new DOMDocument;
			$dom->loadHTML($response);
			$xpath = new DOMXpath($dom);
			
			$a = $xpath->query("//td/p//a[contains(@href, 'proxy_list_high_anonymous_0.html')]/../text()");
			$b = $xpath->query("//td/p//a[contains(@href, 'proxy_list_anonymous_us_0.html')]/../text()");
			$c = $xpath->query("//td/p//a[contains(@href, 'proxy_list_uk_0.html')]/../text()");
			$d = $xpath->query("//td/p//a[contains(@href, 'proxy_list_ca_0.html')]/../text()");
			$e = $xpath->query("//td/p//a[contains(@href, 'proxy_list_cn_ssl_0.html')]/../text()");
			$f = $xpath->query("//td/p//a[contains(@href, 'proxy_list_jp_0.html')]/../text()");
			$g = $xpath->query("//td/p//a[contains(@href, 'proxy_list_port_std_0.html')]/../text()");

			if($a->length){
				for($i=1; $i < $a->length; $i++){ $proxy[] = $a->item($i)->nodeValue;}
			}
			if($b->length){
				for($i=1; $i < $b->length; $i++){ $proxy[] = $b->item($i)->nodeValue; }
			}
			if($c->length){
				for($i=1; $i < $c->length; $i++){ $proxy[] = $c->item($i)->nodeValue; }
			}
			if($d->length){
				for($i=1; $i < $d->length; $i++){ $proxy[] = $d->item($i)->nodeValue; }
			}
			if($e->length){
				for($i=1; $i < $e->length; $i++){ $proxy[] = $e->item($i)->nodeValue; }
			}
			if($f->length){
				for($i=1; $i < $f->length; $i++){ $proxy[] = $f->item($i)->nodeValue; }
			}
			if($g->length){
				for($i=1; $i < $g->length; $i++){ $proxy[] = $g->item($i)->nodeValue; }
			}
			
		}

		send_json_action($response, 'Success', 'Fail. ', $proxy);
	}

	function submit(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$return_val = TRUE;
		$err_msg = '';

		if($this->config->item('use_tor')){
			tor_new_id($this->config->item('proxy_control_url'), $this->config->item('proxy_control_port'), $this->config->item('proxy_control_auth'));
			$this->proxy = $config['proxy_url'].':'.$config['proxy_port'];
		}else{
			$proxy_list = get_proxy_list();
			$this->proxy = $proxy_list[array_rand($proxy_list, 1)];
		}

		try{
			if(count($proxy_list) < 1) throw new Exception('Proxy Null');
			if(is_null($this->proxy)) throw new Exception('Proxy Null.');

			$curl_handler = curl_init();
			curl_setopt($curl_handler, CURLOPT_URL, $this->host);
			curl_setopt($curl_handler, CURLOPT_REFERER, $this->host);
			curl_setopt($curl_handler, CURLOPT_PROXY, $this->proxy);
			curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt($curl_handler, CURLOPT_USERAGENT, $this->useragent);
			curl_setopt($curl_handler, CURLOPT_COOKIEFILE, $this->config->item('cookies_path').'ycombinator'.$this->session->userdata('user_id').'.txt');
			curl_setopt($curl_handler, CURLOPT_COOKIEJAR, $this->config->item('cookies_path').'ycombinator'.$this->session->userdata('user_id').'.txt');
			curl_setopt($curl_handler, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl_handler, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
			$response = curl_exec($curl_handler);
			curl_close($curl_handler);

			if(empty($response)) throw new Exception('Empty Response.'.$this->proxy);

		} catch (Exception $e) {
			$err_msg = $e->getMessage();
			$return_val = FALSE;
			$response = 'Failed';
		}

		send_json_action($return_val, 'Success', 'Fail. '.$err_msg, array('response' => $response, 'proxy' => $this->proxy));
	}

}