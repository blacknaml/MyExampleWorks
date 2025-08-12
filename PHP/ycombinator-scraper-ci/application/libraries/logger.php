<?php defined('BASEPATH') OR exit('No direct script access allowed');

class logger {
	protected $_ci;

	function __construct()
	{
		$this->_ci = & get_instance();

		$this->_ci->load->model('alog_model');
		
		log_message('info', 'Logger active.');
	}

	public function write($code, $logs, $user_id = NULL){
		$user_id = (is_null($user_id)) ? $this->_ci->session->userdata('user_id') : $user_id;
		$data = array(
			'user_id' => $user_id,
			'alog_code' => $code,
			'alog_ip' => $this->_ci->input->ip_address(),
			'alog_information' => $logs);
		
		$return_val = $this->_ci->alog_model->create($data);

		if($return_val){
			$data_txt = print_r($data, TRUE);
			log_message('error', 'Error wrote log.'.$data_txt);
		} else {
			log_message('info', 'Success wrote log.');
		}
	}

	public function read($id=null){
		$data = array();

		$data = $this->_ci->alog_model->read($id);

		return $data;
	}
}