<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class alog_model extends CI_Model {

	function read($id=null) {
		$this->db->select('*', TRUE);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		if(is_null($id)==FALSE)
			$this->db->where('alog_id', $id);
		$query = $this->db->get('activity_log');

		$data = array();
		if($query->num_rows() > 0){
			$data = $query->row_array();
		}
		
		return $data;
	}

	function read_code(){
		$this->db->select('DISTINCT(alog_code) as code', TRUE);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$query = $this->db->get('activity_log');

		$data = array();
		if($query->num_rows() > 0){
			$data = $query->result_array();
		}
		
		return $data;
	}

	function read_where($date1, $date2, $tags){
		$data = array();

		$this->db->select('*', TRUE);
		$this->db->from('activity_log');
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where("to_days(alog_date) BETWEEN to_days('$date1') AND to_days('$date2')");
		$this->db->where_in('alog_code', $tags);

		$query = $this->db->get();
		if($query->num_rows() > 0){
			$data = $query->result_array();
		} else {
			$data = FALSE;
		}
		
		return $data;
	}

	function create($data) {
		$this->db->trans_start();

		$this->db->insert('activity_log', $data);
		
		/*$id = $this->db->insert_id();*/
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete($id) {
		$this->db->trans_start();

		$this->db->where('ycu_id', $id);
		$this->db->delete('activity_log');
		
		$this->db->trans_complete();
		return $this->db->trans_status();	
	}
}