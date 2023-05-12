<?php
require_once __DIR__.'/../classes/db.class.php';
require_once __DIR__.'/../functions/devices.functions.php';

$db = db::getInstance();
$sn = trim($_GET['SN']);

// GET
if($_SERVER['REQUEST_METHOD'] === 'GET')
{
    
    if(isset($_GET['options']) && $_GET['options'] === 'all')
    {

        $device = getDeviceBySN($sn);
        if(empty($device['sn']))
        {
            insertDevice([
                'ip_address'     => $_SERVER['REMOTE_ADDR'],
                'sn'             => $sn,
                'machine_number' => 1,
                'timezone'       => 7,
                'opstamp'        => 0,
                'stamp'          => 0,
                'transflag'      => '1111101000',
            ]);

            header('Content-Type: text/plain');
            echo 'OK';
            return;
        }

        $resp="GET OPTION FROM:".$sn."\n";
        $resp .= 'Stamp='.$device['stamp']."\n";
        $resp .= 'OpStamp='.$device['opstamp']."\n";
        $resp .= 'PhotoStamp=0'."\n";
        $resp .= "ErrorDelay=30\n";
        $resp .= "Delay=60\n";
        $resp .= "TransTimes=00:00;14:05\n";
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
    $table = trim($_GET['table']);
    $content = file_get_contents('php://input');

    switch($table)
    {
        case 'OPERLOG':
            operlog($db, $sn, $content);
            break;
        case 'ATTLOG':
            attlog($db, $sn, $content);
            break;
		case 'options':
			header('Content-Type: text/plain');
			echo 'OK';
			break;
    }
}


?>

