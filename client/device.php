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
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>INDEX</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?=$url?>/assets/css/normalize.css?v=<?= time() ?>">
        <link rel="stylesheet" href="<?=$url?>/assets/css/main.css?v=<?= time() ?>">
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <header>
            <div></div>
            <nav>
                <a href="<?=$url?>/member.php" style="margin-left: auto">MEMBER</a>
                <a href="<?=$url?>/device.php" style="margin-left: auto">ALAT</a>
            </nav>
        </header>
        <main>
            <form name="form-device" style="justify-content: center;">
                <fieldset>
                    <legend>DEVICE</legend>
                    <label>IP Address <small class="mandatory-field">*</small></label>
                    <input type="text" name="ip_address" required/>

                    <label>Netmask <small class="mandatory-field">*</small></label>
                    <input type="text" name="netmask" required/>

                    <label>Gateway <small class="mandatory-field">*</small></label>
                    <input type="text" name="gateway" required/>

                    <label>SN <small class="mandatory-field">*</small></label>
                    <input type="text" name="sn" required readonly/>

                    <div style="display: flex; flex-wrap: nowrap; justify-content: end; width: 100%; margin-top: 12px">
                        <button type="submit" class="primary">Submit</button>
                    </div>
               
                </fieldset>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User ID (id pada mesin)</th>
                        <th>Nick nama</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </main>
        
        <script src="assets/js/member.js" async defer></script>
    </body>
</html>