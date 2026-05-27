<?php
require_once ROOT . "model/admin/dashboardModel.php";
require_once ROOT . "model/admin/articleModel.php";

// TEMPORAIRE — à supprimer quand tu auras la page de login admin
$_SESSION['admin'] = ['prenom' => 'Moussa', 'nom' => 'Diallo', 'email' => 'admin@horizonblog.com'];

$article = function () {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['admin'])) {
        header("Location: " . path('admin', 'login'));
        exit();
    }

    // Badges sidebar
    $nbArticlesEnAttente      = countArticlesEnAttente();
    $nbDemandesEnAttente      = countDemandesEnAttente();
    $nbSignalementsNonTraites = countSignalementsNonTraites();

    /* ── Actions POST ── */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postAction = $_POST['post_action'] ?? '';
        $id         = (int) ($_POST['id'] ?? 0);

        // Approuver un article
        if ($postAction === 'approuver' && $id > 0) {
            adminUpdateStatutArticle($id, 'Actif');
            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Article approuvé avec succès.'];
        }

        // Invalider un article
        if ($postAction === 'invalider' && $id > 0) {
            adminUpdateStatutArticle($id, 'Invalide');
            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Article invalidé.'];
        }

        // Supprimer (soft) un article
        if ($postAction === 'supprimer' && $id > 0) {
            adminUpdateStatutArticle($id, 'Inactif');
            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Article supprimé.'];
        }

        // Restaurer un article
        if ($postAction === 'restaurer' && $id > 0) {
            adminUpdateStatutArticle($id, 'Actif');
            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Article restauré.'];
        }

        // Supprimer définitivement un article
        if ($postAction === 'supprimer_definitif' && $id > 0) {
            adminDeleteArticle($id);
            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Article supprimé définitivement.'];
        }

        // Supprimer un commentaire
        if ($postAction === 'supprimer_commentaire' && $id > 0) {
            adminDeleteCommentaire($id);
            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Commentaire supprimé.'];
        }

        // Redirige pour éviter re-soumission du form
        $redirect = $_POST['redirect'] ?? path('admin', 'article');
        header("Location: $redirect");
        exit();
    }

    /* ── Vue détail article ── */
    if (!empty($_GET['detail'])) {
        $articleId  = (int) $_GET['detail'];
        $article    = adminGetArticleDetail($articleId);
        if (!$article) {
            $_SESSION['flash'] = ['type' => 'err', 'msg' => 'Article introuvable.'];
            header("Location: " . path('admin', 'article'));
            exit();
        }
        $commentaires = adminGetCommentairesArticle($articleId);
        $categories   = getCategoriesDetailByArticle($articleId);
        $pageTitle    = 'Détail article';

        loadView("admin/detail", compact(
            'article', 'commentaires', 'categories',
            'nbArticlesEnAttente', 'nbDemandesEnAttente', 'nbSignalementsNonTraites',
            'pageTitle'
        ), 'admin');
        return;
    }

    /* ── Liste articles ── */
    $statut    = trim($_GET['statut']    ?? '');
    $search    = trim($_GET['q']         ?? '');
    $auteurId  = (int) ($_GET['auteur']  ?? 0);
    $page      = max(1, (int) ($_GET['page'] ?? 1));
    $tri       = $_GET['tri']   ?? 'date_creation';
    $ordre     = strtoupper($_GET['ordre'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
    $perPage   = 15;

    $statutsValides = ['Actif', 'En attente', 'Invalide', 'Inactif'];
    if (!in_array($statut, $statutsValides, true)) $statut = '';

    $totalArticles = adminCountArticles($statut, $search, $auteurId);
    $totalPages    = (int) ceil($totalArticles / $perPage);
    $page          = min($page, max(1, $totalPages));

    $articles  = adminGetArticles($statut, $search, $auteurId, $page, $perPage, $tri, $ordre);
    $auteurs   = adminGetAuteursPourFiltre();

    $flash     = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    $pageTitle = 'Articles';

    loadView("admin/article", compact(
        'articles', 'auteurs', 'statut', 'search', 'auteurId',
        'page', 'totalPages', 'totalArticles', 'tri', 'ordre',
        'flash', 'nbArticlesEnAttente', 'nbDemandesEnAttente',
        'nbSignalementsNonTraites', 'pageTitle'
    ), 'admin');
};

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
    "article"   => $article,
    "detail" => $detail
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