<?php

/**
 * Statistiques globales pour le dashboard.
 */
function getStatsGlobales(): array {
    return [
        'articles'      => countTable('article'),
        'auteurs'       => countTable('auteur'),
        'lecteurs'      => countTable('lecteur'),
        'commentaires'  => countTable('commentaire'),
        'signalements'  => countTable('signalement'),
    ];
}

/**
 * Compte les articles par statut.
 */
function getStatsArticlesParStatut(): array {
    $sql = "SELECT statut, COUNT(*) AS total FROM article GROUP BY statut";
    $rows = executeSelect($sql);
    $result = ['Actif' => 0, 'En attente' => 0, 'Invalide' => 0, 'Inactif' => 0];
    foreach ($rows as $row) {
        $result[$row['statut']] = (int) $row['total'];
    }
    return $result;
}

/**
 * Nombre d'articles en attente de validation.
 */
function countArticlesEnAttente(): int {
    $sql = "SELECT COUNT(*) AS total FROM article WHERE statut = 'En attente'";
    return (int) executeSelect($sql, [], true)['total'];
}

/**
 * Nombre de demandes auteur en attente.
 */
function countDemandesEnAttente(): int {
    $sql = "SELECT COUNT(*) AS total FROM demande_auteur WHERE statut = 'En attente'";
    return (int) executeSelect($sql, [], true)['total'];
}

/**
 * Nombre de signalements non traités.
 */
function countSignalementsNonTraites(): int {
    $sql = "SELECT COUNT(*) AS total FROM signalement WHERE statut = 'Non traiter'";
    return (int) executeSelect($sql, [], true)['total'];
}

/**
 * Nombre d'articles publiés par mois sur les 12 derniers mois.
 * Retourne un tableau [['mois' => 'Janv. 2024', 'total' => 5], ...]
 */
function getArticlesParMois(): array {
    $sql = "SELECT
                TO_CHAR(date_creation, 'YYYY-MM') AS mois_key,
                TO_CHAR(date_creation, 'Mon YYYY') AS mois,
                COUNT(*) AS total
            FROM article
            WHERE date_creation >= NOW() - INTERVAL '12 months'
              AND statut = 'Actif'
            GROUP BY mois_key, mois
            ORDER BY mois_key ASC";
    return executeSelect($sql);
}

/**
 * Les 5 derniers articles soumis.
 */
function getDerniersArticles(int $limit = 5): array {
    $sql = "SELECT
                a.id,
                a.libelle,
                a.statut,
                a.date_creation,
                au.prenom || ' ' || au.nom AS auteur
            FROM article a
            LEFT JOIN auteur au ON au.id = a.auteur_id
            ORDER BY a.date_creation DESC
            LIMIT :limit";
    return executeSelect($sql, [':limit' => $limit]);
}

/**
 * Les 5 derniers signalements.
 */
function getDerniersSignalements(int $limit = 5): array {
    $sql = "SELECT
                s.id,
                s.libelle,
                s.statut,
                s.date_creation,
                s.article_id,
                s.commentaire_id
            FROM signalement s
            ORDER BY s.date_creation DESC
            LIMIT :limit";
    return executeSelect($sql, [':limit' => $limit]);
};
