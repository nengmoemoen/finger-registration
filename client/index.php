<?php 
    $url = 'http://'.$_SERVER['HTTP_HOST'].'/fingerprint/client';
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
        <main>
            <form name="form-member">
                <fieldset>
                    <legend>PROFILE</legend>
                    <label>User ID (Kode unik personal untuk Mesin wajib integer) <small class="mandatory-field">*</small></label>
                    <input type="number" min="1" name="userid" required/>
                    <label>nickname <small class="mandatory-field">*</small></label>
                    <input type="text" name="nik">
                    <label> Jari</label>
                    <div id="fp-container">
                        <fieldset>
                            <h4>KIRI</h4>
                            <label><input type="radio" name="fingerid" value="0"> Jempol</label>
                            <label><input type="radio" name="fingerid" value="1"> Telunjuk</label>
                            <label><input type="radio" name="fingerid" value="2"> Tengah</label>
                            <label><input type="radio" name="fingerid" value="3"> Manis</label>
                            <label><input type="radio" name="fingerid" value="4"> Kelingking</label>
                        </fieldset>

                        <fieldset>
                            <h4>KANAN</h4>
                            <label><input type="radio" name="fingerid" value="5"> Jempol</label>
                            <label><input type="radio" name="fingerid" value="6"> Telunjuk</label>
                            <label><input type="radio" name="fingerid" value="7"> Tengah</label>
                            <label><input type="radio" name="fingerid" value="8"> Manis</label>
                            <label><input type="radio" name="fingerid" value="9"> Kelingking</label>
                        </fieldset>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>FINGERPRINT</legend>
                    <span style="display: flex; flex-wrap:nowrap; align-item: center; margin-bottom: 12px">
                        <img onerror="this.style.display = 'none'" id="finger-image">
                        <span id='capture-count'></span>
                    </span>
                    <button type="button" class="primary" id="start-reg">Mulai Daftar</button>
                    <button type="button" class="danger" id="cancel-reg">Batalkan</button>
                    <div>
                        <span id="capture-stat"></span>
                    </div>
                    <div>
                        <progress id="template-quality" hidden></progress>
                    </div>
                    <input type="hidden" name="fp-template"/>
                </fieldset>
                <div>
                    <button type="submit" class="primary">Submit</button>
                </div>
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