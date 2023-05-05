<?php 
session_start();

require_once __DIR__.'/classes/db.class.php';
require_once __DIR__.'/classes/zklibrary.class.php';
require_once __DIR__.'/classes/curl.class.php';

$db = db::getInstance();

$isSendToServer = sendDataToServer($_POST);

if(!$isSendToServer)
{
    header('Location: '.$_SERVER['HTTP_REFERER']);
    return;
}

$ip      = trim($_POST['ip_address']);
$netmask = trim($_POST['netmask']);
$gateway = trim($_POST['gateway']);
$sn = trim($_POST['sn']);

//file_put_contents(getcwd().'/test.txt', json_encode($_POST));

if(!filter_var($ip, FILTER_VALIDATE_IP))
{
    $_SESSION['flash_message'] = ['message' => 'Format Alamat IP tidak valid', 'type' => 'error'];
    header('Location: '.$_SERVER['HTTP_REFERER']);
    return;
}

try
{
    $query = $db->prepare('INSERT INTO devices(ip_address, netmask, gateway, sn, machine_number) 
                            VALUES(:ip, :netmask, :gateway, :sn, :num) ON DUPLICATE KEY UPDATE
                            ip_address=:ip, netmask=:netmask, gateway=:gateway, sn=:sn, machine_number=:num');
    $query->execute([':ip' => $ip, ':netmask' => $netmask, ':gateway' => $gateway, ':sn' => $sn, ':num' => 1]);
    $id = $db->lastInsertId();

    $_SESSION['flash_message'] = ['message' => 'Data berhasil di simpan', 'type' => 'success'];
    
}
catch(PDOException $e)
{
    file_put_contents("\n".getcwd().'/logs/log_'.date('Ymd').'.txt', '['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
    $_SESSION['flash_message'] = ['message' => 'Data gagal di simpan', 'type' => 'error'];
}
header('Location: '.$_SERVER['HTTP_REFERER']);


$db = NULL;

function sendDataToServer($data) {
    $curl = new Curl();
    $ret = false;
    try
    {
        $request = $curl->setOption(['customrequest' => 'POST'])
                        ->setHeader(['Content-Type: multipart/form-data'])
                        ->request('http://localhost:9001/device_post.php', 'POST', $_POST);

        $message = json_decode($request, TRUE);

        if($message['type'] == 'error') 
            $_SESSION['flash_message'] = $message;

        $ret = true;
    }
    catch(Throwable $e)
    {
        file_put_contents("\n".getcwd().'/logs/log_'.date('Ymd').'.txt', '['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
        $_SESSION['flash_message'] = ['message' => 'Proses gagal', 'type' => 'error'];
    }

    return $ret;
}

?>