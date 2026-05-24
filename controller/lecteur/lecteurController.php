<?php
require_once ROOT."/model/lecteur/lecteurModel.php";

$home = function(){
    $articles = getArticleVisuel();

    // Pour chaque article, on récupère ses catégories séparément
    foreach($articles as &$article){
        $article['categories'] = getCategoriesByArticle($article['id']);
    }
    unset($article); // bonne pratique après un foreach par référence

    $categories = getPrincipalCategorie();
    loadView("lecteur/home", compact('articles', 'categories'));
};

$article = function(){
    loadView("lecteur/article");
};

$actions=[
    "home"=>$home,
    //"categorie"=>$categorie,
    "article"=>$article,
    //"contact"=>$contact
];
$action=$_REQUEST["action"]??"home";

if (array_key_exists($action, $actions)) {
    $actions[$action]();
}else{
    echo "page introuvable ";
    exit();
}