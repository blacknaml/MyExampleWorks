<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class general_model extends CI_Model {
	function cbo_scraper_category(){
		$data = array();

		$this->db->select('sc_id, sc_name, sc_class', TRUE);
		$this->db->from('scraper_category');
		$this->db->where('active', 1);
		$query = $this->db->get();
		
		$data[0] = '---';
		foreach ($query->result_array() as $row) {
			$data[$row['sc_id']] = $row['sc_name'];
		}

		return $data;
	}

	function get_scraper_class($sc_id){
		$data = array();

		$this->db->select('sc_class, sc_params');
		$this->db->from('scraper_category');
		$this->db->where('active', 1);
		$this->db->where('sc_id', $sc_id);

		$query = $this->db->get();
		foreach($query->result_array() as $row){
			$data = array(
				'class' => $row['sc_class'],
				'params' => $row['sc_params']
			);
		}

		return $data;
	}
}