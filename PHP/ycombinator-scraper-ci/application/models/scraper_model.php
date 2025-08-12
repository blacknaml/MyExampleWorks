<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class scraper_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}

	function create($data){
		$this->db->trans_start();

		$this->db->insert('scraper_category', $data);
		$id = $this->db->insert_id();

		$this->db->trans_complete();
		if($this->db->trans_status() == FALSE)
			return FALSE;
		else
			return $id;
	}
	
	function read(){
		$data = array();

		$this->db->select('*', TRUE);
		$this->db->from('scraper_category');

		$query = $this->db->get();
		foreach($query->result_array() as $row){
			$data[] = $row;
		}

		return $data;
	}
}