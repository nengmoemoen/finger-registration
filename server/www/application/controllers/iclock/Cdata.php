<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cdata extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('general_model');
	}
	
	public function index()
	{
		$sn = trim($this->input->get('SN'));
		// GET
		if($_SERVER['REQUEST_METHOD'] === 'GET')
		{

			$ip_address = $_SERVER['REMOTE_ADDR'];
			$device = $this->general_model->get_device_by_sn($sn);

			if(empty($device['sn']))
			{
				$dev = [
					'ip_address'     => $_SERVER['REMOTE_ADDR'],
					'sn'             => $sn,
					'machine_number' => 1,
					'timezone'       => 7,
					'opstamp'        => 0,
					'stamp'          => 0,
					'transflag'      => '1111101000',
				];
				$this->general_model->upsert_device($dev);
				$this->db->insert('command', ['cmd' => 'CHECK']);

				header('Content-Type: text/plain');
				echo 'OK';
				return;
			}
			

			if(!empty($this->input->get('options')) && $this->input->get('options') === 'all')
			{

				$resp="GET OPTION FROM:".$sn."\n";

				$resp .= "Stamp=".$device['stamp']."\n";
				$resp .= "OpStamp=".$device['opstamp']."\n";
				$resp .= "PhotoStamp=0\n";
				$resp .= "ErrorDelay=60\n";
				$resp .= "Delay=30\n";
				$resp .= "TransTimes=00:00;14:05\n";
				$resp .= "TransInterval=1\n";
				$resp .= "TransFlag=".$device['transflag']."\n";
				$resp .= "TimeZone=".$device['timezone']."\n";
				$resp .= "Realtime=1\n";			
				$resp .= "Encrypt=0\n";			

				header('Content-type: text/plain');				
				echo $resp;	
			}
		}
		// POST
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$table = trim($this->input->get('table'));
			$content = file_get_contents('php://input');

			switch($table)
			{
				case 'OPERLOG':
					$this->operlog($sn, $content);
					break;
				case 'ATTLOG':
					$this->attlog($sn, $content);
					break;
				case 'options':
					header('Content-type: text/plain');
					echo 'OK';
					break;
			}

		
		}
	}

	private function operlog($sn, $content) {
		// split rows by endline
		$rows = explode("\n", $content);
		// declare reduce cant be inside loop
		function reduce($curr, $item) {
			$i = explode("=", $item);
			$curr[strtolower(trim($i[0]))] = trim($i[1]);
			return $curr;
		};
		
		$this->db->trans_start();
		foreach($rows as $row)
		{

			if(empty($row)) continue;

			$params = preg_split('/\s+/', trim($row));
			if(trim($params[0]) == 'OPLOG') continue; 

			$key = trim($params[0]);
	
			// reduce
			unset($params[0]);
			$map = array_reduce($params, 'reduce', []);
	
			switch($key)
			{
				case 'USER':
					try
					{
						
						$data = ['user_id' => $map['pin'], 'nickname' => $map['name'], 'privilege' => $map['pri']];
						$this->general_model->upsert_member($data);
	
					}
					catch(Exception $e)
					{
						log_messages('error', $e->__toString());
					}
					// end
					break;
				case 'FP':
					try
					{
						$data = ['member' => $map['pin'], 'fp_number' => $map['fid'], 'template' => $map['tmp']];
						$data = $this->general_model->upsert_finger($data);
						
					}
					catch(Exception $e)
					{
						log_messages('error', $e->__toString());
					}
					// end
				break;
			}
			
		}
		$this->db->trans_complete();
		// params
		$this->db->update('devices', ['opstamp' => $this->input->get('OpStamp')], ['sn' => $sn]);
	
		header('Content-type: text/plain');
		echo 'OK';
	}
	
	// Transaction
	private function attlog($sn, $content) 
	{
		$rows = explode("\n", trim($content));
		// declare reduce cant be inside loop
		try
		{
			foreach($rows as $row)
			{
				$params = explode("\t", $row);

				$data = ['sn' => $sn, 'user_id' => trim($params[0]), 'checktime' => trim($params[1]), 'checktype' => intval($params[2]), 'verifycode' => intval($params[3])];
				$this->general_model->upsert_trans($data);
			}

			$this->db->update('devices', ['stamp' => $this->input->get('Stamp')], ['sn' => $sn]);
		   
		}
		catch(Exception $e)
		{
			log_messages('error', $e->__toString());
		}
	
		header('Content-type: text/plain');				
		echo "OK\n";
		echo "POST from:".$sn."\n";
	   
	}
}
