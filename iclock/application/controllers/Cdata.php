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
						$this->db->trans_commit();
	
					}
					catch(Exception $e)
					{
						log_messages('error', $e->__toString());
						$this->db->trans_rollack();
					}
					// end
					break;
				case 'FP':
					try
					{
						$query = $this->db->query('INSERT INTO fingerprint(member, fp_number, template) VALUES(:member, :fp_no, :template)
												ON DUPLICATE KEY UPDATE 
													member=:member, fp_number=:fp_no, template=:template');
						$query->execute([':member' => $map['pin'], ':fp_no' => $map['fid'], ':template' => $map['tmp']]);
						$id = $db->lastInsertId();
	
					}
					catch(PDOException $e)
					{
						file_put_contents(getcwd().'/logs/log_'.date('Ymd').'.txt', "\n".'['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
						$db->rollBack();
						die('OK');
					}
					// end
				break;
			}
			$db = NULL;
	
			unset($map);
	
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
		global $db;
		$db->beginTransaction();
		try
		{
			foreach($rows as $row)
			{
				$params = explode("\t", $row);
				file_put_contents(getcwd().'/text.txt', json_encode($params), FILE_APPEND);
			
				// reduce
				$query = $db->prepare('INSERT INTO transactions(sn, user_id, checktime, checktype, verifycode) VALUES(:sn, :user, :time, :type, :ver_code)
										ON DUPLICATE KEY UPDATE
										sn=:sn, user_id=:user, checktime=:time, checktype=:type, verifycode=:ver_code');
				$query->execute([':sn' => $sn, ':user' => trim($params[0]), ':time' => trim($params[1]), ':type' => intval($params[2]), ':ver_code' => intval($params[3])]);
				$db->commit();
	
			}
		   
		}
		catch(PDOException $e)
		{
			file_put_contents(getcwd().'/logs/log_'.date('Ymd').'.txt', "\n".'['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
			$db->rollBack();
			die('OK');
		}
		$db = NULL;
	
		header('Content-type: text/plain');				
		echo "OK\n";
		echo "POST from:".$sn."\n";
	   
	}
}
