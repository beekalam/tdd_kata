<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "WebServer.php";
$server = new \App\webserver\WebServer();
if (isset($_GET['p']) && !empty($_GET['p'])) {
    echo $server->start($_GET['p']);
} else if (isset($_GET['f']) && !empty($_GET['f'])) {
    $server->readFile($_GET['f']);
} else {
    echo $server->start();
}
