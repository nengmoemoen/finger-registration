<?php 
    $url = 'http://'.$_SERVER['HTTP_HOST'];
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <?php include_once __DIR__.'/includes/head.php' ?>
        <title>HOME</title>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <?php include_once __DIR__.'/includes/header.php' ?>
        <main id="index-main">
           <a href="<?=$url?>/member.php" class="card bg-primary text-white" style="width: 24rem; height: 24rem">
                <h3 class="display-1">MEMBER</h3>
           </a>
           <a href="<?=$url?>/device.php" class="card bg-primary text-white" style="width: 24rem; height: 24rem">
                <h3 class="display-1">ALAT</h3>
           </a>
        </main>
        <?php include_once __DIR__.'/includes/js_lib.php' ?>
        <script src="assets/js/member.js" async defer></script>
    </body>
</html>