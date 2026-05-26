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
    if ($id <= 0) {
        echo "Article introuvable";
        exit();
    }
 
    /* ── Utilisateur connecté (session) ── */
    // Pour l'instant on simule : adapte selon ton système d'auth
    // session_start() doit être appelé avant (dans index.php ou helper.php)
    $sessionUser    = $_SESSION['user']      ?? null;  // tableau avec id, type ('auteur'|'lecteur')
    $currentAuteurId  = ($sessionUser && $sessionUser['type'] === 'auteur')  ? (int)$sessionUser['id'] : null;
    $currentLecteurId = ($sessionUser && $sessionUser['type'] === 'lecteur') ? (int)$sessionUser['id'] : null;
 
    /* ── Actions POST ── */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postAction = trim($_POST['post_action'] ?? '');
 
        // Ajouter un commentaire
        if ($postAction === 'add_comment') {
            $contenu = trim($_POST['contenu'] ?? '');
            if ($contenu !== '' && ($currentAuteurId || $currentLecteurId)) {
                addCommentaire($id, $contenu, $currentAuteurId, $currentLecteurId);
            }
            header("Location: " . path('lecteur', 'detail', ['id' => $id]) . "#commentsSection");
            exit();
        }
 
        // Modifier un commentaire
        if ($postAction === 'edit_comment') {
            $commentId = (int) ($_POST['comment_id'] ?? 0);
            $contenu   = trim($_POST['contenu'] ?? '');
            if ($commentId > 0 && $contenu !== '' && ($currentAuteurId || $currentLecteurId)) {
                updateCommentaire($commentId, $contenu, $currentAuteurId, $currentLecteurId);
            }
            header("Location: " . path('lecteur', 'detail', ['id' => $id]) . "#commentsSection");
            exit();
        }
 
        // Supprimer un commentaire
        if ($postAction === 'delete_comment') {
            $commentId = (int) ($_POST['comment_id'] ?? 0);
            if ($commentId > 0 && ($currentAuteurId || $currentLecteurId)) {
                deleteCommentaire($commentId, $currentAuteurId, $currentLecteurId);
            }
            header("Location: " . path('lecteur', 'detail', ['id' => $id]) . "#commentsSection");
            exit();
        }
 
        // Signaler un article
        if ($postAction === 'signal_article') {
            $libelle     = trim($_POST['raison']      ?? 'Autre');
            $description = trim($_POST['description'] ?? '');
            if ($libelle !== '') {
                addSignalement($libelle, $description, $id, null, $currentLecteurId);
            }
            header("Location: " . path('lecteur', 'detail', ['id' => $id]));
            exit();
        }
 
        // Signaler un commentaire
        if ($postAction === 'signal_comment') {
            $commentId   = (int) ($_POST['comment_id'] ?? 0);
            $libelle     = trim($_POST['raison']      ?? 'Autre');
            $description = trim($_POST['description'] ?? '');
            if ($commentId > 0 && $libelle !== '') {
                addSignalement($libelle, $description, null, $commentId, $currentLecteurId);
            }
            header("Location: " . path('lecteur', 'detail', ['id' => $id]) . "#commentsSection");
            exit();
        }
    }
 
    /* ── Données ── */
    $article = getArticleById($id);
    if (!$article) {
        echo "Article introuvable ou inactif.";
        exit();
    }
 
    $categories      = getCategoriesDetailByArticle($id);
    $categorieIds    = array_column($categories, 'id');
    $similaires      = getArticlesSimilaires($id, $categorieIds);
    $toutesCategories = getAllCategories();
    $commentaires    = getCommentairesByArticle($id);
    $nbCommentaires  = count($commentaires);
    $nbArticlesAuteur = countArticlesByAuteur((int) $article['auteur_id']);
 
    loadView("lecteur/detail", compact(
        'article',
        'categories',
        'similaires',
        'toutesCategories',
        'commentaires',
        'nbCommentaires',
        'nbArticlesAuteur',
        'currentAuteurId',
        'currentLecteurId'
    ));
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