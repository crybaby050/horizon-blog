<?php
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define("WEBROOT","http://localhost:8000/");
define("ROOT", str_replace("public","",$_SERVER['DOCUMENT_ROOT']));

// Parse l'URL propre : /client/liste → controller=client, action=liste
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $uri);

$_REQUEST['controller'] = $segments[0] !== '' ? $segments[0] : 'lecteur';
$_REQUEST['action']     = $segments[1] ?? 'home';
$_REQUEST['id']         = $segments[2] ?? null;

if (isset($segments[2])) {
    $_GET['id'] = $segments[2];
}


require_once ROOT."config/helper.php";
require_once ROOT. "routes/router.php"; 
//dd($_REQUEST['id']);
