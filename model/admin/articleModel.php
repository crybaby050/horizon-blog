<?php

/**
 * Compte les articles selon les filtres.
 */
function adminCountArticles(string $statut = '', string $search = '', int $auteur_id = 0): int {
    $where  = [];
    $params = [];

    if ($statut !== '') {
        $where[]          = "a.statut = :statut";
        $params[':statut'] = $statut;
    }
    if ($search !== '') {
        $where[]          = "a.libelle ILIKE :search";
        $params[':search'] = '%' . $search . '%';
    }
    if ($auteur_id > 0) {
        $where[]            = "a.auteur_id = :auteur_id";
        $params[':auteur_id'] = $auteur_id;
    }

    $sql = "SELECT COUNT(*) AS total FROM article a"
         . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

    return (int) executeSelect($sql, $params, true)['total'];
}

/**
 * Récupère les articles avec filtres, tri et pagination.
 */
function adminGetArticles(
    string $statut   = '',
    string $search   = '',
    int    $auteur_id = 0,
    int    $page     = 1,
    int    $perPage  = 15,
    string $tri      = 'date_creation',
    string $ordre    = 'DESC'
): array {
    $where  = [];
    $params = [];

    if ($statut !== '') {
        $where[]          = "a.statut = :statut";
        $params[':statut'] = $statut;
    }
    if ($search !== '') {
        $where[]          = "a.libelle ILIKE :search";
        $params[':search'] = '%' . $search . '%';
    }
    if ($auteur_id > 0) {
        $where[]            = "a.auteur_id = :auteur_id";
        $params[':auteur_id'] = $auteur_id;
    }

    // Colonnes de tri autorisées (whitelist)
    $trisValides  = ['date_creation', 'libelle', 'statut'];
    $ordresValides = ['ASC', 'DESC'];
    $tri   = in_array($tri, $trisValides, true)   ? $tri   : 'date_creation';
    $ordre = in_array($ordre, $ordresValides, true) ? $ordre : 'DESC';

    $offset          = ($page - 1) * $perPage;
    $params[':limit']  = $perPage;
    $params[':offset'] = $offset;

    $sql = "SELECT
                a.id,
                a.libelle,
                a.statut,
                a.date_creation,
                a.date_dernier_modification,
                au.id        AS auteur_id,
                au.prenom || ' ' || au.nom AS auteur,
                ai.url AS image_p,
                (SELECT COUNT(*) FROM commentaire c WHERE c.article_id = a.id) AS nb_commentaires
            FROM article a
            LEFT JOIN auteur au ON au.id = a.auteur_id
            LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1"
        . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '')
        . " ORDER BY a.$tri $ordre
            LIMIT :limit OFFSET :offset";

    return executeSelect($sql, $params);
}

/**
 * Récupère un article complet pour le détail admin.
 */
function adminGetArticleDetail(int $id): array|false {
    $sql = "SELECT
                a.id,
                a.libelle,
                a.description,
                a.contenu,
                a.statut,
                a.date_creation,
                a.date_dernier_modification,
                au.id        AS auteur_id,
                au.prenom || ' ' || au.nom AS auteur,
                au.email     AS auteur_email,
                ai.url       AS image_p
            FROM article a
            LEFT JOIN auteur au ON au.id = a.auteur_id
            LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
            WHERE a.id = :id";
    return executeSelect($sql, [':id' => $id], true);
}

/**
 * Récupère les commentaires d'un article pour l'admin.
 */
function adminGetCommentairesArticle(int $article_id): array {
    $sql = "SELECT
                c.id,
                c.contenue,
                c.date,
                c.statut,
                CASE
                    WHEN c.auteur_id IS NOT NULL THEN au.prenom || ' ' || au.nom
                    ELSE l.prenom || ' ' || l.nom
                END AS auteur_nom,
                CASE
                    WHEN c.auteur_id IS NOT NULL THEN 'auteur'
                    ELSE 'lecteur'
                END AS type_user
            FROM commentaire c
            LEFT JOIN auteur au ON au.id = c.auteur_id
            LEFT JOIN lecteur l  ON l.id  = c.lecteur_id
            WHERE c.article_id = :id
            ORDER BY c.date DESC";
    return executeSelect($sql, [':id' => $article_id]);
}

/**
 * Change le statut d'un article.
 */
function adminUpdateStatutArticle(int $id, string $statut): void {
    $sql = "UPDATE article SET statut = :statut, date_dernier_modification = NOW()
            WHERE id = :id";
    executeUpdate($sql, [':statut' => $statut, ':id' => $id]);
}

/**
 * Supprime définitivement un article (et ses dépendances).
 */
function adminDeleteArticle(int $id): void {
    executeUpdate("DELETE FROM signalement WHERE article_id = :id",   [':id' => $id]);
    executeUpdate("DELETE FROM commentaire WHERE article_id = :id",   [':id' => $id]);
    executeUpdate("DELETE FROM article_categorie WHERE article_id = :id", [':id' => $id]);
    executeUpdate("DELETE FROM article_image WHERE article_id = :id", [':id' => $id]);
    executeUpdate("DELETE FROM article WHERE id = :id",               [':id' => $id]);
}

/**
 * Supprime définitivement un commentaire.
 */
function adminDeleteCommentaire(int $id): void {
    executeUpdate("DELETE FROM signalement WHERE commentaire_id = :id", [':id' => $id]);
    executeUpdate("DELETE FROM commentaire WHERE id = :id",             [':id' => $id]);
}

/**
 * Liste tous les auteurs pour le filtre par auteur.
 */
function adminGetAuteursPourFiltre(): array {
    $sql = "SELECT id, prenom || ' ' || nom AS nom_complet FROM auteur ORDER BY nom";
    return executeSelect($sql);
}

/**
 * Soft delete : passe l'article en statut "Inactif".
 */
function adminSoftDeleteArticle(int $id): void {
    executeUpdate(
        "UPDATE article SET statut = 'Inactif', date_dernier_modification = NOW() WHERE id = :id",
        [':id' => $id]
    );
}

/**
 * Restaure un article depuis la corbeille (remet "En attente").
 */
function adminRestaurerArticle(int $id): void {
    executeUpdate(
        "UPDATE article SET statut = 'En attente', date_dernier_modification = NOW()
         WHERE id = :id AND statut = 'Inactif'",
        [':id' => $id]
    );
}

/**
 * Compte les articles dans la corbeille (admin = tous les auteurs).
 */
function adminCountCorbeille(string $search = ''): int {
    $where  = ["a.statut = 'Inactif'"];
    $params = [];
    if ($search !== '') { $where[] = "a.libelle ILIKE :search"; $params[':search'] = '%'.$search.'%'; }
    $sql = "SELECT COUNT(*) AS total FROM article a WHERE " . implode(' AND ', $where);
    return (int)(executeSelect($sql, $params, true)['total'] ?? 0);
}

/**
 * Liste paginée des articles dans la corbeille (admin = tous les auteurs).
 */
function adminGetCorbeille(string $search = '', int $page = 1, int $perPage = 10): array {
    $where  = ["a.statut = 'Inactif'"];
    $params = [];
    if ($search !== '') { $where[] = "a.libelle ILIKE :search"; $params[':search'] = '%'.$search.'%'; }
    $params[':limit']  = $perPage;
    $params[':offset'] = ($page - 1) * $perPage;
    $sql = "SELECT a.id, a.libelle, a.statut, a.date_creation, a.date_dernier_modification,
                   au.prenom || ' ' || au.nom AS auteur,
                   ai.url AS image_p
            FROM article a
            JOIN auteur au ON au.id = a.auteur_id
            LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
            WHERE " . implode(' AND ', $where) . "
            ORDER BY a.date_dernier_modification DESC
            LIMIT :limit OFFSET :offset";
    return executeSelect($sql, $params);
}