<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author: Debi Praharadika
 */
class upvoting extends CI_Controller {
	private $data;

	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'Randow Upvoting',
			'small_content_header' => 'Voting',
			'content' => '');

		$this->load->model('general_model');
		$this->load->model('account_model');
	}

	function index() {
		if(check_user_session() == FALSE) redirect('auth/logout');

		$form['scraper_cbo'] = $this->general_model->cbo_scraper_category();
		$form['account_cbo'] = array('---');

		$this->data['content'] = $this->load->view('admin/upvoting/form', $form, TRUE);
		
		$this->load->view('admin/home', $this->data);
	}

	function category_change(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('i-category', 'Scraper category', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE) send_json_validate();
		
		$user_id = $this->session->userdata('user_id');
		$sc_id = $this->input->post('i-category');
		$account_data = $this->account_model->read_by_scid($sc_id, $user_id);

		$account_option = '<option value="0">---</option>';
		foreach($account_data as $val){
			$account_option .= '<option value="'.$val['ycu_id'].'">';
			$account_option .= $val['ycu_username'];
			$account_option .= '</option>';
		}
		$ok = count($account_option>1);

		send_json_action($ok, 'Changed', 'Not Changed', array('opt' => $account_option));	
	}

	function submit(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('i-category', 'Scraper category', 'required|trim|integer');
		$this->form_validation->set_rules('i-account', 'Username', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE) send_json_validate();
		
		/* Read Scraper Category for load library class */
		$sc_id = $this->input->post('i-category');
		$scraper_data = $this->general_model->get_scraper_class($sc_id);
		$scraper_class = $scraper_data['class'];

		/* load lib class */
		$this->load->library('scraper/'.$scraper_class);

		/* Read account data detail include username and password */
		$ycu_id = $this->input->post('i-account');
		$account_data = $this->account_model->read($ycu_id);

		if($this->config->item('use_tor')){
			$proxy_status = tor_new_id($this->config->item('proxy_control_url'), $this->config->item('proxy_control_port'), $this->config->item('proxy_control_auth'));
			$proxy = $this->config->item('proxy_url').':'.$this->config->item('proxy_port');
		}else{
			$proxy_list = get_proxy_list();
			$proxy = $proxy_list[array_rand($proxy_list, 1)];
			$proxy_status = TRUE; //check_proxy_live($proxy);
		}

		/* run crawler */
		$success_resp = '';
		$failed_resp = 'Failed. ';
		if($proxy_status){
			/* run crawler */
			$this->{$scraper_class}->input = array(
				'username' => $account_data['ycu_username'],
				'password' => $account_data['ycu_password'],
				'upvote_number' => $this->config->item('app_upvote_number'));
			$this->{$scraper_class}->set_proxy($proxy);
			$this->{$scraper_class}->set_cookie_file($this->config->item('cookies_path').'ycombinator'.$this->session->userdata('user_id').'.txt');
			$this->{$scraper_class}->send('upvote');
			$response = $this->{$scraper_class}->output;

			/* send response to client */
			if($response['error'] == FALSE){
				$this->load->model('voting_model');
				$data = array(
					'ycu_id' => $account_data['ycu_id'],
					'ycv_date' => date('Y-m-d H:i:s'),
					'ycv_url' => $response['upvote_url']);
				$return_val = $this->voting_model->create($data);
				$success_resp .= $account_data['ycu_username']." has been succeeded upvoting ".$response['upvote_url'];
				$log_msg = $success_resp;
			} else {
				$return_val = FALSE;
				$failed_resp .= $response['error_msg'];
				$log_msg = $account_data['ycu_username'].'. '.$failed_resp;
			}
		} else {
			$return_val = FALSE;
			$failed_resp .= 'Proxy Error. Please, Try Again.';
			$log_msg = $failed_resp;
		}
		$this->logger->write($this->config->item('log_rand_upvoting'), $log_msg);
		send_json_action($return_val, $success_resp, $failed_resp);
		
	}
}