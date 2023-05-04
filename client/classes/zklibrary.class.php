<?php 
/**
 * Class Zkemkeeper using php extension php_com_dotnet
 */
class Zkemkeeper {

    private $iMachineNumber;
    private  $ip_address;
    private $port;
    private $com;
    
    /**
     * Contrcutor 
     *
     * @param string $ip
     * @param integer $port
     */
    public function __construct($ip = '192.168.1.201', $port = 4370) {
        $this->com = new COM('zkemkeeper.zkem.1');
        $this->ip_address = $ip;
        $this->port = $port;
        $this->iMachineNumber = 1;
    }

    /**
     * Connect Device
     *
     * @return bool
     */
    public function connect(): bool {
        $bool = $this->com->Connect_Net($this->ip_address, $this->port);
        return $bool;
    }

    /**
     * Disconnect Device
     *
     * @return void
     */
    public function disconnect(): void {
        $this->com->Disconnect();
    }

    /**
     * Get Device SN
     *
     * @return string
     */
    public function getSerialNumber(): string {
        $sn = '';
        $this->com->GetSerialNumber($this->iMachineNumber, $sn);
        return $sn;
    }
    
    
}

?>