<?php

class Getrequest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('general_model');
    }

    public function index() {
        $sn = $this->input->get('SN');
        $commands = $this->general_model->get_commands($sn, 50);
       
        header('Content-Type: text/plain');

        if(count($commands) == 0 )
        {
            echo 'OK';
            return;   
        }

        function mapper($val) { return 'C:'.$val['id'].':'.$val['cmd']; };
        $devcmd = array_map('mapper', $commands);
        
        echo implode("\n", $devcmd);
      
    }
}