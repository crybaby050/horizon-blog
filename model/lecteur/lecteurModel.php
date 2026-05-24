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
 * Récupère les 4 catégories principales.
 */
function getPrincipalCategorie(): array {
    $sql = "SELECT * FROM categorie LIMIT 4";
    return executeSelect($sql);
}

/**
 * Compte le nombre total d'articles selon les filtres appliqués.
 *
 * @param string $statut  Filtre par statut ('Actif', 'En attente', 'Invalide') ou '' pour tous
 * @param string $search  Recherche dans le libellé
 */
function countArticles(string $statut = '', string $search = ''): int {
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

    $sql = "SELECT COUNT(*) AS total FROM article a"
         . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

    return (int) executeSelect($sql, $params, true)['total'];
}

/**
 * Récupère une page d'articles avec filtres et pagination.
 *
 * @param string $statut     Filtre par statut ou '' pour tous
 * @param string $search     Recherche textuelle dans le libellé
 * @param int    $page       Numéro de page (commence à 1)
 * @param int    $perPage    Nombre d'articles par page
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
        $where[]          = "a.statut = :statut";
        $params[':statut'] = $statut;
    }
    if ($search !== '') {
        $where[]          = "a.libelle ILIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    $offset          = ($page - 1) * $perPage;
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