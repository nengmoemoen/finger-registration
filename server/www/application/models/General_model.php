<?php


class General_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Insert Or Update Member data
	 *
	 * @param array $data
	 * @return void
	 */
	public function upsert_member(array $data): bool {
		if($this->db->get_where('members', ['user_id' => $data['user_id']])->num_rows() > 0)
			return $this->db->update('members', $data, ['user_id' => $data['user_id']]);
		else
			return $this->db->insert('members', $data);
	}
	
	/**
	 * Insert or update fingerprint table
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function upsert_finger(array $data): bool {
		if($this->db->get_where('fingerprint', ['member' => $data['member']])->num_rows() > 0)
			return $this->db->update('fingerprint', $data, ['member' => $data['member'], 'fp_number' => $data['fo_number']]);
		else
			return $this->db->insert('fingerprint', $data);
	}

	/**
	 * Insert or update device
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function upsert_device(array $data): bool {
		if($this->db->get_where('devices', ['sn' => $data['sn']])->num_rows() > 0)
			return $this->db->update('devices', $data, ['sn' => $data['sn']]);
		else
			return $this->db->insert('devices', $data);
	}

	/**
	 * Insert r update transactions
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function upsert_trans(array $data): bool {
		if($this->db->get_where('transactions', ['sn' => $data['sn'], 'user_id' => $data['user_id'], 'checktime' => $data['checktime']])->num_rows() > 0)
			return $this->db->update('transactions', $data, ['sn' => $data['sn'], 'user_id' => $data['user_id'], 'checktime' => $data['checktime']]);
		else
			return $this->db->insert('transactions', $data);
	}

	/**
	 * Get All Members
	 *
	 * @return array
	 */
	public function get_members(): array {
		return $this->db->get('members')->result_array();
	}

	/**
	 * Get all members by SN or a person if params userid is not NULL
	 *
	 * @param string $userid
	 * @return array
	 */
	public function get_members_sn(string $userid=NULL): array {
		$this->db->select('a.id as member_id, a.*')
				 ->from('members a');
		if(!empty($userid))
			$this->db->where('a.user_id', $userid);
		$res = $this->db->get();

		return $res->result_array();
	}

	/**
	 * Get a Member fingerprint
	 *
	 * @param string $id
	 * @return array
	 */
	public function get_fp_member(string $id): array {
		return $this->db->get_where('fingerprint', ['member' => $id])->result_array();
	}

	/**
	 * get commands 
	 *
	 * @param string $sn
	 * @param  int $limit
	 * @return array
	 */
	public function get_commands(string $sn=NULL, int $limit=NULL): array {
		if(!empty($limit))
			$this->db->limit($limit);
		$this->db->where('sn', $sn);
		$res = $this->db->get('command');
		return $res->result_array();
	}

	/**
	 * Get All Devices
	 *
	 * @return array
	 */
	public function get_all_devices() {
		return $this->db->get('devices')->result_array();
	}

	/**
	 * Get a device by serial number
	 *
	 * @param string $sn
	 * @return array
	 */
	public function get_device_by_sn($sn) {
		return $this->db->get_where('devices', ['sn' => $sn])->row_array();
	}
}
