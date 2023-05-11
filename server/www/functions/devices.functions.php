<?php 

/**
 * get devices by sn
 *
 * @return array
 */
function getDeviceBySN($db, $sn) 
{
    $dev = [];
    try
    {
        $query = $db->prepare("SELECT * FROM devices WHERE sn=:sn");
        $query->execute([':sn' => $sn]);
        $dev = $query->fetch();

    }
    catch(PDOException $e)
    {
        file_put_contents(getcwd().'/logs/log_'.date('Ymd').'.txt', "\n".'['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
    }
    finally 
    {
        $db = NULL;
    }

    return $dev;
}

function insertDevice() {
    $query = "INSERT INTO devices";
}

function operlog($db, $sn, $content) {
    // split rows by endline
    $rows = explode("\n", $content);
    // declare reduce cant be inside loop
    function reduce($curr, $item) {
        $i = explode("=", $item);
        $curr[strtolower(trim($i[0]))] = trim($i[1]);
        return $curr;
    };

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
                    file_put_contents("\n".getcwd().'/logs/log_'.date('Ymd').'.txt', '['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
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
                    file_put_contents(getcwd().'/logs/log_'.date('Ymd').'.txt', "\n".'['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
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
function attlog($db, $sn, $content) 
{
    $rows = explode("\n", trim($content));
    // declare reduce cant be inside loop
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
            $db->commit();

        }
       
    }
    catch(PDOException $e)
    {
        file_put_contents(getcwd().'/logs/log_'.date('Ymd').'.txt', "\n".'['.date('Y-m-d H:i:s').'] '.$e->__toString(), FILE_APPEND);
        $db->rollBack();
        die('OK');
    }
    $db = NULL;

    header('Content-type: text/plain');				
    echo "OK\n";
    echo "POST from:".$sn."\n";
   
}


?>