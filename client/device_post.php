<?php 
session_start();

require_once __DIR__.'/classes/db.class.php';
require_once __DIR__.'/classes/zklibrary.class.php';
require_once __DIR__.'/classes/curl.class.php';

$db = db::getInstance();

sendDataToServer($_POST);

header('Location: '.$_SERVER['HTTP_REFERER']);

$db = NULL;

function sendDataToServer($data) {
    $curl = new Curl();

    try
    {
        $request = $curl->setOption(['customrequest' => 'POST'])
                        ->setHeader(['Content-Type: multipart/form-data'])
                        ->request('http://localhost:9001/device_post.php', 'POST', $_POST);

        file_put_contents(getcwd().'/test.php', $request);
        $_SESSION['flash_message'] = json_decode($request, TRUE);
    }
    catch(Throwable $e)
    {
        file_put_contents("\n".getcwd().'/logs/log_'.date('Ymd').'.txt', '['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
        $_SESSION['flash_message'] = ['message' => 'Proses gagal', 'type' => 'error'];
    }
}

?>