<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author: Debi Praharadika
 */
class user extends CI_Controller {
	private $data;

	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'User',
			'small_content_header' => 'Setting',
			'content' => '');

		$this->load->model('user_model');
	}

	function index(){ 
		if(check_user_session() == FALSE) redirect('auth/logout');

		$this->data['content'] = $this->load->view('admin/user/form', NULL, TRUE);
		$this->load->view('admin/home', $this->data);
	}

	function get_data(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', '<tr><td colspan="4">Authorization Failed, You will redirect to login page.</td></tr>');

		$data = $this->user_model->read();

		$table = '';
		if(count($data) > 0){
			foreach($data as $val){
				$table .= $this->load->view('admin/user/row', $val, TRUE);
			}
		} else {
			$table .= $this->load->view('admin/user/row_0', NULL, TRUE);
		}

		send_json_action(TRUE, $table);
	}

	function submit(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');		

		$this->form_validation->set_rules('i-fullname', 'Fullname', 'required|trim');
		$this->form_validation->set_rules('i-email', 'Email Address', 'required|trim');
		$this->form_validation->set_rules('i-username', 'Username', 'required|trim|callback_username_check');
		$this->form_validation->set_rules('i-password', 'Password', 'required|trim|min_length[6]');
		if ($this->form_validation->run() == FALSE) send_json_validate();

		$password = md5($this->input->post('i-password'));
		$data = array(
			'user_name' => $this->input->post('i-username'),
			'user_password' => $password,
			'user_fullname' => $this->input->post('i-fullname'),
			'user_email' => $this->input->post('i-email'));

		$return_val = $this->user_model->create($data);

		/* write log data */
		$msg = ($return_val) ? 'Success Added '.$data['user_name'] : 'Failed Added '.$data['user_name'];
		$this->logger->write($this->config->item('log_user'), $msg);

		send_json_action($return_val, $msg, $msg);
	}

	function enable($user_id){
		if($this->user_model->read_where(array('user_id'=>$user_id)) !== FALSE){
			$return_val = $this->user_model->update(array('user_lock'=>'0'), $user_id);
		} else {
			$return_val = FALSE;
		}

		/* write log db */
		$msg = ($return_val) ? "User($user_id) Enabled Success" : "User($user_id) Enabled Failed";
		$this->logger->write($this->config->item('log_user'), $msg);

		send_json_action($return_val, $msg, $msg);
	}

	function disable($user_id){
		if($this->user_model->read_where(array('user_id'=>$user_id)) !== FALSE){
			$return_val = $this->user_model->update(array('user_lock'=>'1'), $user_id);
		} else {
			$return_val = FALSE;
		}

		/* write log db */
		$msg = ($return_val) ? "User($user_id) Disabled Success" : "User($user_id) Disabled Failed";
		$this->logger->write($this->config->item('log_user'), $msg);

		send_json_action($return_val, $msg, $msg);
	}

	function username_check($str){		
		$user_account_data = $this->user_model->read_username($str);
		if ($str == $user_account_data['user_name']){
			$this->form_validation->set_message('username_check', '%s is not available.');
			return FALSE;
		} else {
			return TRUE;
		}
	}

}