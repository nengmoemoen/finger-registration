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
     * Set Device enable or disable
     *
     * @param boolean $bool
     * @return void
     */
    public function enableDevice(bool $bool) {
        return $this->com->EnableDevice($this->iMachineNumber, $bool);
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
     * invoke Mass update to device
     *
     * @param integer $override
     * @return boolean
     */
    public function beginBatchUpdate(int $override): bool {
        return $this->com->BeginBatchUpdate($this->iMachineNumber, $override);
    }  
    
    /**
     * mass update data to device
     *
     * @return boolean
     */
    public function batchUpdate(): bool {
        return $this->com->BatchUpdate($this->iMachineNumber);
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
     * Read All User infi before Get All User
     *
     * @return boolean
     */
    public function readAllUsers(): bool {
        return $this->com->ReadAllUserID($this->iMachineNumber);
    }

    /**
     * Get All User data from device
     *
     * @return array
     */
    public function getAllUsers(): array {
        $data = [];

        $this->enableDevice(false);
        // set params
        $dwEnrollNumber = NULL;
        $sName = NULL;
        $sPassword = '';
        $iPrivilege = 0;
        $bEnabled = 0;
        // Read All User First
        $this->readAllUsers();
        while($this->com->SSR_GetAllUserInfo($this->iMachineNumber, $dwEnrollNumber,$sName,$sPassword, $iPrivilege, $bEnabled))
        {
            $data[] = ['user_id' => $dwEnrollNumber, 'nickname' => $sName, 'password' => $sPassword, 'privilege' => $iPrivilege, 'enabled' => $bEnabled];
        }   
        $this->enableDevice(true);

        return $data;
    }

    //Generator for GetUser fform device


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