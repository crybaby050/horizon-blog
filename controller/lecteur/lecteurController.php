<?php
require_once ROOT . "/model/lecteur/lecteurModel.php";

/* ── HOME ────────────────────────────────────────────── */
$home = function () {
    $articles = getArticleVisuel();

    foreach ($articles as &$article) {
        $article['categories'] = getCategoriesByArticle((int) $article['id']);
    }
    unset($article);

    $categories = getPrincipalCategorie();

    loadView("lecteur/home", compact('articles', 'categories'));
};

/* ── LISTE DES ARTICLES (avec filtres + pagination PHP) ── */
$article = function () {
    // ── Paramètres GET ─────────────────────────────────
    $statut  = trim($_GET['statut'] ?? '');
    $search  = trim($_GET['q']      ?? '');
    $page    = max(1, (int) ($_GET['page'] ?? 1));
    $perPage = 9; // articles par page

    // Statuts autorisés (whitelist)
    $statutsValides = ['Actif', 'En attente', 'Invalide', 'Valide'];
    if (!in_array($statut, $statutsValides, true)) {
        $statut = '';
    }

    // ── Données ────────────────────────────────────────
    $totalArticles = countArticles($statut, $search);
    $totalPages    = (int) ceil($totalArticles / $perPage);
    $page          = min($page, max(1, $totalPages)); // borne supérieure

    $articles = getArticlesFiltres($statut, $search, $page, $perPage);

    // Catégories de chaque article
    foreach ($articles as &$art) {
        $art['categories'] = getCategoriesByArticle((int) $art['id']);
        // Valeurs par défaut pour vues et commentaires (à compléter si les colonnes existent)
        $art['vues']        = $art['vues']        ?? 0;
        $art['commentaires'] = $art['commentaires'] ?? 0;
    }
    unset($art);

    loadView("lecteur/article", compact(
        'articles',
        'statut',
        'search',
        'page',
        'totalPages',
        'totalArticles'
    ));
};

$categorie = function(){
    loadView("lecteur/categorie");
};

/* ── DISPATCH ────────────────────────────────────────── */
$actions = [
    "home"    => $home,
    "article" => $article,
    "categorie" => $categorie
];

$action = $_REQUEST["action"] ?? "home";

// Passe l'action courante au layout pour le menu actif
$GLOBALS['currentAction'] = $action;

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    http_response_code(404);
    echo "Page introuvable";
    exit();
}