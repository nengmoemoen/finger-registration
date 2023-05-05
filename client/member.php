<!DOCTYPE html>
<?php 
    require_once __DIR__.'/includes/top.php'; 
    
    function getMembers() {
        $arr = [];
        $db = db::getInstance();

        try
        {
            $query = $db->query("SELECT * FROM members");
            $arr = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {

        }

        $db = NULL;
        return $arr;
    }

    $members = getMembers();
?>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
    <head>
        <?php include_once __DIR__.'/includes/head.php' ?>
        <title>MEMBER</title>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <?php include_once __DIR__.'/includes/header.php' ?>
        <main class="container">
            <?php if(!empty($_SESSION['flash_message'])):?>
                <span class="alert alert-primary"><?= $_SESSION['flash_message']['message'] ?></span>
            <?php 
                unset($_SESSION['flash_message']);
                endif;
            ?>
            <form name="form-member" class="row" method="POST" action="<?php $url?>/member_post.php" enctype="multipart/form-data">
                <fieldset class="col-4 d-flex flex-column">
                    <legend>PROFILE</legend>
                    <label>User ID (Kode unik personal untuk Mesin wajib integer) <small class="mandatory-field">*</small> </label>
                    <input type="number" min="1" name="userid" class="form-control" required/>
                    <labe class="mt-1"l>nickname <small class="mandatory-field">*</small></label>
                    <input type="text" name="nik" class="form-control">
                    <label class="mt-1">Privilege <small class="mandatory-field">*</small>  </label>
                    <select name="privilege" class="form-select">
                        <option value="0">Normal</options>
                        <option value="1">Enroll</options>
                        <option value="2">Admin</options>
                        <option value="3">Super Admin</options>
                    </select>
                   
                </fieldset>
                <!-- JARI -->
                <fieldset class="col-4 d-flex flex-column">
                    <legend> JARI</legend>
                    <div id="fp-container" class="card w-100">
                        <div class="card-body row w-100 flex-nowrap justify-content-between">
                            <fieldset class="d-flex flex-column col-6">
                                <h6>KIRI</h6>
                                <label><input type="checkbox" name="fingerid" value="0"> Jempol</label>
                                <label><input type="checkbox" name="fingerid" value="1"> Telunjuk</label>
                                <label><input type="checkbox" name="fingerid" value="2"> Tengah</label>
                                <label><input type="checkbox" name="fingerid" value="3"> Manis</label>
                                <label><input type="checkbox" name="fingerid" value="4"> Kelingking</label>
                            </fieldset>

                            <fieldset class="d-flex flex-column col-6">
                                <h6>KANAN</h6>
                                <label><input type="checkbox" name="fingerid" value="5"> Jempol</label>
                                <label><input type="checkbox" name="fingerid" value="6"> Telunjuk</label>
                                <label><input type="checkbox" name="fingerid" value="7"> Tengah</label>
                                <label><input type="checkbox" name="fingerid" value="8"> Manis</label>
                                <label><input type="checkbox" name="fingerid" value="9"> Kelingking</label>
                            </fieldset>
                        </div>
                        
                    </div>
                </fieldset>
                <!-- REG FINGERPRINT-->
                <fieldset class="col-4 d-flex flex-column">
                    <legend>FINGERPRINT</legend>
                    <span class="d-flex flex-nowrap w-100">
                        <img onerror="this.style.display = 'none'" id="finger-image">
                        <div class="d-flex flex-column align-items-center" style="width: 12rem">
                            <span id='capture-count'></span>
                            <small id="capture-stat" class="ms-4 mt-3 mb-2"></small>
                            <progress id="template-quality" max="100" hidden></progress>
                        </div>
                    </span>
                    <span class="d-flex flex-nowrap justify-content-end w-75">
                        <button type="button" class="btn btn-sm btn-primary" id="start-reg">Mulai Daftar</button>
                        <button type="button" class="btn btn-sm btn-danger ms-1" id="cancel-reg">Batalkan</button>
                    <span>
                    <input type="hidden" name="fp-template[0]"/>
                    <input type="hidden" name="fp-template[1]"/>
                    <input type="hidden" name="fp-template[2]"/>
                    <input type="hidden" name="fp-template[3]"/>
                    <input type="hidden" name="fp-template[4]"/>
                    <input type="hidden" name="fp-template[5]"/>
                    <input type="hidden" name="fp-template[6]"/>
                    <input type="hidden" name="fp-template[7]"/>
                    <input type="hidden" name="fp-template[8]"/>
                    <input type="hidden" name="fp-template[9]"/>
                   
                </fieldset>
              
                <div class="col-12 mt-2 mb-3 d-flex flex-nowrap justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                </div>
            </form>
            <table class="table">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>No</th>
                        <th>User ID (id pada mesin)</th>
                        <th>Nick nama</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=0; 
                        foreach($members as $member): 
                            $i++;
                            // privilege
                            $priv = NULL;

                            switch($member['privilege']) {
                                case 0:
                                    $priv = 'Normal';
                                    break;
                                case 1:
                                    $priv = 'Enroll';
                                    break;
                                case 2:
                                    $priv = 'Admin';
                                    break;
                                case 3:
                                    $priv = 'Super Admin';
                                    break;
                            }
                    ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$member['user_id']?></td>
                            <td><?=$member['nickname']?></td>
                            <td><?=$priv?></td>
                        </tr>
                    <?php endforeach; unset($members); ?>
                </tbody>
            </table>
        </main>
        <?php include_once __DIR__.'/includes/js_lib.php' ?>
        <script src="assets/js/member.js" async defer></script>
    </body>
</html>