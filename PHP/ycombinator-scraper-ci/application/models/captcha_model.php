<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class captcha_model extends CI_Model {

	public function read($data = null) {
		$expiration = time() - 800;
		$this->db->query("DELETE FROM captcha WHERE captcha_time < " . $expiration);

		$this->db->select('captcha_id, captcha_time, ip_address, word');
		$this->db->from('captcha');
		if (!is_null($data)) {
			$this->db->where($data);
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$result = $query->row_array();
			return $result;
		} else {
			return FALSE;
		}
	}

	public function create($data) {
		$this->db->insert('captcha', $data);
	}

	public function delete($data) {
		$this->db->delete('captcha', $data);
	}
}