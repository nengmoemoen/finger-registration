<?php 

    $uri = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>HOME</title>
        <link rel="stylesheet" href="<?=trim($uri)?>/assets/libs/milligram/milligram.min.css">
    </head>
    <body>
        <h5>CONTOH MENGHAPUS FINGERPRINT</h5>
        <main class="container"> 
            <div class="row">

            </div>
        </main>
    </body>
</html>
