<?php
require_once ROOT . "model/admin/adminModel.php";
//$_SESSION['admin'] = ['prenom' => 'Moussa', 'nom' => 'Diallo', 'email' => 'admin@horizonblog.com'];

/* ── Protection accès ── */
$isLogin = ($_REQUEST['action'] ?? '') === 'login';

if (!$isLogin) {
    if (empty($_SESSION['admin']['id'])) {
        header('Location: ' . path('admin', 'login'));
        exit();
    }
}

$nbSignalementsNonTraites = !$isLogin ? getNbSignalementsNonTraites() : 0;

/* ── LOGIN ── */
$login = function () {
    if (!empty($_SESSION['admin']['id'])) {
        header('Location: ' . path('admin', 'dashboard'));
        exit();
    }

    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']        ?? '');
        $mdp   = trim($_POST['mot_de_passe'] ?? '');

        if (empty($email) || empty($mdp)) {
            $error = 'Veuillez remplir tous les champs.';
        } else {
            $admin = loginAdmin($email, $mdp);
            if ($admin) {
                $_SESSION['admin'] = [
                    'id'     => (int)$admin['id'],
                    'prenom' => $admin['prenom'],
                    'nom'    => $admin['nom'],
                    'email'  => $admin['email'],
                ];
                header('Location: ' . path('admin', 'dashboard'));
                exit();
            } else {
                $error = 'Email ou mot de passe incorrect.';
            }
        }
    }

    // Vue login sans layout
    extract(compact('error'));
    require_once ROOT . "view/admin/login.php";
};

/* ── DASHBOARD ── */
$dashboard = function () use ($nbSignalementsNonTraites) {
    $stats          = getStatsGlobales();
    $chartData      = getChartArticlesParMois();
    $derniersArts   = getDerniersArticles(6);
    $derniersSignal = getSignalementsRecents(5);
    loadView("admin/dashboard", compact(
        'stats', 'chartData', 'derniersArts', 'derniersSignal', 'nbSignalementsNonTraites'
    ), "admin");
};

/* ── ARTICLES ── */
$articles = function () use ($nbSignalementsNonTraites) {
    $statut  = trim($_GET['statut'] ?? '');
    $search  = trim($_GET['q']     ?? '');
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    $statutsValides = ['Actif','En attente','Invalide','Valide'];
    if (!in_array($statut, $statutsValides, true)) $statut = '';

    // Actions POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postAction = $_POST['post_action'] ?? '';
        $artId      = (int)($_POST['article_id'] ?? 0);

        if ($postAction === 'valider'   && $artId) updateStatutArticle($artId, 'Actif');
        if ($postAction === 'invalider' && $artId) updateStatutArticle($artId, 'Invalide');
        if ($postAction === 'supprimer' && $artId) deleteArticleAdmin($artId);

        header('Location: ' . path('admin','articles',['statut'=>$statut,'q'=>$search,'page'=>$page]));
        exit();
    }

    $total      = countAllArticles($statut, $search);
    $totalPages = (int)ceil($total / $perPage);
    $page       = min($page, max(1, $totalPages));
    $articles   = getAllArticles($statut, $search, $page, $perPage);

    loadView("admin/article", compact(
        'articles','statut','search','page','totalPages','total','nbSignalementsNonTraites'
    ), "admin");
};

/* ── DETAIL ARTICLE ── */
$article_detail = function () use ($nbSignalementsNonTraites) {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { header('Location: '.path('admin','articles')); exit(); }

    $article = getArticleAdmin($id);
    if (!$article) { header('Location: '.path('admin','articles')); exit(); }

    // Actions POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postAction = $_POST['post_action'] ?? '';

        if ($postAction === 'valider')   updateStatutArticle($id, 'Actif');
        if ($postAction === 'invalider') updateStatutArticle($id, 'Invalide');
        if ($postAction === 'supprimer_article') {
            deleteArticleAdmin($id);
            header('Location: '.path('admin','articles').'&deleted=1');
            exit();
        }
        if ($postAction === 'supprimer_commentaire') {
            $comId = (int)($_POST['comment_id'] ?? 0);
            if ($comId) deleteCommentaireAdmin($comId);
        }

        header('Location: '.path('admin','article_detail',['id'=>$id]));
        exit();
    }

    $commentaires = getCommentairesArticleAdmin($id);
    $categories   = getCategoriesArticleAdmin($id);
    $nbCommentaires = count($commentaires);

    // Re-fetch article après éventuelle mise à jour statut
    $article = getArticleAdmin($id);

    loadView("admin/detail", compact(
        'article','commentaires','categories','nbCommentaires','nbSignalementsNonTraites'
    ), "admin");
};

/* ── AUTEURS ── */
$auteurs = function () use ($nbSignalementsNonTraites) {
    $statut  = trim($_GET['statut'] ?? '');
    $search  = trim($_GET['q']     ?? '');
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    if (!in_array($statut, ['Actif','Inactif'], true)) $statut = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postAction = $_POST['post_action'] ?? '';
        $auteurId   = (int)($_POST['auteur_id'] ?? 0);

        if ($postAction === 'activer'   && $auteurId) updateStatutAuteur($auteurId, 'Actif');
        if ($postAction === 'desactiver'&& $auteurId) updateStatutAuteur($auteurId, 'Inactif');
        if ($postAction === 'supprimer' && $auteurId) deleteAuteurAdmin($auteurId);

        header('Location: '.path('admin','auteur',['statut'=>$statut,'q'=>$search,'page'=>$page]));
        exit();
    }

    $total      = countAllAuteurs($statut, $search);
    $totalPages = (int)ceil($total / $perPage);
    $page       = min($page, max(1, $totalPages));
    $auteurs    = getAllAuteurs($statut, $search, $page, $perPage);

    loadView("admin/auteur", compact(
        'auteurs','statut','search','page','totalPages','total','nbSignalementsNonTraites'
    ), "admin");
};

/* ── LECTEURS ── */
$lecteurs = function () use ($nbSignalementsNonTraites) {
    $statut  = trim($_GET['statut'] ?? '');
    $search  = trim($_GET['q']     ?? '');
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    if (!in_array($statut, ['Actif','Inactif'], true)) $statut = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postAction = $_POST['post_action'] ?? '';
        $lecteurId  = (int)($_POST['lecteur_id'] ?? 0);

        if ($postAction === 'activer'   && $lecteurId) updateStatutLecteur($lecteurId, 'Actif');
        if ($postAction === 'desactiver'&& $lecteurId) updateStatutLecteur($lecteurId, 'Inactif');
        if ($postAction === 'supprimer' && $lecteurId) deleteLecteurAdmin($lecteurId);

        header('Location: '.path('admin','lecteur',['statut'=>$statut,'q'=>$search,'page'=>$page]));
        exit();
    }

    $total      = countAllLecteurs($statut, $search);
    $totalPages = (int)ceil($total / $perPage);
    $page       = min($page, max(1, $totalPages));
    $lecteurs   = getAllLecteurs($statut, $search, $page, $perPage);

    loadView("admin/lecteur", compact(
        'lecteurs','statut','search','page','totalPages','total','nbSignalementsNonTraites'
    ), "admin");
};

/* ── SIGNALEMENTS ── */
$signalements = function () use ($nbSignalementsNonTraites) {
    $statut  = trim($_GET['statut'] ?? '');
    $search  = trim($_GET['q']     ?? '');
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    if (!in_array($statut, ['Non traiter','Traiter'], true)) $statut = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postAction   = $_POST['post_action']    ?? '';
        $signalementId = (int)($_POST['signalement_id'] ?? 0);

        if ($postAction === 'traiter'  && $signalementId) updateStatutSignalement($signalementId, 'Traiter');
        if ($postAction === 'ignorer'  && $signalementId) deleteSignalement($signalementId);
        if ($postAction === 'supprimer_commentaire') {
            $comId = (int)($_POST['comment_id'] ?? 0);
            if ($comId) deleteCommentaireAdmin($comId);
        }

        header('Location: '.path('admin','signalement',['statut'=>$statut,'q'=>$search,'page'=>$page]));
        exit();
    }

    $total        = countAllSignalements($statut, $search);
    $totalPages   = (int)ceil($total / $perPage);
    $page         = min($page, max(1, $totalPages));
    $signalements = getAllSignalements($statut, $search, $page, $perPage);
    $nbSignalementsNonTraites = getNbSignalementsNonTraites();

    loadView("admin/signalement", compact(
        'signalements','statut','search','page','totalPages','total','nbSignalementsNonTraites'
    ), "admin");
};

/* ── DÉCONNEXION ── */
$deconnexion = function () {
    unset($_SESSION['admin']);
    header('Location: ' . path('admin', 'login'));
    exit();
};

/* ── DISPATCH ── */
$actions = [
    'login'          => $login,
    'dashboard'      => $dashboard,
    'articles'       => $articles,
    'article_detail' => $article_detail,
    'auteurs'        => $auteurs,
    'lecteurs'       => $lecteurs,
    'signalements'   => $signalements,
    'deconnexion'    => $deconnexion,
];

$action = $_REQUEST['action'] ?? 'dashboard';
$GLOBALS['currentAction'] = $action;

if (array_key_exists($action, $actions)) {
    $actions[$action]();
} else {
    http_response_code(404);
    echo "Page introuvable";
    exit();
}