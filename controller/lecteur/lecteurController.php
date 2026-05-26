<?php
require_once ROOT . "/model/lecteur/lecteurModel.php";

/* ── HOME ── */
$home = function () {
    $articles = getArticleVisuel();

    foreach ($articles as &$article) {
        $article['categories'] = getCategoriesByArticle((int) $article['id']);
    }
    unset($article);

    $categories = getPrincipalCategorie(); // 4 catégories avec image + icone + nb_articles

    loadView("lecteur/home", compact('articles', 'categories'));
};

/* ── LISTE DES ARTICLES ── */
$article = function () {
    $statut  = trim($_GET['statut'] ?? '');
    $search  = trim($_GET['q']      ?? '');
    $page    = max(1, (int) ($_GET['page'] ?? 1));
    $perPage = 9;

    $statutsValides = ['Actif', 'En attente', 'Invalide', 'Valide'];
    if (!in_array($statut, $statutsValides, true)) {
        $statut = '';
    }

    $totalArticles = countArticles($statut, $search);
    $totalPages    = (int) ceil($totalArticles / $perPage);
    $page          = min($page, max(1, $totalPages));

    $articles = getArticlesFiltres($statut, $search, $page, $perPage);

    foreach ($articles as &$art) {
        $art['categories']   = getCategoriesByArticle((int) $art['id']);
        $art['vues']         = $art['vues']         ?? 0;
        $art['commentaires'] = $art['commentaires'] ?? 0;
    }
    unset($art);

    loadView("lecteur/article", compact(
        'articles', 'statut', 'search', 'page', 'totalPages', 'totalArticles'
    ));
};

/* ── PAGE CATÉGORIES ── */
$categorie = function () {
    $categories = getAllCategories(); // toutes les 10 catégories
    loadView("lecteur/categorie", compact('categories'));
};

$contact = function (){
    loadView("lecteur/contact");
};

$detail = function () {
    $id = (int) ($_GET['id'] ?? 0);
    // récupérer l'article par id plus tard
    loadView("lecteur/detail", compact('id'));
};

/* ── DISPATCH ── */
$actions = [
    "home"      => $home,
    "article"   => $article,
    "categorie" => $categorie,
    "contact" => $contact,
    "detail" => $detail
];

$action = $_REQUEST["action"] ?? "home";
$GLOBALS['currentAction'] = $action;

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    http_response_code(404);
    echo "Page introuvable";
    exit();
}