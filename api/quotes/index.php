<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}
else if($method == 'GET'){
    $url = $_SERVER['REQUEST_URI'];
    $url = explode('/', $url);
    $param = array_pop($url);
    if(str_contains($param, '?id=')){
        require 'read_single.php';
    }
    else{
        require 'read.php';
    }
}
else if($method == 'POST'){
    require 'create.php';
}
else if($method == 'PUT'){
    require 'update.php';
}
else if($method == 'DELETE'){
    require 'delete.php';
}