<?php
require_once __DIR__.'/classes/curl.class.php';
require_once __DIR__.'/classes/zklibrary.class.php';

$zk = new Zkemkeeper('192.168.1.55', 4370);

if(!$zk->connect())
    die('Tidak dapat konek');



$zk->disconnect();
?>