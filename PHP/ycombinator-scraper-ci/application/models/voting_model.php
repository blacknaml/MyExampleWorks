<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class voting_model extends CI_Model {

	function read($id) {
		$this->db->select('*', TRUE);
		$this->db->where('ycv_id', $id);
		$query = $this->db->get('yc_voting');

		$data = array();
		if($query->num_rows() > 0){
			$data = $query->row_array();
		}
		
		return $data;
	}

	function create($data) {
		$this->db->trans_start();

		$this->db->insert('yc_voting', $data);
		
		/*$id = $this->db->insert_id();*/
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}