<?php
class MY_Form_validation extends CI_Form_validation 
{
	function min_value($input, $param = 0)
	{
		$value = intval($input);
		$limit = intval($param);
		
		if ($value >= $limit) return true;
		return false;
	}
	
	function max_value($input, $param = 0)
	{
		$value = intval($input);
		$limit = intval($param);
		
		if ($value <= $limit) return true;
		return false;
	}
	
	function valid_date($str)
	{
		if(!trim($str))return true;
		$segments = explode("/", $str);
		$segments = array_reverse($segments);
		if (count($segments) < 2) return false;
		if (!checkdate($segments[1], $segments[2], $segments[0])) return false; 
		
		return true;
	}
	
	function sql_date($str)
	{
		if(!$str)return NULL;
		$segments = explode("/", $str);
		$segments = array_reverse($segments);
		if (count($segments) < 2) return '';
		if (!checkdate($segments[1], $segments[2], $segments[0])) return '';
		
		return implode("/", $segments);
	}
	
	function zero_check($str)
	{
		if($str <= 0)
		{
			return false;
		}
		return true;
	}
	
	function numeric_check($str)
	{
		return (bool)preg_match('/^[0-9\.]+$/', $str);
	}
	
	function doubles($str)
	{
		return (bool)preg_match('/^[0-9]+(?:\.[0-9]+)?$/im', $str);
		//return (bool)preg_match('/^[0-9]+([.][0-9])?$/', $str);
	}

	function payment($str)
	{
		return (bool)preg_match('/^([a-zA-Z]{3}+([.]))+[0-9]{7}$/', $str);
	}
	function money($str)
	{
		//debug("before $str");
		$segments = str_replace(',', '', $str);
		//debug("after $segments");
		return $segments;
	}
	function get_errors()
	{
		return $this->_error_array;
	}

	function valid_url_format($str){
        $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
        if (!preg_match($pattern, $str)){
            $this->set_message('valid_url_format', 'The URL you entered is not correctly formatted.');
            return FALSE;
        }
 
        return TRUE;
    }

    function url_exists($url){                                   
        $url_data = parse_url($url); // scheme, host, port, path, query
        $port = isset($url_data['port']) ? $url_data['port'] : 80;
        
        $fso = @fsockopen($url_data['host'], $port, $error_t, $error_c);
		if (!is_resource($fso)){
            $this->set_message('url_exists', 'The URL you entered is not accessible.');
            return FALSE;
        }               
         
        return TRUE;
    }

	// --------------------------------------------------------------------
	
	/**
	 * Valid Email
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function valid_email($str)
	{
		return ( ! preg_match("/^[a-zA-Z]+[:\/\/]+[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&=\?\-_]+$/i", $str)) ? FALSE : TRUE;
		//return ( ! preg_match("#^http://www\.[a-z0-9-_.]+\.[a-z]{2,4}$#i", $str)) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Valid Emails
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */	
	function valid_emails($str)
	{
		if (strpos($str, ',') === FALSE)
		{
			return $this->valid_email(trim($str));
		}
		
		foreach(explode(',', $str) as $email)
		{
			if (trim($email) != '' && $this->valid_email(trim($email)) === FALSE)
			{
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
}
