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
    public function __construct($ip = '192.168.1.201', $port = 4370, $mNumber = 1) {
        $this->com = new COM('zkemkeeper.zkem.1');
        $this->ip_address = $ip;
        $this->port = $port;
        $this->iMachineNumber = $mNumber;
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
    
    /**
     * Input User info to device
     *
     * @param [type] $dwEnrollNumber
     * @param [type] $name
     * @param [type] $password
     * @param [type] $privilege
     * @param [type] $enabled
     * @return boolean
     */
    public function setUserInfo($dwEnrollNumber, $name, $password, $privilege, $enabled): bool {
        return $this->com->SSR_SetUserInfo($this->iMachineNumber, $dwEnrollNumber, $name, $password, $privilege, $enabled);
    }

    /**
     * Input fingerprint to device
     *
     * @param [type] $dwEnrollNummber
     * @param [type] $idx
     * @param [type] $tmp
     * @return boolean
     */
    public function setUserTmp($dwEnrollNummber, $idx, $flag, $tmp): bool {
        return $this->com->SetUserTmpExStr($this->iMachineNumber, $dwEnrollNummber, $idx, $flag, $tmp);
    }

    /**
     * Refresh Machine Data, (usefull after register person or finger)
     *
     * @return boolean
     */ 
    public function refresh(): bool
    {
        return $this->com->RefreshData($this->iMachineNumber);
    }
    
    function __destruct() {
        $this->com->disconnect();
    }
}

?>