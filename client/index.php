<?php 

session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = explode('/', $uri);

if(empty($path[1]))
    require_once __DIR__.'/home.php';
else
    require_once __DIR__.'/'.$path[1].'.php';

?>