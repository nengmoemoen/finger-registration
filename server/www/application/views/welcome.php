<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Fingerprint</title>
        <link rel="stylesheet" href="<?=html_escape('assets/node_modules/mini.css/dist/mini-default.css')?>">
        <style>
            .btn-operation {
                position: relative;
            }
            .btn-operation ul {
                position: absolute;
                left: 0;
                width: 10rem;
                top: 100%;
                z-index: 500;
                bottom: 0;
                text-decoration: none;
                list-style: none;
                border: 1px solid silver;
                border-radius: 6px;
            }
            .btn-operation ul > li {
                position: relative;
            }
            .mt-1 {
                margin-top: 1rem;
            }
            .mt-2 {
                margin-top: 1.5rem;
            }
            table td {
                align-items: center;
            }
        </style>
    </head>
    <body>
        <header></header>
        <main class="container mt-2">
            <div class="col-12">
                <?php if(isset($_SESSION['error'])): ?>
                    <mark class="secondary"><?=$_SESSION['error']['message']?></mark>
                <?php endif; ?>
                <?php if(isset($_SESSION['success'])): ?>
                    <mark class="tertiary"><?=$_SESSION['success']['message']?></mark>
                <?php endif; ?>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>UserID</th>
                        <th>Nickname</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 0; 
                        foreach($members as $member):
                            $i++; 
                    ?>
                        <tr>
                            <td data-label="no"><?=$i?></td>
                            <td data-label="user_id"><?=$member['user_id']?></td>
                            <td data-label="nickname"><?=$member['nickname']?></td>
                            <td>
                                <span class="btn-operation">
                                    <a href="<?=base_url('index/erase_dev?userid='.$member['user_id'])?>" class="button small secondary">Hapus alat</a>
                                    <a href="<?=base_url('index/add_dev?userid='.$member['user_id'])?>" class="button small tertiary">Tambah alat</a>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
      
    </body>
</html>