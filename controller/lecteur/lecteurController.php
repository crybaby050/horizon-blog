<?php
require_once ROOT."/model/lecteur/lecteurModel.php";

$home = function(){
    loadView("lecteur/home");
};

$actions=[
    "home"=>$home,
    //"categorie"=>$categorie,
    //"article"=>$article,
    //"contact"=>$contact
];
$action=$_REQUEST["action"]??"home";

if (array_key_exists($action, $actions)) {
    $actions[$action]();
}else{
    echo "page introuvable ";
    exit();
}