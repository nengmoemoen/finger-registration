<?php 
require_once __DIR__.'/classes/db.class.php';
require_once __DIR__.'/classes/message.class.php';

$message = new Message();

if(!$_SERVER['REQUEST_METHOD'] === 'POST')
{
    http_response_code(405);
    die('Metode Tidak Di Izinkan');
}

/*
Array ( [userid] => 1 [nik] => naquib [privilege] => 0 [fingerid] => 5 [fp-template] => Array ( [0] => [1] => [2] => [3] => [4] => [5] => TcFTUzIxAAAEgoIECAUHCc7QAAAcg2kBAAAAhK8lg4KBAEgPswBGADqNRwB/AE4PXgBAgmQPugDyAMIPJ4K7AEkPHQBmAEuNvAAsAA4PkgAvgmIPFQDnAPsNNYIlAOQPRgDoAaKF+wAXACIDswCxgsMPbwBpAJQPq4JMAAUP0gARAL6PqAD7ABgPDwD3go4NzQBIAEAPHIJsAFMPzADIAYuN8AD9AGsPJwAZg+gPLAAeAWkPboKUAEMPaQAVADyNWwBVAFYPIgCdgjwP1wDkAGYN6oLFAL4PlwDhAHSNQQA4AOMPWgAkgxUPUwAWAKEP/4IxAJoDRQDyAZ+G2/iiBT4PA/sHuFYCSg8H+Ob8KeDb9AZbfXgg8W7m7P6HDA//rArrfBYCnojGCTui0YOIg6aCE/S2lw9xwAKOgaaC5Q92foJrnYrTF3qfFg5zgY9+0f+XCHKRDKJ2gE75PBgWrrP8VRFiiXMGcQrQ7S0DdoBjeIejPgRrBgtzaAMJKZuIaoW2yWfeNd1rgUNc6ggzEYPxc4Em/db323gPddbzSQfOdi/3egHWApOHkej4FUoeLwQeHn6Diwwe5D5sRQKJgweOPnxehEMNVQFLgXYApHpXFWuPbfeCc96LYau2QnogO4MDoyUfBADYAGL7BAAqAGbAswcEtQFmcHwJAIAAbUBldwsAVgC1w8VCw1GEBQBkxW18hgGEAHqLCcW6BITB/f/+QgXF3QSS/0oFAO4A1f3EfAoAtgWGg7BwAoK/CQk1/wjFtggCwYCECgBP02bEQobAbQMA+9wexIkBUhpgYmQEYQuClSVwwnJwrGoCgr4sD0T+DsVYK9zB/8L/i1i0HQR7MJqBkG+MocB638F0wQUAPPtcZooBmUVkwcFOwgmCzUiMw6CLB8F/igGzSwMu/u4WBYXZSS/9/f06/Pp/wf7/////Ov/7QgsAq1FtwgFnxuILALNRBv85/y3EBgD4yEYqP/0SgvY4l3KTwAd5xO92WRIAWVmfwvoGwGZpgMEDxBZfv+cjAPpEnATBxUOSw8DBwYyI/25Cw8DBhGnAARME7WpQc37AwUHBxUN0BAAUb1e9BgSYcVBVwikAfH0zQsD9wsDCwwrIxtmSpcXGwcIH/8dCw//CwsHDAcLAQMTBEQBGgIn/xn3AeMGEb8LMALAEQXh1wyIAPFygAnjBknx1UrF0x0LAwMKGDwCimE31XoR8wQwAqJhH+sBpfAQA51k6UIsBG6RQwMAHX8WTASGmTFpnZX7HfP4QAHi7PQdxxQKXwo4EACB5TMVCDwAlvEbBOomHQf+LGQDxwgaLekDFw8PExJaswXxBBQEQw71s5wD96ajCfJLDwFj+wEPA/3jBacEEwcVDwIQXAO3KjP74evr9/f/+/wf9+n/19kD/WwbEEM/BOf4LAGnU8Wd8Q44WANjZyQdtxg3EycPDfsGyxQyCFehGlcFvwAAdajyMAwCt/NLBAIKo/iSlAwA1/2B/CRD4JHD/OzH7hxELHC3Ed80Q869s/sD7wCrGEAalNcM= [6] => [7] => [8] => [9] => ) )
*/

// // cek mandatoru
// $userid = trim($_POST['userid']);
// $nickname = trim($_POST['nik']);
// $privilege = intval($_POST['privilege']);
// $templates = $_POST['fp-template'];
// $fingerprint = array_filter($templates);
$userid = 1;
$nickname = 'naquib';
$privilege = 0;
$templates = [
    0   => NULL,
    1   => NULL,
    2   => NULL,
    3   => NULL,
    4   => NULL,
    5   => trim('TcFTUzIxAAAEgoIECAUHCc7QAAAcg2kBAAAAhK8lg4KBAEgPswBGADqNRwB/AE4PXgBAgmQPugDyAMIPJ4K7AEkPHQBmAEuNvAAsAA4PkgAvgmIPFQDnAPsNNYIlAOQPRgDoAaKF+wAXACIDswCxgsMPbwBpAJQPq4JMAAUP0gARAL6PqAD7ABgPDwD3go4NzQBIAEAPHIJsAFMPzADIAYuN8AD9AGsPJwAZg+gPLAAeAWkPboKUAEMPaQAVADyNWwBVAFYPIgCdgjwP1wDkAGYN6oLFAL4PlwDhAHSNQQA4AOMPWgAkgxUPUwAWAKEP/4IxAJoDRQDyAZ+G2/iiBT4PA/sHuFYCSg8H+Ob8KeDb9AZbfXgg8W7m7P6HDA//rArrfBYCnojGCTui0YOIg6aCE/S2lw9xwAKOgaaC5Q92foJrnYrTF3qfFg5zgY9+0f+XCHKRDKJ2gE75PBgWrrP8VRFiiXMGcQrQ7S0DdoBjeIejPgRrBgtzaAMJKZuIaoW2yWfeNd1rgUNc6ggzEYPxc4Em/db323gPddbzSQfOdi/3egHWApOHkej4FUoeLwQeHn6Diwwe5D5sRQKJgweOPnxehEMNVQFLgXYApHpXFWuPbfeCc96LYau2QnogO4MDoyUfBADYAGL7BAAqAGbAswcEtQFmcHwJAIAAbUBldwsAVgC1w8VCw1GEBQBkxW18hgGEAHqLCcW6BITB/f/+QgXF3QSS/0oFAO4A1f3EfAoAtgWGg7BwAoK/CQk1/wjFtggCwYCECgBP02bEQobAbQMA+9wexIkBUhpgYmQEYQuClSVwwnJwrGoCgr4sD0T+DsVYK9zB/8L/i1i0HQR7MJqBkG+MocB638F0wQUAPPtcZooBmUVkwcFOwgmCzUiMw6CLB8F/igGzSwMu/u4WBYXZSS/9/f06/Pp/wf7/////Ov/7QgsAq1FtwgFnxuILALNRBv85/y3EBgD4yEYqP/0SgvY4l3KTwAd5xO92WRIAWVmfwvoGwGZpgMEDxBZfv+cjAPpEnATBxUOSw8DBwYyI/25Cw8DBhGnAARME7WpQc37AwUHBxUN0BAAUb1e9BgSYcVBVwikAfH0zQsD9wsDCwwrIxtmSpcXGwcIH/8dCw//CwsHDAcLAQMTBEQBGgIn/xn3AeMGEb8LMALAEQXh1wyIAPFygAnjBknx1UrF0x0LAwMKGDwCimE31XoR8wQwAqJhH+sBpfAQA51k6UIsBG6RQwMAHX8WTASGmTFpnZX7HfP4QAHi7PQdxxQKXwo4EACB5TMVCDwAlvEbBOomHQf+LGQDxwgaLekDFw8PExJaswXxBBQEQw71s5wD96ajCfJLDwFj+wEPA/3jBacEEwcVDwIQXAO3KjP74evr9/f/+/wf9+n/19kD/WwbEEM/BOf4LAGnU8Wd8Q44WANjZyQdtxg3EycPDfsGyxQyCFehGlcFvwAAdajyMAwCt/NLBAIKo/iSlAwA1/2B/CRD4JHD/OzH7hxELHC3Ed80Q869s/sD7wCrGEAalNcM='),
    6   => NULL,
    7   => NULL,
    8   => NULL,
    9   => NULL
];
$fingerprint = array_filter($templates);

if(empty($userid) || empty($nickname) || count($templates) == 0)
{
    $message->setMessage(['message' => 'Data berhasil di simpan', 'type' => 'success']);
    header('Location: '.$_SERVER['HTTP_REFERER']);
    return;
}

// set db
$db = db::getInstance();

$db->beginTransaction();
try
{
    $query = $db->prepare('INSERT INTO members(user_id, nickname, privilege) VALUES(:userid, :nick, :priv)');
    $query->execute([':userid' => $userid, ':nick' =>  $nickname, ':priv' => $privilege]);
    $memberId = $db->lastInsertId();

    foreach($fingerprint as $k => $v)
    {
        $query = $db->prepare('INSERT INTO fingerprint(member, fp_number, template) VALUES(:member, :fp_no, :template)');
        $query->execute([':member' => $memberId, ':fp_no' =>  $k, ':template' => $v]);
    }
    $db->commit();
    $message->setMessage(['message' => 'Data berhasil di simpan', 'type' => 'success']);
}
catch(PDOException $e)
{
    $db->rollBack();
    $message->setMessage(['message' => 'Data gagal di simpan', 'type' => 'error']);
}

$query = NULL;
$db = NULL;
header('Location: '.$_SERVER['HTTP_REFERER']);

?>