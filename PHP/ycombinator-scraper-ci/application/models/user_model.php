<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_model extends CI_Model {
	function __construct(){
		parent::__construct();
	}

	function create($data){
		$this->db->trans_start();

		$this->db->insert('user', $data);
		$id = $this->db->insert_id();

		$this->db->trans_complete();
		if($this->db->trans_status() == FALSE)
			return FALSE;
		else
			return $id;
	}
	function read_where($where){
		$data = array();

		$this->db->select('*', TRUE);
		$this->db->from('user');
		$this->db->where($where);

		$query = $this->db->get();
		if($query->num_rows() > 0){
			$data = $query->row_array();
			return $data;
		} else {
			return FALSE;
		}
	}
	function read_username($username){
		$this->db->select('*', TRUE);
		$this->db->from('user');
		$this->db->where(array('user_name' => $username));

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			return $result;
		} else {
			return FALSE;
		}
	}
	function read(){
		$data = array();

		$this->db->select('*', TRUE);
		$this->db->from('user');

		$query = $this->db->get();
		foreach($query->result_array() as $row){
			$data[] = $row;
		}

		return $data;
	}
	function update($data, $id){
		$this->db->trans_start();

		$this->db->where('user_id', $id);
		$this->db->update('user', $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}
	function delete($id){
		$this->db->trans_start();

		$this->db->where('user_id', $id);
		$this->db->delete('user');

		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}