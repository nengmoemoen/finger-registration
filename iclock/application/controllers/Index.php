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
		$sn = $this->input->get('sn');

		$person = $this->general_model->get_members_sn('userid')[0];

		$cmd = [
			'sn' 		=> $sn,
			'status'	=> 1,
			'cmd'		=> trim("DATA DEL_USER PIN={$userid}"),
		];

		if(!$this->db->insert('command', $cmd))
		{
			$this->session->set_flashdata('error', ['message' => 'Data gagal di hapus pada alat']);
			redirect(base_url());
			return;
		}

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
		$sn = $this->input->get('sn');

		$person = $this->general_model->get_members_sn($userid)[0];

		$this->db->trans_start();
		$cmd = [
			'sn' 		=> $sn,
			'status'	=> 1,
			'cmd'		=> trim("DATA USER PIN={$userid}\tName=".$person['nickname']."\tPasswd=\tCard=\tGrp=0\tTZ=".($person['timezone'] ?? 7)."\tPri=".($person['privilege'] ?? 0)),
		];
		$this->db->insert('command', $cmd);

		$fingerprints = $this->general_model->get_fp_member($person['member_id']);

		foreach($fingerprints as $fp)
		{
			$cmd = [
				'sn' 	 => $sn,
				'status' => 1, 
				'cmd'	 => trim("DATA FP PIN=".$person['user_id']."\tFID=".$fp['fp_number']."\tValid=1\tTMP=".$fp['template'])
			];
			$this->db->insert('command', $cmd);
		}

		$this->db->trans_complete();

		if($this->db->trans_status() === FALSE)
		{
			$this->session->set_flashdata('error', ['message' => 'Data gagal di tambah pada alat']);
			redirect(base_url());
			return;
		}

		$this->session->set_flashdata('success', ['message' => 'Data berhasil di tambah pada alat']);
		redirect(base_url());
	}
}
