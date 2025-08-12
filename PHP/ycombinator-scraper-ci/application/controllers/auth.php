<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author: Debi Praharadika
 */
class auth extends CI_Controller {
	
	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
	}

	function index(){ $this->load->view('login', NULL);}

	function login(){
		$this->form_validation->set_rules('i-username', 'Username', 'trim|required');
		$this->form_validation->set_rules('i-password', 'Password', 'trim|required');
		if($this->form_validation->run() === FALSE){ send_json_validate();}

		$i_username = $this->input->post('i-username');
		$i_password = $this->input->post('i-password');

		$password = md5($i_password);
		$account = $this->user_model->read_where(array(
			'user_name' => $i_username, 
			'user_password' => $password,
			'user_lock' => '0'));
		if($account == FALSE){
			$msg = "$i_username failed login.";
			$this->logger->write($this->config->item('log_auth_fail'), $msg);

			send_json_error("Invalid username or password.");
		} else {
			$this->session->set_userdata(array(
				'is_login' => TRUE, 
				'user_id' => $account['user_id'],
				'username' => $account['user_name'], 
				'log_time' => date('Y-m-d H:i:s'),
				));

			$this->logger->write($this->config->item('log_login'), "$i_username login.");
			
			send_json_redirect('home', 'Login Succes');
		}
	}
	function logout(){
		$msg = $this->session->userdata('username')." logout.";
		$this->logger->write($this->config->item('log_logout'), $msg);

		$array_items = array('is_login' => '', 'username' => '', 'user_id' => '', 'log_time' => '');
		$this->session->unset_userdata($array_items);

		redirect('auth');
	}
}