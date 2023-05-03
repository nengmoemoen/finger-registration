<?php 
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
    
}

?>