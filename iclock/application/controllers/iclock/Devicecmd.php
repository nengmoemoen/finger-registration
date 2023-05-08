<?php

class Devicecmd extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('general_model');
    }

    public function index() {
        $sn = $this->input->get('sn');

        header('Content-Type: text/plain');
        // print all commmand
        $input = explode("\n", $raw);
		foreach($input as $in) {
			$cmd = []; $data = [];
			if(empty($in)) continue;
			parse_str($in, $cmd);
			$data = [
				'sn'            => $sn,
				'returntime'    => date('Y-m-d H:i:s'),
				'returnvalue'   => $cmd['Return']
			];

            if($cmd['Return'] < 0)
			    $this->db->update('command', $data, ['id' => $cmd['ID']]);
            else
                $this->db->delete('command', ['id' => $cmd['ID']]);
		}
        
        echo 'OK';
    }
}