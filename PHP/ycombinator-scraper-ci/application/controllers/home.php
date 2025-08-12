<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * @author: Debi Praharadika
 */
class home extends CI_Controller {
	private $group_permission;

	function __construct() {
		parent::__construct();

		$this->data = array(
			'content_header' => 'Home',
			'small_content_header' => 'Navigation',
			'content' => '');

		$this->load->model('item_model');
		$this->load->model('top_model');
		$this->load->library('scraper/ycombinator_api');
	}

	function index(){
		if(check_user_session() == FALSE) redirect('auth/logout');

		$list = array('now' => date('d/m/Y'), 'last_week' => date('d/m/Y', strtotime('-1 week')));
		$this->data['content'] = $this->load->view('admin/navigation/list', $list, TRUE);	

		$this->data['js'] = add_js('plugins/jquery-form/jquery.form.min.js');
		$this->data['js'] .= add_js('plugins/daterangepicker/daterangepicker1.3.19.js');
		$this->data['css'] = add_css('daterangepicker/daterangepicker-bs3.css');

		$this->load->view('admin/home', $this->data);
	}

	function get_data(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', '<tr><td colspan="3">Authorization Failed, You will redirect to login page.</td></tr>');

		$table = '';

		$this->form_validation->set_rules('i-date-1', 'Date From', 'required|trim|sql_date');
		$this->form_validation->set_rules('i-date-2', 'Date To', 'required|trim|sql_date');
		$this->form_validation->set_rules('pos', 'Position', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE){ 
			$table .= $this->load->view('admin/navigation/row_0', array('message'=>'Error Validation'), TRUE);
		} else {
			$limit = 50;
			$pos = $this->input->post('pos');
			$date1 = $this->input->post('i-date-1');
			$date2 = $this->input->post('i-date-2');

			$items = $this->item_model->read($date1, $date2, NULL, $pos, $limit);
			if(count($items) > 0){
				$i = ($pos+1);
				foreach($items as $item){
					$item['datetime'] = date('d/m/Y H:i:s', strtotime($item['time']));
					$item['i'] = $i;

					$table .= $this->load->view('admin/navigation/row', $item, TRUE);

					$i++;
				}
				
				/* load more button */
				$table .= $this->load->view('admin/navigation/more', array('pos' => ($pos + $limit)), TRUE);
			} else {
				$log_msg = 'No Data';
				$table .= $this->load->view('admin/navigation/row_0', array('message'=>$log_msg), TRUE);
			}
		}

		send_json_action(TRUE, $table, '');

	}

	function get_top_data(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', '<tr><td colspan="3">Authorization Failed, You will redirect to login page.</td></tr>');

		$table = '';

		$this->form_validation->set_rules('i-date-1', 'Date From', 'required|trim|sql_date');
		$this->form_validation->set_rules('i-date-2', 'Date To', 'required|trim|sql_date');
		$this->form_validation->set_rules('pos', 'Position', 'required|trim|integer');
		if ($this->form_validation->run() == FALSE){ 
			$table .= $this->load->view('admin/navigation/row_0', array('message'=>'Error Validation'), TRUE);
		} else {
			$limit = 50;
			$pos = $this->input->post('pos');
			$date1 = $this->input->post('i-date-1');
			$date2 = $this->input->post('i-date-2');

			$items = $this->top_model->read($date1, $date2, $pos, $limit);
			if(count($items) > 0){
				$i = ($pos+1);
				foreach($items as $item){
					$item['datetime'] = date('d/m/Y H:i:s', strtotime($item['time']));
					$item['i'] = $i;

					$table .= $this->load->view('admin/navigation/row', $item, TRUE);

					$i++;
				}
				
				/* load more button */
				$table .= $this->load->view('admin/navigation/more', array('pos' => ($pos + $limit)), TRUE);
			} else {
				$log_msg = 'No Data';
				$table .= $this->load->view('admin/navigation/row_0', array('message'=>$log_msg), TRUE);
			}
		}

		send_json_action(TRUE, $table, '');
	}

	function synch(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', '<tr><td colspan="5">Authorization Failed, You will redirect to login page.</td></tr>');

		if($this->item_model->check_synch() > 0)
			send_json_error('Other Sychronization in process.');

		try {
			/* lock synch process */
			$pid = $this->item_model->set_synch();

			/* news and newest data */
			$this->ycombinator_api->newest();
			$newest = $this->ycombinator_api->output;

			/* throw error message */
			if($newest['error']) throw new Exception($newest['error_msg']);
			
			$newest_data = json_decode($newest['response'], TRUE);
			$db_data = $this->item_model->read_all_id();

			/* diff data */
			$diff_data = array_diff($newest_data, $db_data);
			
			if(count($diff_data) == 0) throw new Exception('Data is Synchronized.');

			krsort($diff_data, SORT_NUMERIC);
			foreach($diff_data as $id){
				/* crawl */
				$this->ycombinator_api->reset();
				$this->ycombinator_api->item($id);
				$resp = $this->ycombinator_api->output;

				/* throw error message */
				if($resp['error']) throw new Exception('Error item');
				
				$item = json_decode($resp['response'], TRUE);
				
				/* make sure */	
				if(isset($item['kids'])) $item['kids'] = implode(',', $item['kids']);
				if(isset($item['parent'])) $item['parent'] = implode(',', $item['parent']);
				$item['time'] = date('Y-m-d H:i:s', $item['time']);
				$item['yci_flag'] = 'newest';

				$return_val = $this->item_model->create($item);
			}
		}
		catch (Exception $e) {
			if($pid !== FALSE) $this->item_model->unset_synch($pid);
			send_json_error($e->getMessage());
		}
		
		if($pid !== FALSE) $this->item_model->unset_synch($pid);

		send_json_action(TRUE, 'Data Synchronized.', 'Data not Synchronized.');
	}

	function top_synch(){
		if(check_user_session() == FALSE)
			send_json_redirect('auth/logout', '<tr><td colspan="5">Authorization Failed, You will redirect to login page.</td></tr>');

		if($this->item_model->check_synch() > 0)
			send_json_error('Other Sychronization in process.');

		try {
			/* lock synch process */
			$pid = $this->item_model->set_synch();

			/* news and newest data */
			$this->ycombinator_api->news();
			$news = $this->ycombinator_api->output;

			/* throw error message */
			if($news['error']) throw new Exception($news['error_msg']);
			
			$news_data = json_decode($news['response'], TRUE);
			$slice_data = array_slice($news_data, 0, 100);
			
			if(count($slice_data) != 100) throw new Exception('Data not valid');

			/* truncate top table */
			$this->top_model->truncate();

			/* start */
			foreach($slice_data as $id){
				/* crawl */
				$this->ycombinator_api->reset();
				$this->ycombinator_api->item($id);
				$resp = $this->ycombinator_api->output;

				/* throw error message */
				if($resp['error']) throw new Exception('Error item');
				
				$item = json_decode($resp['response'], TRUE);
				/* make sure */	
				if(isset($item['kids'])) $item['kids'] = implode(',', $item['kids']);
				if(isset($item['parent'])) $item['parent'] = implode(',', $item['parent']);
				$item['time'] = date('Y-m-d H:i:s', $item['time']);

				$return_val = $this->top_model->create($item);
				if($return_val) $this->item_model->update($item, $item['id']);
			}
		}
		catch (Exception $e) {
			if($pid !== FALSE) $this->item_model->unset_synch($pid);
			send_json_error($e->getMessage());
		}

		/*
		$this->ycombinator_api->reset();
		$news = $this->ycombinator_api->news();
		*/
		
		if($pid !== FALSE) $this->item_model->unset_synch($pid);

		send_json_action(TRUE, 'Data Synchronized.', 'Data not Synchronized.');
	}
}