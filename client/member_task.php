<?php
require_once __DIR__.'/classes/curl.class.php';
require_once __DIR__.'/classes/zklibrary.class.php';

$zk = new Zkemkeeper('192.168.1.55', 4370);

if(!$zk->connect())
    die('Tidak dapat konek');

$members = (new Curl())->request('http://path/to/api/server'); // uncomment this if API alreadey exists

// disable device before update
$zk->enableDevice(false);
// invoke bactch update: 0 means not to override fingerprints, 1 means override fingerprint. No need to voerride fingerprint 
$zk->beginBatchUpdate(0);

// loop members data that consummed from API
foreach($members as $member) {
    $id = $member['user_id']; // ID personnel in device
    $name = $member['nickname']; // Nickname personnel in device
    $password = ''; // personnel pin for alternate verification. Leave blank if unused
    $privilege = $member['privilege']; // personnel privilege on device. 0 -> Normal; 1 -> registrar; 2 -> admin; 3 -> super admin
    $enabled = $member['active']; // set member active or not

    $zk->setUserInfo($id, $name, $password, $privilege, $enabled);
}
// mass update
$zk->batchUpdate();
// refresh data to apply update immediately
$zk->refresh();
// enable device
$zk->enableDevice(true);


$zk->disconnect();
?>