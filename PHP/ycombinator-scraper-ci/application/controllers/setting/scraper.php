<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author: Debi Praharadika
 */
class scraper extends CI_Controller {
	
	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'Scraper',
			'small_content_header' => 'Libraries',
			'content' => '');

		$this->load->model('scraper_model');
	}

	function index(){ 
		if(check_user_session() == FALSE) redirect('auth/logout');		

		$this->data['content'] = $this->load->view('admin/scraper/form', NULL, TRUE);

		$this->load->view('admin/home', $this->data);
	}

	function get_data(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', '<tr><td colspan="2">Authorization Failed, You will redirect to login page.</td></tr>');

		$data = $this->scraper_model->read();

		$table = '';
		if(count($data) > 0){
			foreach($data as $val){
				$table .= $this->load->view('admin/scraper/row', $val, TRUE);
			}
		} else {
			$table .= $this->load->view('admin/scraper/row_0', NULL, TRUE);
		}

		send_json_action(TRUE, $table);
	}

	function submit(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');		

		$this->form_validation->set_rules('i-name', 'Scraper Name', 'required|trim');
		$this->form_validation->set_rules('i-class', 'Scraper Class', 'required|trim');
		if ($this->form_validation->run() == FALSE) send_json_validate();

		$data = array(
			'sc_name' => $this->input->post('i-name'),
			'sc_class' => $this->input->post('i-class'));

		$return_val = $this->scraper_model->create($data);

		$msg = ($return_val) ? 'Success create scraper '.$data['sc_name'] : 'Failed create scraper '.$data['sc_name'];
		$this->logger->write($this->config->item('log_create_scraper'), $msg);

		send_json_action($return_val, $msg, $msg);
	}

}