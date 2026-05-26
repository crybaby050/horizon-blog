<?php
session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define("WEBROOT","http://localhost:8000/");
define("ROOT", str_replace("public","",$_SERVER['DOCUMENT_ROOT']));

require_once ROOT."config/helper.php";
require_once ROOT. "routes/router.php"; 
