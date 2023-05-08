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
			$data = ['ip_address' => $ip_address, 'sn' => $sn];
			$this->general_model->upsert_device($data);

			if(isset($_GET['options']) && $_GET['options'] === 'all')
			{

				$resp="GET OPTION FROM:".$sn."\n";

				$resp .= "Stamp=82983982\n";
				$resp .= "OpStamp=9238883\n";
				$resp .= "PhotoStamp=9238833\n";
				$resp .= "ErrorDelay=60\n";
				$resp .= "Delay=30\n";
				$resp .= "TransTimes=00:00;14:05\n";
				$resp .= "TransInterval=1\n";
				$resp .= "TransFlag=1111000000\n";
				$resp .= "TimeZone=7\n";
				$resp .= "Realtime=1\n";			
				$resp .= "Encrypt=0\n";			
							
				header('Content-type: text/plain');				
				echo $resp;	
			}
		}
		// POST
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$table = trim($_GET['table']);
			$content = file_get_contents('php://input');

			switch($table)
			{
				case 'OPERLOG':
					$this->operlog($sn, $content);
					break;
				case 'ATTLOG':
					$this->attlog($sn, $content);
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
			$params = preg_split('/\s+/', trim($row));
			$key = trim($params[0]);
	
			// reduce
			unset($params[0]);
			$map = array_reduce($params, 'reduce', []);
	
			
			switch($key)
			{
				case 'USER':
					try
					{
						
						$data = ['user_id' => $map['pin'], 'nickname' => $map['name'], 'privilege' => $map['pri'], 'sn' => $sn];
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
			$this->db->trans_complete();
	
			header('Content-type: text/plain');
			echo 'OK';
		}
		// params
		
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
				$this->db->insert('transactions', $data);
			}
		   
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
