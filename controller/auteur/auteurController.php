<?php

require_once ROOT . "model/auteur/auteurModel.php";
/*var_dump($_SESSION['utilisateur']['type']);
var_dump($_SESSION['utilisateur']['type'] === 'auteur');
die();*/

$auteurId = null;
if (!empty($_SESSION['user']) && $_SESSION['user']['type'] === 'auteur') {
    $auteurId = $_SESSION['user']['id'];
}

if (!$auteurId) {
    header('Location: ' . path('lecteur', 'home'));
    exit();
}

/* ── DASHBOARD ── */
$dashboard = function () use ($auteurId) {
    $stats            = getStatsAuteur($auteurId);
    $nbArticlesAuteur = $stats['nb_articles'] ?? 0;
    $articlesRecents  = getArticlesRecentsAuteur($auteurId, 5);
    $chartData        = getChartVuesParMois($auteurId);
    loadView("auteur/dashboard", compact(
        'stats', 'nbArticlesAuteur', 'articlesRecents', 'chartData'
    ), "auteur");
};

/* ── LISTE ARTICLES ── */
$articles = function () use ($auteurId) {
    $statut  = trim($_GET['statut'] ?? '');
    $search  = trim($_GET['q']     ?? '');
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 9;

    $statutsValides = ['Actif', 'En attente', 'Invalide', 'Valide'];
    if (!in_array($statut, $statutsValides, true)) $statut = '';

    $total      = countArticlesAuteur($auteurId, $statut, $search);
    $totalPages = (int) ceil($total / $perPage);
    $page       = min($page, max(1, $totalPages));

    $articles = getArticlesAuteur($auteurId, $statut, $search, $page, $perPage);
    foreach ($articles as &$art) {
        $art['categories'] = getCategoriesArticle((int)$art['id']);
    }
    unset($art);

    $nbArticlesAuteur = $total;
    loadView("auteur/articles", compact(
        'articles', 'statut', 'search', 'page', 'totalPages', 'total', 'nbArticlesAuteur'
    ), "auteur");
};

/* ── AJOUT ARTICLE ── */
$ajout = function () use ($auteurId) {
    $categories       = getAllCategoriesSimple();
    $nbArticlesAuteur = countArticlesAuteur($auteurId);
    $errors           = [];
    $success          = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['post_action'] ?? '') === 'add_article') {
        $titre       = trim($_POST['titre']       ?? '');
        $description = trim($_POST['description'] ?? '');
        $contenu     = trim($_POST['contenu']     ?? '');
        $cats        = $_POST['categories']       ?? [];

        // Validation
        if (strlen($titre) < 5)
            $errors['titre'] = 'Le titre doit contenir au moins 5 caractères.';
        if (strlen($titre) > 255)
            $errors['titre'] = 'Le titre ne doit pas dépasser 255 caractères.';
        if (strlen($description) < 10)
            $errors['description'] = 'La description doit contenir au moins 10 caractères.';
        if (strlen($contenu) < 20)
            $errors['contenu'] = 'Le contenu doit contenir au moins 20 caractères.';
        if (empty($cats))
            $errors['categories'] = 'Sélectionnez au moins une catégorie.';
        if (count($cats) > 5)
            $errors['categories'] = 'Maximum 5 catégories.';

        // Images uploadées
        $images = [];
        if (!empty($_FILES['images']['name'][0])) {
            $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
            foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
                if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
                $mime = mime_content_type($tmp);
                if (!in_array($mime, $allowed)) {
                    $errors['images'] = 'Format image non supporté (jpg, png, webp, gif).';
                    break;
                }
                if ($_FILES['images']['size'][$i] > 5 * 1024 * 1024) {
                    $errors['images'] = 'Chaque image doit faire moins de 5 Mo.';
                    break;
                }
                // On simule un upload — en production, upload vers /public/uploads/
                // Ici on stocke juste le nom original + une URL Unsplash de démo
                $images[] = [
                    'url'     => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=1400&q=85',
                    'legende' => htmlspecialchars($_FILES['images']['name'][$i]),
                    'ordre'   => $i + 1,
                ];
            }
        }

        if (empty($errors)) {
            $articleId = insertArticle($auteurId, $titre, $description, $contenu);
            if ($articleId) {
                insertCategoriesArticle($articleId, $cats);
                if (!empty($images)) insertImagesArticle($articleId, $images);
                header('Location: ' . path('auteur', 'articles') . '&success=1');
                exit();
            } else {
                $errors['global'] = 'Une erreur est survenue lors de la création.';
            }
        }
    }

    loadView("auteur/ajout", compact(
        'categories', 'nbArticlesAuteur', 'errors', 'success'
    ), "auteur");
};

/* ── DETAIL ARTICLE (vue auteur) ── */
$detail = function () use ($auteurId) {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { header('Location: ' . path('auteur','articles')); exit(); }

    $article = getArticleAuteur($id, $auteurId);
    if (!$article) { header('Location: ' . path('auteur','articles')); exit(); }

    // Actions POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postAction = $_POST['post_action'] ?? '';

        // Supprimer commentaire
        if ($postAction === 'delete_comment') {
            $comId = (int)($_POST['comment_id'] ?? 0);
            if ($comId) deleteCommentaireAuteur($comId, $auteurId, $id);
            header('Location: ' . path('auteur','detail',['id'=>$id]) . '#commentsSection');
            exit();
        }
        // Signaler commentaire
        if ($postAction === 'signal_comment') {
            $comId  = (int)($_POST['comment_id'] ?? 0);
            $raison = trim($_POST['raison'] ?? '');
            $desc   = trim($_POST['description'] ?? '');
            if ($comId && $raison) signalerCommentaire($comId, $auteurId, 'auteur', $raison, $desc);
            header('Location: ' . path('auteur','detail',['id'=>$id]) . '#commentsSection');
            exit();
        }
        // Commenter son propre article
        if ($postAction === 'add_comment') {
            $contenu = trim($_POST['contenu'] ?? '');
            if (strlen($contenu) >= 2) addCommentaireAuteur($id, $auteurId, $contenu);
            header('Location: ' . path('auteur','detail',['id'=>$id]) . '#commentsSection');
            exit();
        }
    }

    $article['categories'] = getCategoriesArticle($id);
    $commentaires          = getCommentairesArticle($id);
    $nbCommentaires        = count($commentaires);
    $nbArticlesAuteur      = countArticlesAuteur($auteurId);

    loadView("auteur/detail", compact(
        'article','commentaires','nbCommentaires','nbArticlesAuteur'
    ), "auteur");
};

/* ── MODIFIER ARTICLE ── */
$modifier = function () use ($auteurId) {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) { header('Location: ' . path('auteur','articles')); exit(); }

    $article = getArticleAuteur($id, $auteurId);
    if (!$article) { header('Location: ' . path('auteur','articles')); exit(); }

    $categories            = getAllCategoriesSimple();
    $categoriesArticle     = getCategoriesArticle($id);
    $imagesArticle         = getImagesArticle($id);
    $nbArticlesAuteur      = countArticlesAuteur($auteurId);
    $errors                = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['post_action'] ?? '') === 'edit_article') {
        $titre       = trim($_POST['titre']       ?? '');
        $description = trim($_POST['description'] ?? '');
        $contenu     = trim($_POST['contenu']     ?? '');
        $cats        = $_POST['categories']       ?? [];

        if (strlen($titre) < 5)
            $errors['titre'] = 'Le titre doit contenir au moins 5 caractères.';
        if (strlen($titre) > 255)
            $errors['titre'] = 'Le titre ne doit pas dépasser 255 caractères.';
        if (strlen($description) < 10)
            $errors['description'] = 'La description doit contenir au moins 10 caractères.';
        if (strlen($contenu) < 20)
            $errors['contenu'] = 'Le contenu doit contenir au moins 20 caractères.';
        if (empty($cats))
            $errors['categories'] = 'Sélectionnez au moins une catégorie.';

        if (empty($errors)) {
            updateArticle($id, $auteurId, $titre, $description, $contenu);
            updateCategoriesArticle($id, $cats);

            // Nouvelles images
            if (!empty($_FILES['images']['name'][0])) {
                $allowed = ['image/jpeg','image/png','image/webp','image/gif'];
                $newImgs = [];
                $ordre   = count($imagesArticle) + 1;
                foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
                    if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
                    $mime = mime_content_type($tmp);
                    if (!in_array($mime, $allowed)) continue;
                    $newImgs[] = [
                        'url'     => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=1400&q=85',
                        'legende' => htmlspecialchars($_FILES['images']['name'][$i]),
                        'ordre'   => $ordre++,
                    ];
                }
                if (!empty($newImgs)) insertImagesArticle($id, $newImgs);
            }

            // Supprimer images cochées
            $imgSuppr = $_POST['suppr_images'] ?? [];
            foreach ($imgSuppr as $imgId) {
                deleteImageArticle((int)$imgId, $id);
            }

            header('Location: ' . path('auteur','detail',['id'=>$id]) . '&success=edit');
            exit();
        }
        // Re-fetch pour le formulaire
        $article['libelle']     = $titre;
        $article['description'] = $description;
        $article['contenu']     = $contenu;
        $categoriesArticle      = array_map(fn($c) => ['id' => $c], $cats);
    }

    loadView("auteur/modifier", compact(
        'article','categories','categoriesArticle','imagesArticle','nbArticlesAuteur','errors'
    ), "auteur");
};

/* ── SUPPRIMER ARTICLE ── */
$supprimer = function () use ($auteurId) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) deleteArticleAuteur($id, $auteurId);
    header('Location: ' . path('auteur','articles') . '&deleted=1');
    exit();
};

/* ── DÉCONNEXION ── */
$deconnexion = function () {
    session_destroy();
    header('Location: ' . path('lecteur','home'));
    exit();
};

/* ── DISPATCH ── */
$actions = [
    'dashboard'  => $dashboard,
    'articles'   => $articles,
    'ajout'      => $ajout,
    'detail'     => $detail,
    'modifier'   => $modifier,
    'supprimer'  => $supprimer,
    'deconnexion'=> $deconnexion,
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