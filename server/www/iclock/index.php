<?php  

$req = $_SERVER['REQUEST_URI'];

$uri = explode('/', $req);
$_uri = explode('?', $uri[2]);

if(preg_match_all('/\/iclock\/'.trim($_uri[0]).'(&|\?)(\w+)=(\w+)/i', $req))
	require __DIR__.'/iclock/'.trim($_uri[0]).'.php';

?>