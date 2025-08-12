<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class article_model extends CI_Model {

	function read($id) {
		$this->db->select('*', TRUE);
		$this->db->where('yca_id', $id);
		$query = $this->db->get('yc_article');

		$data = array();
		if($query->num_rows() > 0){
			$data = $query->row_array();
		}
		
		return $data;
	}

	function create($data) {
		$this->db->trans_start();

		$this->db->insert('yc_article', $data);
		
		/*$id = $this->db->insert_id();*/
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function set_success($id) {
		$this->db->trans_start();

		$this->db->where('yca_id', $id);
		$this->db->update('yc_article', array('yca_success' => '1'));

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function set_fail($id) {
		$this->db->trans_start();

		$this->db->where('yca_id', $id);
		$this->db->update('yc_article', array('yca_success' => '0'));

		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}