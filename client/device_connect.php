<?php 

require_once __DIR__.'/classes/db.class.php';
require_once __DIR__.'/classes/zklibrary.class.php';

$ip      = trim($_POST['ip_address']);
$netmask = trim($_POST['netmask']);
$gateway = trim($_POST['gateway']);

if(!filter_var($ip, FILTER_VALIDATE_IP))
{
    http_response_code(422);
    echo json_encode(['message' => 'Format Alamat IP tidak valid', 'type' => 'error']);
    return;
}

// 
$com = new Zkemkeeper('192.168.1.55', 4370);

if(!$com->connect())
{
    http_response_code(422);
    echo json_encode(['message' => 'Alat tidak terhubung', 'type' => 'error']);
    return;
}

$sn = $com->getSerialNumber();
$com->disconnect();

http_response_code(200);
echo json_encode(['message' => 'Alat berhasil terhubung', 'type' => 'success', 'data' =>  ['sn' => $sn]]);
?>