<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author: Debi Praharadika
 */
class single_upvoting extends CI_Controller {
	private $data;

	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'Upvote Single Article',
			'small_content_header' => 'Voting',
			'content' => '');

		$this->load->model('general_model');
		$this->load->model('account_model');
	}

	function index() {
		if(check_user_session() == FALSE) redirect('auth/logout');

		$user_id = $this->session->userdata('user_id');
		$account_data = $this->account_model->read_by_scid($this->config->item('app_nyc_scid'), $user_id);

		foreach($account_data as $val){
			$account_cbo[$val['ycu_id']] = $val['ycu_username'];
		}
		$form['account_cbo'] = $account_cbo;

		$this->data['content'] = $this->load->view('admin/single_upvoting/form', $form, TRUE);
		
		$this->load->view('admin/home', $this->data);
	}

	function submit(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('i-article', 'Article url', 'required|trim|valid_url_format|url_exists');
		$this->form_validation->set_rules('i-account[]', 'Account/s', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE) send_json_validate();

		$i_account = $this->input->post('i-account');
		$i_article = $this->input->post('i-article');

		$success_resp = '';
		$fail_resp = '';
		
		/* get class from app_config for nyc config */
		$scraper_data = $this->general_model->get_scraper_class($this->config->item('app_nyc_scid'));
		$scraper_class = $scraper_data['class'];

		/* load lib class */
		$this->load->library('scraper/'.$scraper_class);

		if($this->config->item('use_tor')){
			$proxy_status = tor_new_id($this->config->item('proxy_control_url'), $this->config->item('proxy_control_port'), $this->config->item('proxy_control_auth'));
			$proxy = $this->config->item('proxy_url').':'.$this->config->item('proxy_port');
		}else{
			$proxy_list = get_proxy_list();
			$proxy = $proxy_list[array_rand($proxy_list, 1)];
			$proxy_status = TRUE; 
		}
		
		$i = 1;
		foreach($i_account as $i_val){
			if($i == 3){
				$i = 0;
				if($this->config->item('use_tor')){
					$proxy_status = tor_new_id($this->config->item('proxy_control_url'), $this->config->item('proxy_control_port'), $this->config->item('proxy_control_auth'));
					$proxy = $this->config->item('proxy_url').':'.$this->config->item('proxy_port');
				}else{
					$proxy_list = get_proxy_list();
					$proxy = $proxy_list[array_rand($proxy_list, 1)];
					$proxy_status = TRUE; //check_proxy_live($proxy);
				}
			}

			if($proxy_status){
				$account_data = $this->account_model->read($i_val);

				$query = parse_url($i_article, PHP_URL_QUERY);
				parse_str($query);
				if(isset($id)&&empty($id)==FALSE){
					/* run crawler */
					$this->{$scraper_class}->input = array(
						'username' => $account_data['ycu_username'],
						'password' => $account_data['ycu_password'],
						'upvote_number' => $this->config->item('app_upvote_number'));
					$this->{$scraper_class}->set_proxy($proxy);
					$this->{$scraper_class}->set_cookie_file($this->config->item('cookies_path').'ycombinator'.$this->session->userdata('user_id').'.txt');
					$this->{$scraper_class}->single_upvote($i_article);
					$response = $this->{$scraper_class}->output;
					$this->{$scraper_class}->reset();

					$return_val = TRUE;
					if($response['error']){
						$return_val = FALSE;
					}
					$success_resp .= $response['error_msg'].'<br/>';
					$log_info = $fail_resp = $success_resp;
				} else {
					$return_val = FALSE;

					$fail_resp .= "Article ID ($url) not found.<br/>";
					$log_info = $fail_resp;	
				}
			} else {
				$return_val = FALSE;

				$fail_resp .= 'Proxy Error. Please, Try Again.<br/>';
				$log_info = $fail_resp;
			}

			$i++;
		}

		/* write db log */
		$this->logger->write($this->config->item('log_bulk_upvoting'), $log_info);

		send_json_action($return_val, $success_resp, $fail_resp);
	}
}