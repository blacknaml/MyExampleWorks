<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author: Debi Praharadika
 */
class account_check extends CI_Controller {
	private $data;

	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'Account Checking',
			'small_content_header' => 'Account',
			'content' => '');

		$this->load->model('general_model');
	}

	function index() {
		if(check_user_session() == FALSE) redirect('auth/logout');

		$form['scraper_cbo'] = $this->general_model->cbo_scraper_category();

		$this->data['content'] = $this->load->view('admin/account_check/form', $form, TRUE);
		
		$this->load->view('admin/home', $this->data);
	}

	function get_data(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', '<tr><td colspan="4">Authorization Failed, You will redirect to login page.</td></tr>');

		$table = '';

		$this->form_validation->set_rules('i_category', 'Scraper category', 'required|trim|integer');
		$this->form_validation->set_rules('i_proxy', 'Use Proxy', 'required|trim');
		$this->form_validation->set_rules('pos', 'Position', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE){ 
			$table .= $this->load->view('admin/account_check/row_0', array('message'=>'Error Validation'), TRUE);
		} else {
			$this->load->model('account_model');

			$limit = 50;
			$pos = $this->input->post('pos');
			$sc_id = $this->input->post('i_category');
			$use_proxy = $this->input->post('i_proxy');

			$data = $this->account_model->read_by_scid($sc_id, $this->session->userdata('user_id'), true, $pos, $limit);
			if(count($data) > 0){
				/* get scraper information */
				$scraper_data = $this->general_model->get_scraper_class($sc_id);
				$scraper_class = $scraper_data['class'].'_api';

				/* load lib class */
				$this->load->library('scraper/'.$scraper_class);

				/* check login */
				$i = ($pos+1);
				foreach($data as $val){
					$response = array();

					/* run crawler */
					$this->{$scraper_class}->user($val['ycu_username']);
					$response = $this->{$scraper_class}->output;
					$this->{$scraper_class}->reset();

					/* data row */
					$val['i'] = $i;
					$val['error'] = $response['error'];
					$val['error_message'] = empty($response['error_msg']) ? 'OK' : $response['error_msg'];
					$val['error_message'] = $val['ycu_username'].' : '.$val['ycu_password'].' - '.$val['error_message'];

					/* generate row */
					$table .= $this->load->view('admin/account_check/row', $val, TRUE);
					$log_msg = $val['error_message'];

					/* row count (number) */
					$i++;
				}
				/* load more button */
				$table .= $this->load->view('admin/account_check/more', array('pos' => ($pos + $limit)), TRUE);
			} else {
				$log_msg = 'No Data';
				$table .= $this->load->view('admin/account_check/row_0', array('message'=>$log_msg), TRUE);
			}
		}
		/* write db log */
		$this->logger->write($this->config->item('log_checking_account'), $log_msg);
		send_json_action(TRUE, $table, '', array('pos'=>$pos, 'limit'=>$limit));
	}

	function play() {
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$table = '';

		$this->form_validation->set_rules('id', 'Row Id', 'required|trim|integer');
		$this->form_validation->set_rules('i_category', 'Scraper category', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE){ 
			$table .= $this->load->view('admin/account_check/row_0', array('message'=>'Error Validation'), TRUE);
		} else {
			$sc_id = $this->input->post('i_category');
			$scraper_data = $this->general_model->get_scraper_class($sc_id);
			$scraper_class = $scraper_data['class'];

			$this->load->library('scraper/'.$scraper_class);

			if($this->config->item('use_tor')){
				$proxy_status = tor_new_id($this->config->item('proxy_control_url'), $this->config->item('proxy_control_port'), $this->config->item('proxy_control_auth'));
				$proxy = $this->config->item('proxy_url').':'.$this->config->item('proxy_port');
			}else{
				$proxy_list = get_proxy_list();
				$proxy = $proxy_list[array_rand($proxy_list, 1)];
				$proxy_status = TRUE;
			}
			if($proxy_status){
				$ycu_id = $this->input->post('id');

				$this->load->model('account_model');
				$account_data = $this->account_model->read($ycu_id);

				/* crawl */
				$this->{$scraper_class}->input = array(
					'username' => $account_data['ycu_username'],
					'password' => $account_data['ycu_password']);
				$this->{$scraper_class}->set_proxy($proxy);
				$this->{$scraper_class}->set_cookie_file($this->config->item('cookies_path').'ycombinator_reg_'.$this->session->userdata('user_id').'.txt');
				$this->{$scraper_class}->send('register');
				$response = $this->{$scraper_class}->output;

				$account_data['error'] = $response['error'];
				$account_data['error_message'] = ($response['error']) ? empty($response['error_msg']) ? '---' : $response['error_msg'].$proxy : 'OK' ;

				$log_msg = $account_data['error_message'];				

				/* generate row */
				$table .= $this->load->view('admin/account_check/td', $account_data, TRUE);

			} else {
				$log_msg = 'Proxy Error. Please, Try Again.';
				$table .= $this->load->view('admin/account_check/row_0', array('message'=>$log_msg), TRUE);
			}
		}
		$this->logger->write($this->config->item('log_reregistration'), $log_msg);
		send_json_action(TRUE, $table);
	}
}