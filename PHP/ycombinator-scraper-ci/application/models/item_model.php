<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class item_model extends CI_Model {
	function read($date1, $date2, $flag, $pos, $limit){
		$this->db->select('*', TRUE);
		if(empty($flag) == FALSE)
			$this->db->where('yci_flag', $flag);
		$this->db->where("to_days(time) BETWEEN to_days('$date1') AND to_days('$date2')");
		$this->db->order_by('time', 'desc');
		if(is_null($pos)==FALSE) 
			$this->db->limit($limit, $pos);
		$query = $this->db->get('yc_item');

		$data = array();
		foreach($query->result_array() as $row){
			$data[] = $row;
		}

		return $data;
	}

	function read_by_id($id) {
		$this->db->select('*', TRUE);
		$this->db->where('yci_id', $id);
		$query = $this->db->get('yc_item');

		$data = array();
		if($query->num_rows() > 0){
			$data = $query->row_array();
		}
		
		return $data;
	}

	function read_all_id(){
		$this->db->select('id', TRUE);
		$this->db->order_by('yci_id','desc');
		$this->db->limit(500);
		$query = $this->db->get('yc_item');

		$data = array();
		foreach($query->result_array() as $row){
			$data[] = $row['id'];
		}

		return $data;
	}

	function create($data) {
		$this->db->trans_start();

		$this->db->insert('yc_item', $data);
		
		/*$id = $this->db->insert_id();*/
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function update($data, $id){
		$this->db->trans_start();		

		$this->db->where('id', $id);
		$this->db->update('yc_item', $data);
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function set_synch(){
		$this->db->trans_start();

		$data = array('user_id' => $this->session->userdata('user_id'));
		$this->db->insert('yc_item_synch', $data);
		$id = $this->db->insert_id();

		$this->db->trans_complete();
		$status = $this->db->trans_status();

		if($status) return $id;
		else return $status;
	}

	function unset_synch($id){
		$this->db->trans_start();

		$this->db->where('ycis_id', $id);
		$this->db->update('yc_item_synch', array('ycis_active' => 0));

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function check_synch(){
		$this->db->select('count(*) as total', TRUE);		
		$this->db->where('ycis_active', 1);
		
		$query = $this->db->get('yc_item_synch');

		$row = $query->row_array();

		return $row['total'];
	}
}