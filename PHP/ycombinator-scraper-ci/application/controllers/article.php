<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author: Debi Praharadika
 */
class article extends CI_Controller {
	private $data;

	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'Article',
			'small_content_header' => 'Post New',
			'content' => '');

		$this->load->model('general_model');
		$this->load->model('account_model');
	}

	function index() {
		if(check_user_session() == FALSE) redirect('auth/logout');

		$form['scraper_cbo'] = $this->general_model->cbo_scraper_category();
		$form['account_cbo'] = array('---');

		$this->data['content'] = $this->load->view('admin/article/form', $form, TRUE);
		
		$this->load->view('admin/home', $this->data);
	}

	function category_change(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('i-category', 'Scraper category', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE) {
			send_json_validate();
		} else {
			$user_id = $this->session->userdata('user_id');
			$sc_id = $this->input->post('i-category');
			$account_data = $this->account_model->read_by_scid($sc_id, $user_id);

			$account_option = '<option value="0">---</option>';
			foreach($account_data as $val){
				$account_option .= '<option value="'.$val['ycu_id'].'">';
				$account_option .= $val['ycu_username'];
				$account_option .= '</option>';
			}
			$ok = count($account_data) > 0;

			send_json_action($ok, 'Changed', 'Not Changed', array('opt' => $account_option));
		}
	}

	function submit(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('i-category', 'Scraper category', 'required|trim|integer');
		$this->form_validation->set_rules('i-account', 'Account', 'required|trim|integer');
		$this->form_validation->set_rules('i-title', 'Article Title', 'required|trim|max_length[80]');
		$this->form_validation->set_rules('i-article-url', 'Article Url', 'required|trim|valid_url_format|url_exists');
		$this->form_validation->set_rules('i-content', 'Article Content', 'trim');
		if ($this->form_validation->run() == FALSE) send_json_validate();
		
		/* Read Scraper Category for load library class */
		$fail_resp = 'Failed. ';
		$success_resp = 'Success. ';

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
		if($proxy_status){
			/* run crawler */
			$this->{$scraper_class}->input = array(
				'username' => $account_data['ycu_username'],
				'password' => $account_data['ycu_password'],
				'article_title' => $this->input->post('i-title'),
				'article_url' => $this->input->post('i-article-url'),
				'article_content' => $this->input->post('i-content'));
			$this->{$scraper_class}->set_proxy($proxy);
			$this->{$scraper_class}->set_cookie_file($this->config->item('cookies_path').'ycombinator'.$this->session->userdata('user_id').'.txt');
			$this->{$scraper_class}->send('submit');
			$response = $this->{$scraper_class}->output;

			/* send response to client */
			if($response['error'] == FALSE){
				$this->load->model('article_model');
				$data = array(
					'ycu_id' => $account_data['ycu_id'],
					'yca_title' => $this->input->post('i-title'),
					'yca_url' => $this->input->post('i-article-url'),
					'yca_content' => $this->input->post('i-content'),
					'yca_success' => '1');
				$return_val = $this->article_model->create($data);
				$success_resp .= 'Created article "'.$this->input->post('i-title').'"';
				$log_msg = $account_data['ycu_username'].': '.$success_resp;

			} else {
				$fail_resp .= $response['error_msg'];
				$log_msg = $account_data['ycu_username'].': '.$fail_resp;

				$return_val = FALSE;
			}
		} else {
			$fail_resp .= 'Proxy Error. Please, Try Again.';
			$log_msg = $fail_resp;

			$return_val = FALSE;
		}
		/* write db log */
		$this->logger->write($this->config->item('log_submit'), $log_msg);
		send_json_action($return_val, $success_resp, $fail_resp, $response);
	}
}