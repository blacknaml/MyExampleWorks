<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * @author: Debi Praharadika
 */
class schedule_comment extends CI_Controller {
	private $data;

	function __construct() {
		parent::__construct();

		$this->data = array(	
			'content_header' => 'Schedule Comment to Article',
			'small_content_header' => 'Comment',
			'content' => '');

		$this->load->library('parsecsv');

		$this->load->model('general_model');
		$this->load->model('account_model');
		$this->load->model('comment_model');
	}

	function index() {
		if(check_user_session() == FALSE) redirect('auth/logout');
		
		$form = array('now' => date('d/m/Y'));
		$this->data['content'] = $this->load->view('admin/schedule_comment/form', $form, TRUE);
		$this->data['js'] = add_js('plugins/jquery-form/jquery.form.min.js');
		$this->data['js'] .= add_js('plugins/daterangepicker/daterangepicker1.3.19.js');
		$this->data['css'] = add_css('daterangepicker/daterangepicker-bs3.css');
		
		$this->load->view('admin/home', $this->data);
	}

	function get_history(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', '<tr><td colspan="5">Authorization Failed, You will redirect to login page.</td></tr>');

		$table = '';

		$this->form_validation->set_rules('i-date-1', 'Date From', 'required|trim|sql_date');
		$this->form_validation->set_rules('i-date-2', 'Date To', 'required|trim|sql_date');		
		if ($this->form_validation->run() == FALSE){ 
			$table .= $this->load->view('admin/schedule_comment/history/row_0', array('message'=>'Error Validation'), TRUE);
		} else {
			$date_1 = $this->input->post('i-date-1');
			$date_2 = $this->input->post('i-date-2');
			
			$log_data = $this->comment_model->read_bg_all($date_1, $date_2);
			if($log_data === FALSE){
				$table .= $this->load->view('admin/schedule_comment/history/row_0', array('message'=>'No Data Loaded'), TRUE);
			} else {
				$i = 1;
				$ind_css = 0;
				foreach($log_data as $val){
					switch ($val['ycc_sent']) {
						case '0':
							$status = '---';
							$color = 'class="text-primary"';
							break;
						case '1':
							$status = 'Success';
							$color = 'class="text-success"';
							break;
						case '2':
							$status = 'Failed';
							$color = 'class="text-danger"';
							break;
					}

					if($val['ycc_pid'] == 0)
						$ind_css = 0;
					else
						$ind_css++;

					$val['i'] = $i;
					$val['ycc_schedule'] = date('d/m/Y H:i', strtotime($val['ycc_schedule']));
					$val['status'] = $status;
					$val['class'] = 'class="td-f'.$ind_css.'"';
					$val['color'] = $color;
					$table .= $this->load->view('admin/schedule_comment/history/row', $val, TRUE);
					$i++;
				}
			}
		}

		send_json_action(TRUE, $table, '');
	}

	function upload(){
		$config['upload_path'] = $this->config->item('csv_folder');
		$config['allowed_types'] = 'csv';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);

		$return_val = $this->upload->do_upload('i-csv');
		if($return_val){
			$data = $this->upload->data();
			$this->parsecsv->auto($data['full_path']);
			if(multiarray_key_exists('flag', $this->parsecsv->data) && multiarray_key_exists('comment', $this->parsecsv->data)){
				$params['fn'] = $data['file_name'];
			} else {
				$return_val = FALSE;
				$params['error'] = 'The file have wrong csv structure.';
			}
		} else {
			$params = array('error' => $this->upload->display_errors());
		}

		send_json_action($return_val, 'Ok', 'Fail', $params);
	}

	function submit(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('i-fn', 'CSV File', 'required|trim');
		$this->form_validation->set_rules('i-article', 'Article Url', 'required|trim|valid_url_format|url_exists');
		if ($this->form_validation->run() == FALSE) send_json_validate();

		$csv_file = $this->config->item('csv_folder').$this->input->post('i-fn');
		$article_url = $this->input->post('i-article');

		/* Get Account */
		$user_id = $this->session->userdata('user_id');
		$account_data = $this->account_model->read_by_scid($this->config->item('app_nyc_scid'), $user_id);
		foreach($account_data as $val){
			$account_cbo[$val['ycu_id']] = $val['ycu_username'];
		}
		
		$row = '';
		$this->parsecsv->auto($csv_file);
		foreach ($this->parsecsv->data as $key => $value) {
			if($value['flag'] == 'f') $i = 0;
			if($value['flag'] == 't') $i++;

			$value['class'] = 'class="td-f'.$i.'"';
			$value['account'] = $account_cbo;
			$value['date_time'] = date('d/m/Y H:i:s');
			$row .= $this->load->view('admin/schedule_comment/row', $value, TRUE);
		}
		$content = $this->load->view('admin/schedule_comment/table', array('row' => $row, 'article_url' => $article_url), TRUE);

		send_json_action(TRUE, $content);
	}

	function save(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', 'Authorization Failed, You will redirect to login page.');

		$this->form_validation->set_rules('i-comment[]', 'Comment', 'required|trim');
		$this->form_validation->set_rules('i-datetime[]', 'Datetime', 'required|trim');
		$this->form_validation->set_rules('i-flag[]', 'Flag', 'required|trim');
		$this->form_validation->set_rules('i-account[]', 'Account', 'required|trim|integer');
		$this->form_validation->set_rules('i-article', 'Article URL', 'required|trim');
		if ($this->form_validation->run() == FALSE) send_json_validate();

		$comment = $this->input->post('i-comment');
		$flag = $this->input->post('i-flag');
		$datetime = $this->input->post('i-datetime');
		$account = $this->input->post('i-account');
		$article_url = $this->input->post('i-article');
		$ycu_pid = 0;

		for($i = 0; $i < count($flag); $i++){
			$data = array(
				'ycc_pid' => $ycu_pid,
				'ycu_id' => $account[$i],
				'ycc_schedule' => date('Y-m-d H:i:s', strtotime($datetime[$i])),
				'ycc_url' => '',
				'ycc_comment' => $comment[$i],
				'ycc_bg' => 1);
			
			if($i == 0 || $flag[$i] == 'f'){
				$data['ycc_url'] = $article_url;
				$data['ycc_pid'] = 0;
			}

			$ycu_pid = $this->comment_model->create($data, TRUE);
			$this->logger->write($this->config->item('log_sche_comments'), json_encode($data));
		}

		send_json_action(TRUE, 'OK', 'Fail', $data);	
	}
}