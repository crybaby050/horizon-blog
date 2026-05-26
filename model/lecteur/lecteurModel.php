<?php

/**
 * Récupère les 5 derniers articles actifs pour le hero/slider de la home.
 */
function getArticleVisuel(): array {
    $sql = "SELECT
        a.id,
        a.libelle,
        a.description,
        a.statut,
        a.date_creation,
        ai.url  AS image_p,
        au.prenom || ' ' || au.nom AS auteur
    FROM article a
    LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
    LEFT JOIN auteur au ON au.id = a.auteur_id
    WHERE a.statut = 'Actif'
    ORDER BY a.date_creation DESC
    LIMIT 5";
    return executeSelect($sql);
}

/**
 * Récupère les catégories d'un article donné.
 */
function getCategoriesByArticle(int $article_id): array {
    $sql = "SELECT c.libelle
            FROM categorie c
            JOIN article_categorie ac ON ac.categorie_id = c.id
            WHERE ac.article_id = :id";
    return executeSelect($sql, [':id' => $article_id]);
}

/**
 * Récupère les 4 premières catégories pour la home (avec image et icone).
 */
function getPrincipalCategorie(): array {
    $sql = "SELECT id, libelle, image, icone,
            (SELECT COUNT(*) FROM article_categorie ac WHERE ac.categorie_id = c.id) AS nb_articles
            FROM categorie c
            ORDER BY id
            LIMIT 4";
    return executeSelect($sql);
}

/**
 * Récupère toutes les catégories pour la page catégorie (avec image et icone).
 */
function getAllCategories(): array {
    $sql = "SELECT id, libelle, image, icone,
            (SELECT COUNT(*) FROM article_categorie ac WHERE ac.categorie_id = c.id) AS nb_articles
            FROM categorie c
            ORDER BY id";
    return executeSelect($sql);
}

/**
 * Compte le nombre total d'articles selon les filtres appliqués.
 */
function countArticles(string $statut = '', string $search = ''): int {
    $where  = [];
    $params = [];

    if ($statut !== '') {
        $where[]           = "a.statut = :statut";
        $params[':statut'] = $statut;
    }
    if ($search !== '') {
        $where[]           = "a.libelle ILIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    $sql = "SELECT COUNT(*) AS total FROM article a"
         . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

    return (int) executeSelect($sql, $params, true)['total'];
}

/**
 * Récupère une page d'articles avec filtres et pagination.
 */
function getArticlesFiltres(
    string $statut  = '',
    string $search  = '',
    int    $page    = 1,
    int    $perPage = 9
): array {
    $where  = [];
    $params = [];

    if ($statut !== '') {
        $where[]           = "a.statut = :statut";
        $params[':statut'] = $statut;
    }
    if ($search !== '') {
        $where[]           = "a.libelle ILIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    $offset            = ($page - 1) * $perPage;
    $params[':limit']  = $perPage;
    $params[':offset'] = $offset;

    $sql = "SELECT
        a.id,
        a.libelle,
        a.description,
        a.statut,
        a.date_creation,
        ai.url  AS image_p,
        au.prenom || ' ' || au.nom AS auteur
    FROM article a
    LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
    LEFT JOIN auteur au ON au.id = a.auteur_id"
    . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '')
    . " ORDER BY a.date_creation DESC
       LIMIT :limit OFFSET :offset";

    return executeSelect($sql, $params);
}



/**
 * Récupère un article complet par son id.
 */
function getArticleById(int $id): array|false {
    $sql = "SELECT
        a.id,
        a.libelle,
        a.description,
        a.contenu,
        a.statut,
        a.date_creation,
        ai.url AS image_p,
        au.id        AS auteur_id,
        au.prenom || ' ' || au.nom AS auteur,
        au.bio       AS auteur_bio
    FROM article a
    LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
    LEFT JOIN auteur au ON au.id = a.auteur_id
    WHERE a.id = :id AND a.statut = 'Actif'";
    return executeSelect($sql, [':id' => $id], true);
}
 
/**
 * Récupère les catégories d'un article (avec id + libelle).
 */
function getCategoriesDetailByArticle(int $article_id): array {
    $sql = "SELECT c.id, c.libelle
            FROM categorie c
            JOIN article_categorie ac ON ac.categorie_id = c.id
            WHERE ac.article_id = :id";
    return executeSelect($sql, [':id' => $article_id]);
}
 
/**
 * Compte les articles publiés d'un auteur.
 */
function countArticlesByAuteur(int $auteur_id): int {
    $sql = "SELECT COUNT(*) AS total FROM article
            WHERE auteur_id = :id AND statut = 'Actif'";
    return (int) executeSelect($sql, [':id' => $auteur_id], true)['total'];
}
 
/**
 * Compte le total de vues des articles d'un auteur.
 */
function countVuesByAuteur(int $auteur_id): int {
    // Si tu n'as pas de colonne vues, retourne 0
    return 0;
}
 
/**
 * Récupère 3 articles similaires (même catégorie, hors article courant).
 */
function getArticlesSimilaires(int $article_id, array $categorie_ids, int $limit = 3): array {
    if (empty($categorie_ids)) return [];
 
    $placeholders = implode(',', array_fill(0, count($categorie_ids), '?'));
    $params = $categorie_ids;
    $params[] = $article_id;
    $params[] = $limit;
 
    $sql = "SELECT DISTINCT
        a.id,
        a.libelle,
        a.date_creation,
        ai.url AS image_p
    FROM article a
    JOIN article_categorie ac ON ac.article_id = a.id
    LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
    WHERE ac.categorie_id IN ($placeholders)
      AND a.id != ?
      AND a.statut = 'Actif'
    ORDER BY a.date_creation DESC
    LIMIT ?";
    return executeSelect($sql, $params);
}
 
/**
 * Récupère toutes les catégories (pour la sidebar).
 */
/*function getAllCategories(): array {
    $sql = "SELECT id, libelle FROM categorie ORDER BY libelle";
    return executeSelect($sql);
}*/ 
 
/**
 * Récupère les commentaires actifs d'un article avec infos auteur/lecteur.
 */
function getCommentairesByArticle(int $article_id): array {
    $sql = "SELECT
        c.id,
        c.contenue,
        c.date,
        c.statut,
        c.auteur_id,
        c.lecteur_id,
        CASE
            WHEN c.auteur_id IS NOT NULL THEN au.prenom || ' ' || au.nom
            ELSE l.prenom || ' ' || l.nom
        END AS nom_complet,
        CASE
            WHEN c.auteur_id IS NOT NULL THEN 'auteur'
            ELSE 'lecteur'
        END AS type_user
    FROM commentaire c
    LEFT JOIN auteur au  ON au.id = c.auteur_id
    LEFT JOIN lecteur l  ON l.id  = c.lecteur_id
    WHERE c.article_id = :id AND c.statut = 'Actif'
    ORDER BY c.date ASC";
    return executeSelect($sql, [':id' => $article_id]);
}
 
/**
 * Ajoute un commentaire.
 */
function addCommentaire(int $article_id, string $contenue, ?int $auteur_id, ?int $lecteur_id): void {
    $sql = "INSERT INTO commentaire (contenue, date, statut, article_id, auteur_id, lecteur_id)
            VALUES (:contenue, NOW(), 'Actif', :article_id, :auteur_id, :lecteur_id)";
    executeUpdate($sql, [
        ':contenue'    => $contenue,
        ':article_id' => $article_id,
        ':auteur_id'  => $auteur_id,
        ':lecteur_id' => $lecteur_id,
    ]);
}
 
/**
 * Modifie un commentaire (seulement si il appartient à l'utilisateur).
 */
function updateCommentaire(int $comment_id, string $contenue, ?int $auteur_id, ?int $lecteur_id): void {
    if ($auteur_id !== null) {
        $sql = "UPDATE commentaire SET contenue = :contenue
                WHERE id = :id AND auteur_id = :user_id";
        $params = [':contenue' => $contenue, ':id' => $comment_id, ':user_id' => $auteur_id];
    } else {
        $sql = "UPDATE commentaire SET contenue = :contenue
                WHERE id = :id AND lecteur_id = :user_id";
        $params = [':contenue' => $contenue, ':id' => $comment_id, ':user_id' => $lecteur_id];
    }
    executeUpdate($sql, $params);
}
 
/**
 * Supprime un commentaire (seulement si il appartient à l'utilisateur).
 */
function deleteCommentaire(int $comment_id, ?int $auteur_id, ?int $lecteur_id): void {
    if ($auteur_id !== null) {
        $sql = "DELETE FROM commentaire WHERE id = :id AND auteur_id = :user_id";
        $params = [':id' => $comment_id, ':user_id' => $auteur_id];
    } else {
        $sql = "DELETE FROM commentaire WHERE id = :id AND lecteur_id = :user_id";
        $params = [':id' => $comment_id, ':user_id' => $lecteur_id];
    }
    executeUpdate($sql, $params);
}
 
/**
 * Ajoute un signalement (article ou commentaire).
 */
function addSignalement(
    string  $libelle,
    string  $description,
    ?int    $article_id,
    ?int    $commentaire_id,
    ?int    $lecteur_id
): void {
    $sql = "INSERT INTO signalement
                (libelle, description, date_creation, statut, article_id, commentaire_id, lecteur_id)
            VALUES
                (:libelle, :description, NOW(), 'Non traiter', :article_id, :commentaire_id, :lecteur_id)";
    executeUpdate($sql, [
        ':libelle'        => $libelle,
        ':description'    => $description,
        ':article_id'     => $article_id,
        ':commentaire_id' => $commentaire_id,
        ':lecteur_id'     => $lecteur_id,
    ]);
}
 