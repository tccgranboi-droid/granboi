<?php
session_start();
require_once "../app/config/config.php";
require_once "../app/models/conexao.php";
require_once "../app/models/boi.php"; 
require_once "../app/controllers/controller.php";

// Pega a URL
$url = isset($_GET['url']) ? $_GET['url'] : '';

if(empty($url)) {
    $url = "home/homeGranboi";
}

$url = explode("/", $url);
$controller = ucfirst($url[0])."Controller"; 
$metodo = isset($url[1]) ? $url[1] : "homeGranboi";

$caminho = "../app/controllers/".$controller.".php";

if(file_exists($caminho)) {
    require_once $caminho;
    $obj = new $controller();
    
    if(method_exists($obj, $metodo)) {
        $parametros = array_slice($url, 2);
        call_user_func_array(array($obj, $metodo), $parametros);
    } else {
        header("Location: " . BASE_URL . "home/homeGranboi");
    }
} else {
    header("Location: " . BASE_URL . "home/homeGranboi");
}