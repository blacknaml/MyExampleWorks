<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author: Debi Praharadika
 */
class profile extends CI_Controller {
	
	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'Profile',
			'small_content_header' => 'User',
			'content' => '');

		$this->load->model('user_model');
	}

	function index(){ 
		if(check_user_session() == FALSE) redirect('auth/logout');

		$user = $this->user_model->read_where(array('user_id'=>$this->session->userdata('user_id')));

		$this->data['content'] = $this->load->view('admin/profile/form', $user, TRUE);
		$this->load->view('admin/home', $this->data);
	}

	function submit(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('i-fullname', 'Fullname', 'required|trim');
		$this->form_validation->set_rules('i-email', 'Email Address', 'required|trim');
		$this->form_validation->set_rules('i-oldpassword', 'Current Password', 'required|trim|callback_password_check');
		$this->form_validation->set_rules('i-newpassword', 'New Password', 'trim|min_length[6]');
		if ($this->form_validation->run() == FALSE) send_json_validate();

		$data = array(
			'user_fullname' => $this->input->post('i-fullname'),
			'user_email' => $this->input->post('i-email'));

		$i_newpassword = $this->input->post('i-newpassword');
		if(empty($i_newpassword)==FALSE) $data['user_password'] = md5($i_newpassword);

		$return_val = $this->user_model->update($data, $this->session->userdata('user_id'));

		$msg = 'Profile edited, You will redirect to login page.';

		$this->logger->write($this->config->item('log_profile'), $msg);

		send_json_redirect('auth/logout', $msg);
	}

	function password_check($str){
		$user_account_data = $this->user_model->read_where(array('user_id'=>$this->session->userdata('user_id')));
		if (md5($str) == $user_account_data['user_password']){
			return TRUE;
		} else {
			$this->form_validation->set_message('password_check', 'Wrong %s.');
			return FALSE;
		}
	}

}