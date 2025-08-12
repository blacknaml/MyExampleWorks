<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class comment_model extends CI_Model {

	function read($id) {
		$this->db->select('*', TRUE);
		$this->db->where('ycc_id', $id);
		$query = $this->db->get('yc_comment');

		$data = array();
		if($query->num_rows() > 0){
			$data = $query->row_array();
		}
		
		return $data;
	}

	function read_bg(){
		$this->db->select('c.*', TRUE);
		$this->db->select('user_id, ycu_username, ycu_password', TRUE);
		$this->db->from('yc_comment as c');
		$this->db->join('yc_user as u', 'u.ycu_id = c.ycu_id');
		$this->db->where('ycc_sent', '0');
		$this->db->where('ycc_bg', '1');
		$this->db->where('ycc_schedule <= now()');
		$this->db->order_by('ycc_id', 'asc');
		
		$query = $this->db->get();

		$data = array();
		foreach($query->result_array() as $row){
			$data[] = $row;
		}

		return $data;
	}

	function read_bg_all($date1, $date2){
		$data = array();

		$this->db->select('c.*', TRUE);
		$this->db->select('ycu_username', TRUE);
		$this->db->from('yc_comment as c');
		$this->db->join('yc_user as u', 'u.ycu_id = c.ycu_id');
		$this->db->where('ycc_bg', '1');
		$this->db->where('u.user_id', $this->session->userdata('user_id'));
		$this->db->where("to_days(ycc_schedule) BETWEEN to_days('$date1') AND to_days('$date2')");
		$this->db->order_by('ycc_id', 'asc');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			$data = $query->result_array();
		} else {
			$data = FALSE;
		}
		
		return $data;
	}

	function create($data, $return_id=FALSE) {
		$this->db->trans_start();

		$this->db->insert('yc_comment', $data);
		$id = $this->db->insert_id();
		
		$this->db->trans_complete();
		if($this->db->trans_status() && $return_id) return $id;
		else return $this->db->trans_status();
	}

	function update($data, $id){
		$this->db->trans_start();

		$this->db->where('ycc_id', $id);
		$this->db->update('yc_comment', $data);
		
		$this->db->trans_complete();
		return $this->db->trans_status();	
	}

	function update_child($data, $pid){
		$this->db->trans_start();

		$this->db->where('ycc_pid', $pid);
		$this->db->update('yc_comment', $data);
		
		$this->db->trans_complete();
		return $this->db->trans_status();	
	}
}