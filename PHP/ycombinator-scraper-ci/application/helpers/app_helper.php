<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');	
if ( ! function_exists('random_string'))
{
	function random_string($len){
		$result = "";
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$charArray = str_split($chars);
		for($i = 0; $i < $len; $i++){
			$randItem = array_rand($charArray);
			$result .= "".$charArray[$randItem];
		}
		return $result;
	}
}
if ( ! function_exists('add_css'))
{
	function add_css($css) 
	{
		$result = '<link href="'.base_url(). 'assets/css/'.$css.'" rel="stylesheet" type="text/css" />';
		return $result;
	}		
}
if ( ! function_exists('add_js'))
{
	function add_js($js) 
	{
		$result = '<script src="'.base_url().'assets/js/'.$js.'" type="text/javascript"></script>';
		return $result;
	}		
}
if ( ! function_exists('tor_new_id'))
{
	function tor_new_id($tor_ip='127.0.0.1', $control_port='9051', $auth_code=''){
		$fp = fsockopen($tor_ip, $control_port, $errno, $errstr, 30);
		if (!$fp) return false; /*can't connect to the control port*/

		fputs($fp, "AUTHENTICATE $auth_code\r\n");
		$response = fread($fp, 1024);
		list($code, $text) = explode(' ', $response, 2);
		if ($code != '250') return false; /*authentication failed*/

		/*send the request to for new identity*/
		fputs($fp, "signal NEWNYM\r\n");
		$response = fread($fp, 1024);
		list($code, $text) = explode(' ', $response, 2);
		if ($code != '250') return false; /*signal failed*/

		fclose($fp);
		
		return true;
	}
}
if( ! function_exists('check_proxy_live'))
{
	function check_proxy_live($proxy_addr){
		$curl_handler = curl_init();
		curl_setopt($curl_handler, CURLOPT_URL, 'https://www.digi77.com/software/bouncer/data/myipvv-by-web.php');  
    	curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handler, CURLOPT_PROXY, $proxy_addr);
		curl_setopt($curl_handler, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		$response = curl_exec($curl_handler);
		curl_close($curl_handler);

		if($response === FALSE) return FALSE;
		else return TRUE;
	}
}
if( ! function_exists('get_proxy_list')){
	function get_proxy_list(){
		$proxy = array();
		
		$curl_handler = curl_init();
		curl_setopt($curl_handler, CURLOPT_URL, "http://www.proxz.com/proxy_list_high_anonymous_0.html"); 
		curl_setopt($curl_handler, CURLOPT_USERAGENT, 'Mozilla/6.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.7) Gecko/20050414 Firefox/1.0.3');
		curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, TRUE);		
		curl_setopt($curl_handler, CURLOPT_FAILONERROR, TRUE);
		$response = curl_exec($curl_handler); 
		curl_close($curl_handler);

		if($response !== TRUE){
			libxml_use_internal_errors(true);
			$dom = new DOMDocument;
			$dom->loadHTML($response);
			$xpath = new DOMXpath($dom);
			
			$a = $xpath->query("//td/p//a[contains(@href, 'proxy_list_high_anonymous_0.html')]/../text()");
			$b = $xpath->query("//td/p//a[contains(@href, 'proxy_list_anonymous_us_0.html')]/../text()");
			/*$c = $xpath->query("//td/p//a[contains(@href, 'proxy_list_uk_0.html')]/../text()");
			$d = $xpath->query("//td/p//a[contains(@href, 'proxy_list_ca_0.html')]/../text()");*/
			/*$e = $xpath->query("//td/p//a[contains(@href, 'proxy_list_cn_ssl_0.html')]/../text()");*/
			/*$f = $xpath->query("//td/p//a[contains(@href, 'proxy_list_jp_0.html')]/../text()");*/
			//$g = $xpath->query("//td/p//a[contains(@href, 'proxy_list_port_std_0.html')]/../text()");

			if($a->length){
				for($i=1; $i < $a->length; $i++){ 
					$proxy[] = $a->item($i)->nodeValue;
				}
			}
			if($b->length){
				for($i=1; $i < $b->length; $i++){ 
					$proxy[] = $b->item($i)->nodeValue; 
				}
			}
			/*if($c->length){
				for($i=1; $i < $c->length; $i++){ 
					$proxy[] = $c->item($i)->nodeValue; 
				}
			}
			if($d->length){
				for($i=1; $i < $d->length; $i++){ 
					$proxy[] = $d->item($i)->nodeValue; 
				}
			}*/
			/*if($e->length){
				for($i=1; $i < $e->length; $i++){ 
					$proxy[] = $e->item($i)->nodeValue; 
				}
			}*/
			/*if($f->length){
				for($i=1; $i < $f->length; $i++){ 
					$proxy[] = $f->item($i)->nodeValue; 
				}
			}*/
			/*if($g->length){
				for($i=1; $i < $g->length; $i++){ 
					$proxy[] = $g->item($i)->nodeValue; 
				}
			}*/
		}
		return $proxy;
	}
}
if( ! function_exists('check_user_session'))
{
	function check_user_session(){
		$ci = & get_instance();
		$user_id = $ci->session->userdata('user_id');
		if($user_id) return TRUE;
		else return FALSE;
	}
}
if( ! function_exists('multiarray_key_exists'))
{
	function multiarray_key_exists($key, Array $array){
		if (array_key_exists($key, $array)) {
			return true;
		}
		foreach ($array as $k=>$v) {
			if (!is_array($v)) {
				continue;
			}
			if (array_key_exists($key, $v)) {
				return true;
			}
		}
		return false;
	}
}
?>