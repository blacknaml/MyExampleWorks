<?php

class ycombinator_api {
	public $input;
	public $output;

	protected $host;
	protected $url;

	protected $useragent;
	protected $cookie_file;

	function __construct(){
		/* General params */
		$this->host = 'https://hacker-news.firebaseio.com/v0/';
		$this->url = $this->host;

		/* cookie file */
		$this->cookie_file = '/var/log/yc/yc_api_ck.txt';

		$this->useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1';

		/* Connection */
		$this->output['error'] = FALSE;
		$this->output['error_msg'] = '';
	}

	private function set_fail($message=''){
		/* create error message */		
		$this->output['error'] = TRUE;
		$this->output['error_msg'] .= $message;
	}

	private function set_url($url){
		$this->url = $this->host.$url;
	}

	private function curl($opt=NULL){
		$default = array(
			CURLOPT_URL => $this->url,
			CURLOPT_USERAGENT => $this->useragent,
			CURLOPT_COOKIEJAR => $this->cookie_file,
			CURLOPT_COOKIEFILE => $this->cookie_file,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_FOLLOWLOCATION => TRUE);
		if(is_null($opt) == FALSE)
			$option = array_merge($default, $opt);
		else 
			$option = $default;

		$curl_handler = curl_init();
		curl_setopt_array($curl_handler, $option);
		$response = curl_exec($curl_handler);
		curl_close($curl_handler);

		if(empty($response)){
			$this->set_fail('Empty Response or Connection Error.');
			return FALSE;
		} else {
			$this->output['response'] = $response;
			return TRUE;
		}
	}

	public function reset(){
		/* Connection */
		$this->output['error'] = FALSE;
		$this->output['error_msg'] = '';
	}

	public function set_cookie_file($cookie_file){
		$this->cookie_file = $cookie_file;
	}

	public function maxitem(){
		$this->set_url('maxitem.json?print=pretty');
		$this->curl();
	}

	public function news(){
		$this->set_url('topstories.json?print=pretty');
		$this->curl();
	}

	public function newest(){
		$this->set_url('newstories.json?print=pretty');
		$this->curl();
	}

	public function updates(){		
		$this->set_url('updates.json?print=pretty');
		$this->curl();
	}

	public function item($id){
		$this->set_url("item/$id.json?print=pretty");
		$this->curl();
	}

	public function user($id){
		$this->set_url("user/$id.json?print=pretty");
		if($this->curl()){
			$response = json_decode($this->output['response'], TRUE);
			if(is_array($response) == FALSE)
				$this->set_fail('User is Expired.');

		}
	}
}