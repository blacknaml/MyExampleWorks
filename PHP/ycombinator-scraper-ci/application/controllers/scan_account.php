<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of scan_account
 * for running this script: /usr/bin/php5 path_folder_of_ci_web/index.php scan_account/run
 * @author Debi Praharadika
 */
class scan_account extends CI_Controller {
	protected $data;

	function __construct() {
		parent::__construct();

		$this->data = array(	
			'content_header' => 'Schedule Scan Active Account',
			'small_content_header' => 'Tools',
			'content' => '');

		$this->load->model('account_model');
		$this->load->model('general_model');
	}

	public function index() {
		if ($this->input->is_cli_request()) {
			echo 'Run on CLI mode ... ';
		} else {
			if(check_user_session() == FALSE) redirect('auth/logout');

			$this->data['content'] = $this->load->view('admin/scan_account/block', NULL, TRUE);
			$this->load->view('admin/home', $this->data);
		}
	}

	public function run(){
		if ($this->input->is_cli_request()) {
			echo PHP_EOL;
			echo '['.date('d/m/Y H:i:s')."] Run Scan Account on CLI mode ... " . PHP_EOL;
			echo "=======================================" . PHP_EOL;

			$sc_id = $this->config->item('app_nyc_scid');
			$data = $this->account_model->read_all_by_scid($sc_id, true);
			if(count($data) > 0){
				/* get scraper information */
				$scraper_data = $this->general_model->get_scraper_class($sc_id);
				$scraper_class = $scraper_data['class'].'_api';

				/* load lib class */
				$this->load->library('scraper/'.$scraper_class);

				/* check login */
				$expired_account = array();
				$live_account = array();
				foreach($data as $val){
					$response = array();

					/* run crawler */
					$this->{$scraper_class}->user($val['ycu_username']);
					$response = $this->{$scraper_class}->output;
					$this->{$scraper_class}->reset();

					/* data row */
					if($response['error'] == FALSE){
						$live_account[] = array('ycu_id' => $val['ycu_id'], 'ycu_expired' => 0);
					} else {
						$expired_account[] = array('ycu_id' => $val['ycu_id'], 'ycu_expired' => 1);
					}
				}

				if(count($expired_account) > 0)
					$this->account_model->set_expired($expired_account);

				if(count($live_account) > 0)
					$this->account_model->set_live($live_account);

				echo '['.date('d/m/Y H:i:s')."] expired account: " . count($expired_account) . ', live account: ' . count($live_account) . PHP_EOL;
				echo "=======================================" . PHP_EOL;
			}

		} else {
			if(check_user_session() == FALSE) redirect('auth/logout');

			$this->data['content'] = $this->load->view('admin/scan_account/block', NULL, TRUE);
			$this->load->view('admin/home', $this->data);
		}
	}
}