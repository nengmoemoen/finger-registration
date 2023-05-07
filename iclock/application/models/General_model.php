<?php


class General_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function upsert_member($data) {
		if($this->db->get_where('members', ['user_id' => $data['user_id']])->num_rows() > 0)
			return $this->db->update('members', $data, ['user_id' => $data['user_id']]);
		else
			return $this->db->insert('members', $data);
	}	
}
