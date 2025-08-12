<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class account_model extends CI_Model {

	function read($id=null) {
		$this->db->select('*', TRUE);
		if(is_null($id) == FALSE) $this->db->where('ycu_id', $id);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		$this->db->where('ycu_active', '1');
		$query = $this->db->get('yc_user');

		$data = array();
		if($query->num_rows() > 0){
			$data = $query->row_array();
		}
		
		return $data;
	}

	function read_by_scid($sc_id, $user_id, $active=true, $position=null, $item_per_page=null){
		$this->db->select('*', TRUE);
		$this->db->where('user_id', $user_id);
		$this->db->where('sc_id', $sc_id);
		if($active){
			$this->db->where('ycu_active', '1');
			$this->db->where('ycu_expired', '0');
		}
		$this->db->order_by('ycu_username');
		if(is_null($position)==FALSE) $this->db->limit($item_per_page, $position);

		$query = $this->db->get('yc_user');

		$data = array();
		foreach($query->result_array() as $row){
			$data[] = $row;
		}

		return $data;
	}

	function read_all_by_scid($sc_id, $active=true){
		$this->db->select('*', TRUE);		
		$this->db->where('sc_id', $sc_id);
		if($active)	$this->db->where('ycu_active', '1');

		$this->db->order_by('ycu_username');
		$query = $this->db->get('yc_user');

		$data = array();
		foreach($query->result_array() as $row){
			$data[] = $row;
		}

		return $data;
	}

	function create($data) {
		$this->db->trans_start();

		$this->db->insert('yc_user', $data);
		
		/*$id = $this->db->insert_id();*/
		
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function lock($id){
		$this->db->trans_start();

		$this->db->where('ycu_id', $id);
		$this->db->update('yc_user', array('ycu_active' => '0'));

		$this->db->trans_complete();
		return $this->db->trans_status();	
	}

	function unlock($id){
		$this->db->trans_start();

		$this->db->where('ycu_id', $id);
		$this->db->update('yc_user', array('ycu_active' => '1'));

		$this->db->trans_complete();
		return $this->db->trans_status();	
	}

	function delete($id) {
		$this->db->trans_start();

		$this->db->where('ycu_id', $id);
		$this->db->delete('yc_user');
		
		$this->db->trans_complete();
		return $this->db->trans_status();	
	}

	function set_expired($array_of_id){
		$this->db->trans_start();

		$this->db->update_batch('yc_user', $array_of_id, 'ycu_id'); 

		$this->db->trans_complete();
		return $this->db->trans_status();	
	}

	function set_live($array_of_id){
		$this->db->trans_start();

		$this->db->update_batch('yc_user', $array_of_id, 'ycu_id'); 

		$this->db->trans_complete();
		return $this->db->trans_status();	
	}
}