<?php 

require_once __DIR__.'/classes/db.class.php';
require_once __DIR__.'/classes/curl.class.php';


$db = db::getInstance();

$ip      = trim($_POST['ip_address']);
$netmask = trim($_POST['netmask']);
$gateway = trim($_POST['gateway']);
$sn = trim($_POST['sn']);

file_put_contents(getcwd().'/test.txt', json_encode($_POST));

if(!filter_var($ip, FILTER_VALIDATE_IP))
{
    http_response_code(422);
    echo json_encode(['message' => 'Format Alamat IP tidak valid', 'type' => 'error']);
    return;
}

try
{
    $query = $db->prepare('INSERT INTO devices(ip_address, netmask, gateway, sn, machine_number) 
                            VALUES(:ip, :netmask, :gateway, :sn, :num) ON DUPLICATE KEY UPDATE
                            ip_address=:ip, netmask=:netmask, gateway=:gateway, sn=:sn, machine_number=:num');
    $query->execute([':ip' => $ip, ':netmask' => $netmask, ':gateway' => $gateway, ':sn' => $sn, ':num' => 1]);
    $id = $db->lastInsertId();

    http_response_code(200);
    echo json_encode(['message' => 'Data berhasil di simpan', 'type' => 'success']);
    
}
catch(PDOException $e)
{
    file_put_contents("\n".getcwd().DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'log_'.date('Ymd').'.txt', '['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
    http_response_code(500);
    echo json_encode(['message' => 'Data gagal di simpan', 'type' => 'error', 'data' => ['error' => $e->__toString()]]);
}

$db = NULL;



?>