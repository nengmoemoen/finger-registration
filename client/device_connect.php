<?php 

require_once __DIR__.'/classes/db.class.php';
require_once __DIR__.'/classes/zklibrary.class.php';

$db = db::getInstance();

function getDevice($db) {
    $query = $db->prepare("SELECT * FROM devices");
    $query->execute();
    $arr = $query->fetch();
    $db = NULL;
    return $arr;
}

?>