<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of schedule_comment_bg
 * for running this script: /usr/bin/php5 path_folder_of_ci_web/index.php schedule_comment_bg/run
 * @author Debi Praharadika
 */
class schedule_comment_bg extends CI_Controller {
	protected $data;

	function __construct() {
		parent::__construct();

		$this->data = array(	
			'content_header' => 'Schedule Comment to Article [Background Script]',
			'small_content_header' => 'Comment',
			'content' => '');

		$this->load->model('comment_model');
	}

	public function index() {
		if ($this->input->is_cli_request()) {
			echo 'Run on CLI mode ... ';
		} else {
			if(check_user_session() == FALSE) redirect('auth/logout');

			$this->data['content'] = $this->load->view('admin/schedule_comment/block', NULL, TRUE);
			$this->load->view('admin/home', $this->data);
		}
	}

	public function run($class){
		if ($this->input->is_cli_request()) {
			echo PHP_EOL;
			echo '['.date('d/m/Y H:i:s')."] Run $class on CLI mode ... " . PHP_EOL;
			echo "=======================================" . PHP_EOL;

			/* load lib class */
			$this->load->library('scraper/'.$class);

			/*$proxy_list = get_proxy_list();
			$proxy = $proxy_list[array_rand($proxy_list, 1)];*/
			$proxy = $this->config->item('proxy_url').':'.$this->config->item('proxy_port');

			/* read comment */
			$comments = $this->comment_model->read_bg();
			foreach($comments as $comment){
				$log_msg = '[background script] '.$comment['ycu_username'].',';

				/* run crawler */
				$this->{$class}->input = array(
					'username' => $comment['ycu_username'],
					'password' => $comment['ycu_password'],
					'article_url' => $comment['ycc_url'],
					'comment' => $comment['ycc_comment']);
				$this->{$class}->set_proxy($proxy);
				$this->{$class}->set_cookie_file($this->config->item('cookies_path').'ycombinator_bg.txt');
				$this->{$class}->send('comment');
				$response = $this->{$class}->output;
				$this->{$class}->reset();

				/* response handler */
				if($response['error']){
					/* flag 2 for error */
					$log_msg .= $response['error_msg'];
					
					$data = array('ycc_sent' => '2', 'ycc_message' => $log_msg);
					$this->comment_model->update($data, $comment['ycc_id']);
				} else {
					if($response['comment_id'] !== FALSE){
						/* update child url */
						$comment_url = 'https://news.ycombinator.com/'.$response['comment_id'];
						$this->comment_model->update_child(array('ycc_url' => $comment_url), $comment['ycc_id']);
					}
					/* Update db */
					$return_val = $this->comment_model->update(array('ycc_sent' => '1'), $comment['ycc_id']);
					
					/* log message s */
					$log_msg .= ($return_val) ? ' Comment successfully submitted and saved.' : ' Comment successfully submitted but failed to saved.';
					$log_msg .= $response['error_msg'];
				}

				/* write log */
				$this->logger->write($this->config->item('log_sche_comments'), $log_msg, $comment['user_id']);

				echo '['.date('d/m/Y H:i:s')."] $log_msg" . PHP_EOL;
				echo "=======================================" . PHP_EOL;
			}

		} else {
			if(check_user_session() == FALSE) redirect('auth/logout');

			$this->data['content'] = $this->load->view('admin/schedule_comment/block', NULL, TRUE);
			$this->load->view('admin/home', $this->data);
		}
	}
}