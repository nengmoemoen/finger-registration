<?php 

require_once __DIR__.'/classes/db.class.php';
require_once __DIR__.'/classes/zkemkeeper.class.php';

// $db = db::getInstance();

// function getDevice() {
//     global $db;
//     $query = $db->prepare("SELECT * FROM devices LIMIT 1 ORDER BY id DESC");
//     $query->execute();
//     $arr = $query->fetch();
//     $db = NULL;
//     return $arr;
// }

$com = Fingerprint::connect('192.168.1.55', 4370);

var_dump($com->getStatus());

?>