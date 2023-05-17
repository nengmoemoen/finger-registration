<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('general_model');
	}

	public function index()
	{
		$data['members'] = $this->general_model->get_members();
		$this->load->view('welcome', $data);
	}

	/**
	 * Delete personnel from device only
	 *
	 * @return void
	 */
	public function erase_dev(): void {
		$userid = $this->input->get('userid');
		$devices = $this->general_model->get_all_devices();
		$fingerprints = $this->general_model->get_fp_member($userid)[0];

		$this->db->trans_start();
		foreach($devices as $device) {

			foreach($fingerprints as $fp)
			{
				$cmd = [
					'sn' 		=> $device['sn'],
					'status'	=> 1,
					'cmd'		=> trim("DATA DELETE FINGERTMP PIN={$userid}\tFID=".intval($fp['fp_number'])),
				];

				$this->db->insert('command', $cmd);
			}
		}
		$this->db->trans_complete();

		if($this->db->trans_status() == FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', ['message' => 'Data gagal di hapus pada alat']);
			redirect(base_url());
			return;
		}
		
		$this->db->trans_commit();
		$this->session->set_flashdata('success', ['message' => 'Data berhasil di hapus pada alat']);
		redirect(base_url());
	}

	/**
	 * Add personnel to device only
	 *
	 * @return void
	 */
	public function add_dev(): void {
		$userid = $this->input->get('userid');
		$devices = $this->general_model->get_all_devices();
		$person = $this->general_model->get_members_sn($userid)[0];
		$fingerprints = $this->general_model->get_fp_member($userid);

		$this->db->trans_start();

		foreach($devices as $device)
		{
			// $cmd = [
			// 	'sn' 		=> $device['sn'],
			// 	'status'	=> 1,
			// 	'cmd'		=> trim("DATA USER PIN={$userid}\tName=".$person['nickname']."\tPasswd=\tCard=\tGrp=0\tTZ=".($person['timezone'] ?? 7)."\tPri=".($person['privilege'] ?? 0)),
			// ];
			// $this->db->insert('command', $cmd);
	
			foreach($fingerprints as $fp)
			{
				$cmd = [
					'sn' 	 => $device['sn'],
					'status' => 1, 
					'cmd'	 => trim("DATA UPDATE FINGERTMP PIN=".$person['user_id']."\tFID=".$fp['fp_number']."\tSize=".strlen($fp['template'])."\tValid=1\tTMP=".$fp['template'])
				];
				$this->db->insert('command', $cmd);
			}
		}

		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', ['message' => 'Data gagal di tambah pada alat']);
			redirect(base_url());
			return;
		}

		$this->db->trans_commit();
		$this->session->set_flashdata('success', ['message' => 'Data berhasil di tambah pada alat']);
		redirect(base_url());
	}
	
	/** 
	*
	* Get All Person Data in device
	*
	* @return void
	*/
	public function get_all() {
		$devices = $this->general_model->get_all_devices();
		
		foreach($devices as $device)
		{
			$data = [
				'sn'	=> $device['sn'],
				'cmd'	=> "DATA QUERY USERINFO"
			];
			
			$this->db->insert('command', $data);
		}
		
		$this->session->set_flashdata('success', ['message' => 'Data berhasil di tambah pada alat']);
		redirect(base_url());
	}
}
