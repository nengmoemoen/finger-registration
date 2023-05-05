<?php 
    session_start();
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
        <title>DEVICE</title>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <?php include_once __DIR__.'/includes/header.php' ?>
        <main class="container">
            <?php if(isset($_SESSION['flash_message'])): 
                    print_r($_SESSION['flash_message']);
                    $type = $_SESSION['flash_message']['type'] == 'error' ? 'danger' : $_SESSION['flash_message']['type'];
            ?>
                <span class="alert alert-<?=$type?>"><?=$_SESSION['flash_message']['message']?></span>
            <?php endif; unset($_SESSION['flash_message']); ?>
            <form name="form-device" class="row justify-content-center" method="POST" action="<?=$url?>/device_post.php">
                <fieldset class="col-6 ">
                    <legend>DEVICE</legend>
                    <label>IP Address <small class="mandatory-field">*</small></label>
                    <input type="text" name="ip_address" class="form-control" required/>

                    <label>Netmask <small class="mandatory-field">*</small></label>
                    <input type="text" name="netmask" class="form-control" required/>

                    <label>Gateway <small class="mandatory-field">*</small></label>
                    <input type="text" name="gateway" class="form-control" required/>

                    <label>SN <small class="mandatory-field">*</small></label>
                    <input type="text" name="sn" class="form-control" value="A5NM174660155" required readonly/>

                    <div class="w-100 d-flex justify-content-end mt-3 mb-5">
                        <button type="button" id="btn-connect" class="btn btn-sm btn-success">Connect</button>
                        <button type="submit" class="btn btn-sm btn-primary ms-1">Submit</button>
                    </div>
               
                </fieldset>
            </form>
            <table class="table table-sm">
                <thead class="bg-primary text-white">
                    <tr>
                       <th>IP Address</th>
                       <th>Netmask</th>
                       <th>Gateway</th>
                       <th>Serial Number</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </main>
        <?php include_once __DIR__.'/includes/js_lib.php' ?>
        <script src="assets/js/devices.js" async defer></script>
    </body>
</html>