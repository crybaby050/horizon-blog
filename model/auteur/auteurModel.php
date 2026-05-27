<?php

/**
 * Statistiques globales de l'auteur.
 */
function getStatsAuteur(int $auteurId): array {
    $sql = "SELECT
        COUNT(a.id)                                      AS nb_articles,
        (SELECT COUNT(*) FROM commentaire c
         JOIN article a2 ON a2.id = c.article_id
         WHERE a2.auteur_id = :id1)                      AS total_commentaires,
        (SELECT COUNT(*) FROM article a3
         WHERE a3.auteur_id = :id2 AND a3.statut = 'Actif') AS articles_actifs
    FROM article a
    WHERE a.auteur_id = :id3";
    return executeSelect($sql, [':id1'=>$auteurId,':id2'=>$auteurId,':id3'=>$auteurId], true) ?: [];
}

/**
 * 5 articles les plus récents de l'auteur.
 */
function getArticlesRecentsAuteur(int $auteurId, int $limit = 5): array {
    $sql = "SELECT a.id, a.libelle, a.statut, a.date_creation,
                   ai.url AS image_p
            FROM article a
            LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
            WHERE a.auteur_id = :id
            ORDER BY a.date_creation DESC
            LIMIT :limit";
    return executeSelect($sql, [':id'=>$auteurId, ':limit'=>$limit]);
}

/**
 * Données pour le graphique : vues par mois (12 derniers mois).
 */
function getChartVuesParMois(int $auteurId): array {
    $sql = "SELECT
                TO_CHAR(date_trunc('month', date_creation), 'Mon YYYY') AS mois,
                TO_CHAR(date_trunc('month', date_creation), 'YYYY-MM')  AS mois_sort,
                COUNT(id) AS nb_articles
            FROM article
            WHERE auteur_id = :id
              AND date_creation >= NOW() - INTERVAL '12 months'
            GROUP BY date_trunc('month', date_creation)
            ORDER BY mois_sort ASC";
    return executeSelect($sql, [':id' => $auteurId]);
}

/**
 * Compte les articles de l'auteur avec filtres optionnels.
 */
function countArticlesAuteur(int $auteurId, string $statut = '', string $search = ''): int {
    $where  = ["a.auteur_id = :id"];
    $params = [':id' => $auteurId];
    if ($statut !== '') { $where[] = "a.statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "a.libelle ILIKE :search"; $params[':search'] = '%'.$search.'%'; }
    $sql = "SELECT COUNT(*) AS total FROM article a WHERE " . implode(' AND ', $where);
    return (int)(executeSelect($sql, $params, true)['total'] ?? 0);
}

/**
 * Liste paginée des articles de l'auteur.
 */
function getArticlesAuteur(int $auteurId, string $statut = '', string $search = '', int $page = 1, int $perPage = 9): array {
    $where  = ["a.auteur_id = :id"];
    $params = [':id' => $auteurId];
    if ($statut !== '') { $where[] = "a.statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "a.libelle ILIKE :search"; $params[':search'] = '%'.$search.'%'; }
    $params[':limit']  = $perPage;
    $params[':offset'] = ($page - 1) * $perPage;
    $sql = "SELECT a.id, a.libelle, a.description, a.statut, a.date_creation,
                   ai.url AS image_p
            FROM article a
            LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
            WHERE " . implode(' AND ', $where) . "
            ORDER BY a.date_creation DESC
            LIMIT :limit OFFSET :offset";
    return executeSelect($sql, $params);
}

/**
 * Récupère un article appartenant à l'auteur.
 */
function getArticleAuteur(int $articleId, int $auteurId): array|false {
    $sql = "SELECT a.id, a.libelle, a.description, a.contenu, a.statut,
               a.date_creation, a.date_dernier_modification,
               au.prenom || ' ' || au.nom AS auteur,
               ai.url AS image_p
        FROM article a
        JOIN auteur au ON au.id = a.auteur_id
        LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
        WHERE a.id = :id AND a.auteur_id = :auteur_id";
    $res = executeSelect($sql, [':id'=>$articleId, ':auteur_id'=>$auteurId], true);
    return $res ?: false;
}

/**
 * Catégories d'un article.
 */
function getCategoriesArticle(int $articleId): array {
    $sql = "SELECT c.id, c.libelle, c.icone
            FROM categorie c
            JOIN article_categorie ac ON ac.categorie_id = c.id
            WHERE ac.article_id = :id";
    return executeSelect($sql, [':id' => $articleId]);
}

/**
 * Images d'un article.
 */
function getImagesArticle(int $articleId): array {
    $sql = "SELECT id, url, legende, ordre
            FROM article_image
            WHERE article_id = :id
            ORDER BY ordre ASC";
    return executeSelect($sql, [':id' => $articleId]);
}

/**
 * Toutes les catégories (pour les selects).
 */
function getAllCategoriesSimple(): array {
    $sql = "SELECT id, libelle, icone FROM categorie ORDER BY libelle";
    return executeSelect($sql);
}

/**
 * Insérer un article — statut par défaut "En attente".
 */
function insertArticle(int $auteurId, string $titre, string $description, string $contenu): int|false {
    $sql = "INSERT INTO article (libelle, description, contenu, statut, auteur_id, date_creation, date_dernier_modification)
        VALUES (:libelle, :description, :contenu, 'En attente', :auteur_id, NOW(), NOW())
        RETURNING id";
    $res = executeSelect($sql, [
        ':libelle'     => $titre,
        ':description' => $description,
        ':contenu'     => $contenu,
        ':auteur_id'   => $auteurId,
    ], true);
    return $res['id'] ?? false;
}

/**
 * Insérer les catégories d'un article.
 */
function insertCategoriesArticle(int $articleId, array $categorieIds): void {
    // Supprimer les anciennes
    executeUpdate("DELETE FROM article_categorie WHERE article_id = :id", [':id' => $articleId]);
    foreach ($categorieIds as $catId) {
        $catId = (int)$catId;
        if ($catId > 0) {
            executeUpdate(
                "INSERT INTO article_categorie (article_id, categorie_id) VALUES (:a, :c)",
                [':a' => $articleId, ':c' => $catId]
            );
        }
    }
}

/**
 * Insérer les images d'un article.
 */
function insertImagesArticle(int $articleId, array $images): void {
    foreach ($images as $img) {
        executeUpdate(
            "INSERT INTO article_image (article_id, url, legende, ordre) VALUES (:a, :url, :leg, :ord)",
            [':a'=>$articleId, ':url'=>$img['url'], ':leg'=>$img['legende'], ':ord'=>$img['ordre']]
        );
    }
}

/**
 * Mettre à jour un article.
 */
function updateArticle(int $articleId, int $auteurId, string $titre, string $description, string $contenu): void {
    $sql = "UPDATE article
        SET libelle = :libelle, description = :description, contenu = :contenu,
            statut = 'En attente', date_dernier_modification = NOW()
        WHERE id = :id AND auteur_id = :auteur_id";
    executeUpdate($sql, [
        ':libelle'     => $titre,
        ':description' => $description,
        ':contenu'     => $contenu,
        ':id'          => $articleId,
        ':auteur_id'   => $auteurId,
    ]);
}

/**
 * Mettre à jour les catégories d'un article.
 */
function updateCategoriesArticle(int $articleId, array $categorieIds): void {
    insertCategoriesArticle($articleId, $categorieIds);
}

/**
 * Supprimer une image d'un article.
 */
function deleteImageArticle(int $imageId, int $articleId): void {
    executeUpdate(
        "DELETE FROM article_image WHERE id = :id AND article_id = :art",
        [':id' => $imageId, ':art' => $articleId]
    );
}

/**
 * Supprimer un article (et ses dépendances).
 */
function deleteArticleAuteur(int $articleId, int $auteurId): void {
    executeUpdate("DELETE FROM article_image WHERE article_id = :id",     [':id' => $articleId]);
    executeUpdate("DELETE FROM article_categorie WHERE article_id = :id", [':id' => $articleId]);
    executeUpdate("DELETE FROM commentaire WHERE article_id = :id",       [':id' => $articleId]);
    executeUpdate(
        "DELETE FROM article WHERE id = :id AND auteur_id = :auteur_id",
        [':id' => $articleId, ':auteur_id' => $auteurId]
    );
}

/**
 * Commentaires d'un article.
 */
function getCommentairesArticle(int $articleId): array {
    $sql = "SELECT
        c.id,
        c.contenue,
        c.date,
        COALESCE(au.prenom || ' ' || au.nom, le.prenom || ' ' || le.nom, 'Inconnu') AS nom_complet,
        CASE WHEN c.auteur_id IS NOT NULL THEN 'auteur' ELSE 'lecteur' END AS type_user,
        c.auteur_id,
        c.lecteur_id
    FROM commentaire c
    LEFT JOIN auteur  au ON au.id = c.auteur_id
    LEFT JOIN lecteur le ON le.id = c.lecteur_id
    WHERE c.article_id = :id
    ORDER BY c.date ASC";
    return executeSelect($sql, [':id' => $articleId]);
}

/**
 * Ajouter un commentaire (auteur sur son article).
 */
function addCommentaireAuteur(int $articleId, int $auteurId, string $contenu): void {
    $sql = "INSERT INTO commentaire (article_id, auteur_id, contenue, date)
            VALUES (:article_id, :auteur_id, :contenu, NOW())";
    executeUpdate($sql, [
        ':article_id' => $articleId,
        ':auteur_id'  => $auteurId,
        ':contenu'    => $contenu,
    ]);
}

/**
 * Supprimer un commentaire (auteur = propriétaire de l'article).
 */
function deleteCommentaireAuteur(int $commentaireId, int $auteurId, int $articleId): void {
    // Vérifie que l'article appartient bien à cet auteur
    $sql = "DELETE FROM commentaire
            WHERE id = :com_id
              AND article_id = :art_id
              AND article_id IN (SELECT id FROM article WHERE auteur_id = :auteur_id)";
    executeUpdate($sql, [
        ':com_id'    => $commentaireId,
        ':art_id'    => $articleId,
        ':auteur_id' => $auteurId,
    ]);
}

/**
 * Signaler un commentaire.
 */
function signalerCommentaire(int $commentaireId, int $signaleurId, string $typeSignaleur, string $raison, string $description): void {
    // Table signalement si elle existe — sinon on ignore silencieusement
    try {
        $col = $typeSignaleur === 'auteur' ? 'auteur_id' : 'lecteur_id';
        $sql = "INSERT INTO signalement (commentaire_id, $col, raison, description, date)
                VALUES (:com_id, :signaleur_id, :raison, :description, NOW())";
        executeUpdate($sql, [
            ':com_id'       => $commentaireId,
            ':signaleur_id' => $signaleurId,
            ':raison'       => $raison,
            ':description'  => $description,
        ]);
    } catch (\Throwable $e) {
        // Silently ignore if table doesn't exist
    }
}