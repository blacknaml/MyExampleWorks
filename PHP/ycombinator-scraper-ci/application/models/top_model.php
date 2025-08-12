<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class top_model extends CI_Model {
	function read($date1, $date2, $pos, $limit){
		$this->db->select('*', TRUE);
		$this->db->where("to_days(ycn_date) BETWEEN to_days('$date1') AND to_days('$date2')");
		$this->db->order_by('ycn_id', 'asc');
		if(is_null($pos)==FALSE) 
			$this->db->limit($limit, $pos);
		$query = $this->db->get('yc_news');

		$data = array();
		foreach($query->result_array() as $row){
			$data[] = $row;
		}

		return $data;
	}

	function read_by_id($id) {
		$this->db->select('*', TRUE);
		$this->db->where('ycn_id', $id);
		$query = $this->db->get('yc_news');

		$data = array();
		if($query->num_rows() > 0){
			$data = $query->row_array();
		}
		
		return $data;
	}

	function read_all_id(){
		$this->db->select('id', TRUE);
		$this->db->order_by('ycn_id','desc');
		$this->db->limit(500);
		$query = $this->db->get('yc_news');

		$data = array();
		foreach($query->result_array() as $row){
			$data[] = $row['id'];
		}

		return $data;
	}

	function create($data) {
		$this->db->trans_start();

		$this->db->insert('yc_news', $data);
		
		/*$id = $this->db->insert_id();*/
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function truncate(){
		$this->db->trans_start();

		$this->db->truncate('yc_news'); 

		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}