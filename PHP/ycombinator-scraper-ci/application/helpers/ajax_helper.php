<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');	
if ( ! function_exists('is_ajax'))
{
	function is_ajax()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
	} 
}
if ( ! function_exists('send_datatables_list'))
{
	function send_datatables_list($data = array()) 
	{
		$result['aaData'] = $data;
		return $result;
	}		
}
if ( ! function_exists('format_zero_padding'))
{
	function format_zero_padding($int, $width) 
	{
		return sprintf("%0" . $width . "d", $int);
	}		
}
if ( ! function_exists('send_json'))
{
	function send_json($str='')
	{
		header("Content-type: application/json");
		echo json_encode($str);
		exit;
	}
}
if ( ! function_exists('send_json_action'))
{
	function send_json_action($is_ok, $success='Data Tersimpan', $failed='Data gagal Tersimpan', $param = '')
	{
		$ci = & get_instance();
		$action_mode = $ci->input->post('action_mode');
		$json_str = (!$is_ok) ? json_encode(array('type'=>'failed','content'=>$failed,'mode' => $action_mode, 'param' => $param)) : json_encode(array('type'=>'success','content'=>$success,'mode' => $action_mode, 'param' => $param));
		header("Content-length: ". strlen($json_str));
		echo $json_str;
		exit;
	}
}
if ( ! function_exists('send_json_error'))
{
	function send_json_error($str='')
	{
		echo json_encode(array('type' => 'error', 'content' => $str));
		exit;
	}
}
if ( ! function_exists('send_json_denied'))
{
	function send_json_denied($str=NULL)
	{
		if(!$str) $str="Access Denied.";
		echo json_encode(array('type' => 'error', 'content' => $str));
		exit;
	}
}
if ( ! function_exists('send_json_redirect'))
{
	function send_json_redirect($url, $str)
	{
		if(!$str) $str="Access Denied.";
		echo json_encode(array('type' => 'redirect', 'content' => $str, 'url' => base_url().$url));
		exit;
	}
}
if ( ! function_exists('send_json_dt_denied'))
{
	function send_json_dt_denied()
	{	
		$params = get_datatables_control();
		$data = make_datatables_control($params, array(), 0);
		send_json($data);
	}
}
if ( ! function_exists('send_json_validate'))
{
	function send_json_validate()
	{
		$errors = validation_errors('<li>', '</li>');
		$ci = & get_instance();
		$details = $ci->form_validation->get_errors();
		$response = array();
		foreach ($details as $key => $val)
		{
			if ($val != '')
			{
				$response[]	= array('field' => $key, 'msg' => $val);
			}
		}
		if (!empty($errors)) echo json_encode(array('type' => 'failed', 'content' => '<ul>'.$errors.'</ul>', 'details' => $response));
		exit;
	}
}
?>