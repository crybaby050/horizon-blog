<?php
$controllers=[
    "lecteur"=>"lecteur",
    "auteur"=>"auteur",
    "admin"=>"admin"

];

$controller=$_REQUEST["controller"]??"lecteur";

if (array_key_exists($controller, $controllers)) {
    $path=ROOT."controller/".$controllers[$controller]."/".$controllers[$controller]."Controller.php";
    }
    else{
        echo "controller introuvable";
        exit();
}

require_once($path);