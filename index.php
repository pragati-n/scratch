<?php
error_reporting(0);
define('ROOT_PATH',dirname(__DIR__).'/scratch');
define('BASEURL','/scratch/');
require ROOT_PATH.'/app/server.php';

try {
	$route = $_SERVER['PATH_INFO'] ?? "";
    $server = new server();
    $server->handle($route);
} catch (\Exception $e) {
    echo $e->getMessage();
}

?>