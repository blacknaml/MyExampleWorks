<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author: Debi Praharadika
 */
class register extends CI_Controller {
	private $data;

	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'Registration',
			'small_content_header' => 'Account',
			'content' => '');

		$this->load->model('general_model');
	}

	function index() {
		if(check_user_session() == FALSE) redirect('auth/logout');

		$form['scraper_cbo'] = $this->general_model->cbo_scraper_category();

		$this->data['content'] = $this->load->view('admin/register/form', $form, TRUE);
		
		$this->load->view('admin/home', $this->data);
	}

	function get_data(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', '<tr><td colspan="4">Authorization Failed, You will redirect to login page.</td></tr>');

		$table = '';

		$this->form_validation->set_rules('i_category', 'Scraper category', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE){ 
			$table .= $this->load->view('admin/register/row_0', NULL, TRUE);
		} else {
			$this->load->model('account_model');

			$yc_id = $this->input->post('i_category');

			$data = $this->account_model->read_by_scid($yc_id, $this->session->userdata('user_id'), FALSE);
			if(count($data) > 0){
				$i = 1;
				foreach($data as $val){
					$val['i'] = $i;
					$table .= $this->load->view('admin/register/row', $val, TRUE);
					$i++;
				}
			} else {
				$table .= $this->load->view('admin/register/row_0', NULL, TRUE);
			}
		}
		send_json_action(TRUE, $table);
	}

	function set_disable(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('id', 'Row ID', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE) send_json_validate();

		$this->load->model('account_model');

		$id = $this->input->post('id');
		$return_val = $this->account_model->lock($id);

		$msg = ($return_val) ? "User Account ($id) is Disabled" : "User Account ($id) disable Failed";

		/* write db log */
		$this->logger->write($this->config->item('log_registration'), $msg);

		send_json_action($return_val, $msg, $msg);	
	}

	function set_enable(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('id', 'Row ID', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE) send_json_validate();

		$this->load->model('account_model');

		$id = $this->input->post('id');
		$return_val = $this->account_model->unlock($id);

		$msg = ($return_val) ? "User Account ($id) is Enabled" : "User Account ($id) enable Failed";

		/* write db log */
		$this->logger->write($this->config->item('log_registration'), $msg);

		send_json_action($return_val, 'User Account is Enabled', 'User Failed Enable');	
	}

	function submit() {
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('i-category', 'Scraper category', 'required|trim|integer');
		$this->form_validation->set_rules('i-username', 'Username', 'required|trim');
		$this->form_validation->set_rules('i-password', 'Password', 'required|trim');
		if ($this->form_validation->run() == FALSE) send_json_validate();
		
		$sc_id = $this->input->post('i-category');
		$scraper_data = $this->general_model->get_scraper_class($sc_id);
		$scraper_class = $scraper_data['class'];

		$this->load->library('scraper/'.$scraper_class);

		if($this->config->item('use_tor')){
			$return_val = tor_new_id($this->config->item('proxy_control_url'), $this->config->item('proxy_control_port'), $this->config->item('proxy_control_auth'));
			$proxy = $this->config->item('proxy_url').':'.$this->config->item('proxy_port');
		}else{
			$proxy_list = get_proxy_list();
			$proxy = $proxy_list[array_rand($proxy_list, 1)];
			$return_val = TRUE; //check_proxy_live($proxy);
		}

		if($return_val){
			$this->{$scraper_class}->input = array(
				'username' => $this->input->post('i-username'),
				'password' => $this->input->post('i-password'));
			$this->{$scraper_class}->set_proxy($proxy);
			$this->{$scraper_class}->set_cookie_file($this->config->item('cookies_path').'ycombinator'.$this->session->userdata('user_id').'.txt');
			$this->{$scraper_class}->send('register');
			$response = $this->{$scraper_class}->output;

			$return_val = FALSE;
			if($response['error'] == FALSE){
				$this->load->model('account_model');

				$data = array('user_id' => $this->session->userdata('user_id'),
					'ycu_username' => $this->input->post('i-username'),
					'ycu_password' => $this->input->post('i-password'),
					'sc_id' => $this->input->post('i-category'));

				/* write db log */
				$log_msg = $this->input->post('i-username').' registered success with '.$this->input->post('i-password');

				$return_val = $this->account_model->create($data);
			} else {
				$log_msg = $response['error_msg'];
			}

			$return_val = ($response['error']) ? FALSE : $return_val ;
		} else {
			$response['error_msg'] = "Proxy $proxy Server can not get IP.";
			$log_msg = $response['error_msg'];
		}
		$this->logger->write($this->config->item('log_registration'), $log_msg);
		send_json_action($return_val, 'Success registered '.$this->input->post('i-username'), 'Fail. '.$response['error_msg'], $response);
	}

	function category_change() {
		if(check_user_session() == FALSE) send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('i-category', 'Scraper category', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE) {
			send_json_validate();
		} else {
			$ok = FALSE;
			$scraper_params = array();
			$data['scraper_cbo'] = $this->general_model->cbo_scraper_category();

			$sc_id = $this->input->post('i-category');
			$data['sc_id'] = $sc_id;
			if($sc_id > 0){
				$scraper_data = $this->general_model->get_scraper_class($sc_id);
				if(empty($scraper_data['class']) == FALSE){
					$ok = TRUE;

					$scraper_class = strtolower($scraper_data['class']);
					$scraper_params['form'] = $this->load->view('admin/register/form_'.$scraper_class, $data, TRUE);
				}
			} else {
				$ok = TRUE;
				$scraper_params['form'] = $this->load->view('admin/register/form_0', $data, TRUE);
			}

			send_json_action($ok, 'Changed', 'Not Changed', $scraper_params);
		}	
	}
}