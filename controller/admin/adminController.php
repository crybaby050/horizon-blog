<?php
require_once ROOT . "model/admin/dashboardModel.php";

// TEMPORAIRE — à supprimer quand tu auras la page de login admin
//$_SESSION['admin'] = ['prenom' => 'Moussa', 'nom' => 'Diallo', 'email' => 'admin@horizonblog.com'];

if (session_status() === PHP_SESSION_NONE) session_start();

$dashboard = function () {
    if (empty($_SESSION['admin'])) {
        header("Location: " . path('admin', 'login'));
        exit();
    }

    $stats                    = getStatsGlobales();
    $statsStatuts             = getStatsArticlesParStatut();
    $articlesParMois          = getArticlesParMois();
    $derniersArticles         = getDerniersArticles();
    $derniersSignalements     = getDerniersSignalements();
    $nbArticlesEnAttente      = countArticlesEnAttente();
    $nbDemandesEnAttente      = countDemandesEnAttente();
    $nbSignalementsNonTraites = countSignalementsNonTraites();
    $pageTitle                = 'Dashboard';

    loadView("admin/dashboard", compact(
        'stats', 'statsStatuts', 'articlesParMois',
        'derniersArticles', 'derniersSignalements',
        'nbArticlesEnAttente', 'nbDemandesEnAttente',
        'nbSignalementsNonTraites', 'pageTitle'
    ), 'admin');
};

/* ── DISPATCH ── */
$actions = [
    "dashboard" => $dashboard,
    // "article"   => $article,   // à ajouter plus tard
    // "auteur"    => $auteur,
    // "signalement" => $signalement,
];

$action = $_REQUEST["action"] ?? "dashboard";
$GLOBALS['currentAction'] = $action;

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    http_response_code(404);
    echo "Page introuvable";
    exit();
}