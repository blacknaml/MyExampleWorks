<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author: Debi Praharadika
 */
class log extends CI_Controller {
	private $data;

	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'Log',
			'small_content_header' => 'System',
			'content' => '');

		$this->load->model('alog_model');
	}

	function index(){ 
		if(check_user_session() == FALSE) redirect('auth/logout');

		$data = $this->alog_model->read_code();
		foreach($data as $val){
			$token[] = array('label' => $val['code'], 'value' => $val['code']);
			$comma_token[] = $val['code'];
		}
		$json_token = json_encode($token);
		$comma_token = implode(',', $comma_token);

		$this->data['css'] = add_css('bootstrap-tokenfield/tokenfield-typeahead.min.css');
		$this->data['css'] .= add_css('bootstrap-tokenfield/bootstrap-tokenfield.min.css');
		$this->data['css'] .= add_css('bootstrap-datepicker/datepicker.css');

		$this->data['js'] = add_js('plugins/bootstrap-typehead/typeahead.bundle.min.js');
		$this->data['js'] .= add_js('plugins/bootstrap-tokenfield/bootstrap-tokenfield.min.js');
		$this->data['js'] .= add_js('plugins/bootstrap-datepicker/bootstrap-datepicker.js');
		
		$content = array('token' => $json_token, 'token_val' => $comma_token, 'now' => date('d/m/Y'));

		$this->data['content'] = $this->load->view('admin/log/form', $content, TRUE);
		$this->load->view('admin/home', $this->data);
	}

	function get_data(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', '<tr><td colspan="4">Authorization Failed, You will redirect to login page.</td></tr>');

		$table = '';

		$this->form_validation->set_rules('i-date-1', 'Date From', 'required|trim|sql_date');
		$this->form_validation->set_rules('i-date-2', 'Date To', 'required|trim|sql_date');
		$this->form_validation->set_rules('i-tags', 'Tags', 'required|trim');
		if ($this->form_validation->run() == FALSE){ 
			$table .= $this->load->view('admin/log/row_0', array('message'=>'Error Validation'), TRUE);
		} else {
			$date_1 = $this->input->post('i-date-1');
			$date_2 = $this->input->post('i-date-2');
			$tags = $this->input->post('i-tags');
			$tags = explode(',', $tags);
			$tags = array_map('trim', $tags);

			$log_data = $this->alog_model->read_where($date_1, $date_2, $tags);
			if($log_data === FALSE){
				$table .= $this->load->view('admin/log/row_0', array('message'=>'No Data Loaded'), TRUE);
			} else {
				$i = 1;
				foreach($log_data as $val){
					$val['i'] = $i;
					$val['alog_date'] = date('d/m/Y H:i', strtotime($val['alog_date']));
					$table .= $this->load->view('admin/log/row', $val, TRUE);
					$i++;
				}
			}
		}

		send_json_action(TRUE, $table, '');
	}
}