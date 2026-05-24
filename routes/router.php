<?php
$controllers=[
    "visiteur"=>"visiteur",
    "auteur"=>"auteur",
    "admin"=>"admin"

];

$controller=$_REQUEST["controller"]??"visiteur";

if (array_key_exists($controller, $controllers)) {
    $path=ROOT."controller/".$controllers[$controller]."Controller.php";
    }
    else{
        echo "controller introuvable";
        exit();
}

require_once($path);