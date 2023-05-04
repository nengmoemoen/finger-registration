<?php

require_once __DIR__.'/../classes/db.class.php';

$sn = trim($_GET['SN']);
$db = db::getInstance();

// GET
if($_SERVER['REQUEST_METHOD'] === 'GET')
{
    if(isset($_GET['options']) && $_GET['options'] === 'all')
    {
        $resp="GET OPTION FROM:".$sn."\n";
        foreach($deviceconf as $devcon) {
            $resp .= 'Stamp='.$devcon->stamp."\n";
            $resp .= 'OpStamp='.$devcon->opstamp."\n";
            $resp .= 'PhotoStamp='.$devcon->photostamp."\n";
            $resp .= "ErrorDelay=".$devcon->errdelay."\n";
            $resp .= "Delay=".$devcon->delay."\n";
            $resp .= "TransTimes=".$devcon->transtimes."\n";
            $resp .= "TransInterval=".$devcon->transinterval."\n";
            $resp .= "TransFlag=".$devcon->transflag."\n";
            $resp .= "TimeZone=".$devcon->timezone."\n";
            $resp .= "Realtime=".$devcon->realtime."\n";
            $resp .= "Encrypt=".$devcon->encrypt."\n";					
        }										
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
            operlog($sn, $content);
            break;
        case 'ATTLOG':
            attlog($sn, $content);
            break;
    }

  
}


// FUNCTION 
function operlog($sn, $content) {
    // split rows by endline
    $rows = explode("\n", $content);
    // declare reduce cant be inside loop
    function reduce($curr, $item) {
        $i = explode("=", $item);
        $curr[strtolower(trim($i[0]))] = trim($i[1]);
        return $curr;
    };

    global $db;

    foreach($rows as $row)
    {
        $params = preg_split('/\s+/', trim($content));
        $key = trim($params[0]);

        // reduce
        unset($params[0]);
        $map = array_reduce($params, 'reduce', []);

        $db->beginTransaction();
        switch($key)
        {
            case 'USER':
                try
                {
                    $query = $db->prepare('INSERT INTO members(user_id, nickname, privilege, sn) VALUES(:member, :nick, :priv, :sn) 
                                            ON DUPLICATE KEY UPDATE 
                                                user_id=:member, nickname=:nick, privilege=:priv, sn=:sn');
                    $query->execute([':member' => $map['pin'], ':nick' => $map['name'], ':priv' => $map['pri'], ':sn' => $sn]);
                    $id = $db->lastInsertId();

                    $db->commit();

                }
                catch(PDOException $e)
                {
                    file_put_contents(getcwd().'/logs/log_'.date('Ymd').'.txt', '['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
                    $db->rollBack();
                    die('OK');
                }
                // end
                break;
            case 'FP':
                try
                {
                    $query = $db->prepare('INSERT INTO fingerprint(member, fp_number, template) VALUES(:member, :fp_no, :template)
                                            ON DUPLICATE KEY UPDATE 
                                                member=:member, fp_number=:fp_no, template=:template');
                    $query->execute([':member' => $map['pin'], ':fp_no' => $map['fid'], ':template' => $map['tmp']]);
                    $id = $db->lastInsertId();

                }
                catch(PDOException $e)
                {
                    file_put_contents(getcwd().'/logs/log_'.date('Ymd').'.txt', '['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
                    $db->rollBack();
                    die('OK');
                }
                // end
            break;
        }
        $db = NULL;

        unset($map);

        header('Content-type: text/plain');
        echo 'OK';
    }
    // params
    
}

// Transaction
function attlog($sn, $content) 
{
    $rows = explode("\n", $content);
    // declare reduce cant be inside loop
    global $db;
    $db->beginTransaction();
    try
    {
        foreach($rows as $row)
        {
            $params = explode("\t", $row);
            file_put_contents(getcwd().'/text.txt', json_encode($params), FILE_APPEND);
        
            // reduce
            $query = $db->prepare('INSERT INTO transactions(sn, user_id, checktime, checktype, verifycode) VALUES(:sn, :user, :time, :type, :ver_code)
                                    ON DUPLICATE KEY UPDATE
                                    sn=:sn, user_id=:user, checktime=:time, checktype=:type, verifycode=:ver_code');
            $query->execute([':sn' => $sn, ':user' => trim($params[0]), ':time' => trim($params[1]), ':type' => intval($params[2]), ':ver_code' => intval($params[3])]);

        }
        $db->commit();
    }
    catch(PDOException $e)
    {
        file_put_contents(getcwd().'/logs/log_'.date('Ymd').'.txt', '['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
        $db->rollBack();
        die('OK');
    }
    $db = NULL;

    header('Content-type: text/plain');				
    echo "OK\n";
    echo "POST from:".$sn."\n";
   
}
?>

