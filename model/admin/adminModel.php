<?php

/* ════════ AUTH ════════ */

function loginAdmin(string $email, string $mdp): array|false {
    $sql = "SELECT id, prenom, nom, email, mot_de_passe
            FROM admin WHERE email = :email";
    $admin = executeSelect($sql, [':email' => $email], true);
    if (!$admin) return false;
    // Comparaison directe (sans hash) — à sécuriser en production
    if ($mdp !== $admin['mot_de_passe']) return false;
    return $admin;
}

/* ════════ STATS GLOBALES ════════ */

function getStatsGlobales(): array {
    $sql = "SELECT
        (SELECT COUNT(*) FROM article)                                AS nb_articles,
        (SELECT COUNT(*) FROM article WHERE statut = 'Actif')        AS articles_actifs,
        (SELECT COUNT(*) FROM article WHERE statut = 'En attente')   AS articles_attente,
        (SELECT COUNT(*) FROM auteur)                                AS nb_auteurs,
        (SELECT COUNT(*) FROM auteur WHERE statut = 'Actif')         AS auteurs_actifs,
        (SELECT COUNT(*) FROM lecteur)                               AS nb_lecteurs,
        (SELECT COUNT(*) FROM lecteur WHERE statut = 'Actif')        AS lecteurs_actifs,
        (SELECT COUNT(*) FROM commentaire)                           AS nb_commentaires,
        (SELECT COUNT(*) FROM signalement)                           AS nb_signalements,
        (SELECT COUNT(*) FROM signalement WHERE statut = 'Non traiter') AS signalements_attente";
    return executeSelect($sql, [], true) ?: [];
}

function getChartArticlesParMois(): array {
    $sql = "SELECT
                TO_CHAR(date_trunc('month', date_creation), 'Mon YYYY') AS mois,
                TO_CHAR(date_trunc('month', date_creation), 'YYYY-MM')  AS mois_sort,
                COUNT(*) AS nb_articles
            FROM article
            WHERE date_creation >= NOW() - INTERVAL '12 months'
            GROUP BY date_trunc('month', date_creation)
            ORDER BY mois_sort ASC";
    return executeSelect($sql);
}

function getDerniersArticles(int $limit = 6): array {
    $sql = "SELECT a.id, a.libelle, a.statut, a.date_creation,
                   au.prenom || ' ' || au.nom AS auteur,
                   ai.url AS image_p
            FROM article a
            JOIN auteur au ON au.id = a.auteur_id
            LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
            ORDER BY a.date_creation DESC
            LIMIT :limit";
    return executeSelect($sql, [':limit' => $limit]);
}

function getSignalementsRecents(int $limit = 5): array {
    $sql = "SELECT s.id, s.libelle, s.statut, s.date_creation,
                   s.article_id, s.commentaire_id,
                   COALESCE(au.prenom || ' ' || au.nom,
                            le.prenom || ' ' || le.nom, 'Inconnu') AS signaleur
            FROM signalement s
            LEFT JOIN auteur  au ON au.id = s.auteur_id
            LEFT JOIN lecteur le ON le.id = s.lecteur_id
            ORDER BY s.date_creation DESC
            LIMIT :limit";
    return executeSelect($sql, [':limit' => $limit]);
}

/* ════════ ARTICLES ════════ */

function countAllArticles(string $statut = '', string $search = ''): int {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "a.statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "a.libelle ILIKE :s"; $params[':s'] = '%'.$search.'%'; }
    $sql = "SELECT COUNT(*) AS total FROM article a"
         . (count($where) ? ' WHERE '.implode(' AND ',$where) : '');
    return (int)(executeSelect($sql, $params, true)['total'] ?? 0);
}

function getAllArticles(string $statut = '', string $search = '', int $page = 1, int $perPage = 10): array {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "a.statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "a.libelle ILIKE :s"; $params[':s'] = '%'.$search.'%'; }
    $params[':limit']  = $perPage;
    $params[':offset'] = ($page - 1) * $perPage;
    $sql = "SELECT a.id, a.libelle, a.statut, a.date_creation,
                   au.prenom || ' ' || au.nom AS auteur,
                   ai.url AS image_p
            FROM article a
            JOIN auteur au ON au.id = a.auteur_id
            LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1"
          . (count($where) ? ' WHERE '.implode(' AND ',$where) : '')
          . " ORDER BY a.date_creation DESC LIMIT :limit OFFSET :offset";
    return executeSelect($sql, $params);
}

function getArticleAdmin(int $id): array|false {
    $sql = "SELECT a.id, a.libelle, a.description, a.contenu, a.statut,
                   a.date_creation, a.date_dernier_modification,
                   au.prenom || ' ' || au.nom AS auteur, au.id AS auteur_id,
                   ai.url AS image_p
            FROM article a
            JOIN auteur au ON au.id = a.auteur_id
            LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
            WHERE a.id = :id";
    return executeSelect($sql, [':id' => $id], true) ?: false;
}

function updateStatutArticle(int $id, string $statut): void {
    executeUpdate("UPDATE article SET statut = :statut WHERE id = :id",
        [':statut' => $statut, ':id' => $id]);
}

function deleteArticleAdmin(int $id): void {
    executeUpdate("DELETE FROM article_image    WHERE article_id = :id", [':id'=>$id]);
    executeUpdate("DELETE FROM article_categorie WHERE article_id = :id", [':id'=>$id]);
    executeUpdate("DELETE FROM commentaire       WHERE article_id = :id", [':id'=>$id]);
    executeUpdate("DELETE FROM signalement       WHERE article_id = :id", [':id'=>$id]);
    executeUpdate("DELETE FROM article           WHERE id = :id",         [':id'=>$id]);
}

function getCommentairesArticleAdmin(int $articleId): array {
    $sql = "SELECT c.id, c.contenue, c.date,
                   COALESCE(au.prenom||' '||au.nom, le.prenom||' '||le.nom,'Inconnu') AS nom_complet,
                   CASE WHEN c.auteur_id IS NOT NULL THEN 'auteur' ELSE 'lecteur' END AS type_user,
                   c.auteur_id, c.lecteur_id
            FROM commentaire c
            LEFT JOIN auteur  au ON au.id = c.auteur_id
            LEFT JOIN lecteur le ON le.id = c.lecteur_id
            WHERE c.article_id = :id
            ORDER BY c.date ASC";
    return executeSelect($sql, [':id' => $articleId]);
}

function deleteCommentaireAdmin(int $id): void {
    executeUpdate("DELETE FROM signalement WHERE commentaire_id = :id", [':id'=>$id]);
    executeUpdate("DELETE FROM commentaire  WHERE id = :id",            [':id'=>$id]);
}

function getCategoriesArticleAdmin(int $id): array {
    $sql = "SELECT c.libelle FROM categorie c
            JOIN article_categorie ac ON ac.categorie_id = c.id
            WHERE ac.article_id = :id";
    return executeSelect($sql, [':id' => $id]);
}

/* ════════ AUTEURS ════════ */

function countAllAuteurs(string $statut = '', string $search = ''): int {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "(nom ILIKE :s OR prenom ILIKE :s OR email ILIKE :s)"; $params[':s'] = '%'.$search.'%'; }
    $sql = "SELECT COUNT(*) AS total FROM auteur"
         . (count($where) ? ' WHERE '.implode(' AND ',$where) : '');
    return (int)(executeSelect($sql, $params, true)['total'] ?? 0);
}

function getAllAuteurs(string $statut = '', string $search = '', int $page = 1, int $perPage = 10): array {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "(nom ILIKE :s OR prenom ILIKE :s OR email ILIKE :s)"; $params[':s'] = '%'.$search.'%'; }
    $params[':limit']  = $perPage;
    $params[':offset'] = ($page - 1) * $perPage;
    $sql = "SELECT a.id, a.prenom, a.nom, a.email, a.statut, a.date_inscription,
                   (SELECT COUNT(*) FROM article ar WHERE ar.auteur_id = a.id) AS nb_articles
            FROM auteur a"
          . (count($where) ? ' WHERE '.implode(' AND ',$where) : '')
          . " ORDER BY a.date_inscription DESC LIMIT :limit OFFSET :offset";
    return executeSelect($sql, $params);
}

function updateStatutAuteur(int $id, string $statut): void {
    executeUpdate("UPDATE auteur SET statut = :statut WHERE id = :id",
        [':statut' => $statut, ':id' => $id]);
}

function deleteAuteurAdmin(int $id): void {
    // Récupère ses articles pour les supprimer en cascade
    $articles = executeSelect("SELECT id FROM article WHERE auteur_id = :id", [':id'=>$id]);
    foreach ($articles as $art) deleteArticleAdmin($art['id']);
    executeUpdate("DELETE FROM auteur WHERE id = :id", [':id'=>$id]);
}

/* ════════ LECTEURS ════════ */

function countAllLecteurs(string $statut = '', string $search = ''): int {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "(nom ILIKE :s OR prenom ILIKE :s OR email ILIKE :s)"; $params[':s'] = '%'.$search.'%'; }
    $sql = "SELECT COUNT(*) AS total FROM lecteur"
         . (count($where) ? ' WHERE '.implode(' AND ',$where) : '');
    return (int)(executeSelect($sql, $params, true)['total'] ?? 0);
}

function getAllLecteurs(string $statut = '', string $search = '', int $page = 1, int $perPage = 10): array {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "(nom ILIKE :s OR prenom ILIKE :s OR email ILIKE :s)"; $params[':s'] = '%'.$search.'%'; }
    $params[':limit']  = $perPage;
    $params[':offset'] = ($page - 1) * $perPage;
    $sql = "SELECT l.id, l.prenom, l.nom, l.email, l.statut,
                   (SELECT COUNT(*) FROM commentaire c WHERE c.lecteur_id = l.id) AS nb_commentaires
            FROM lecteur l"
          . (count($where) ? ' WHERE '.implode(' AND ',$where) : '')
          . " ORDER BY l.id DESC LIMIT :limit OFFSET :offset";
    return executeSelect($sql, $params);
}

function updateStatutLecteur(int $id, string $statut): void {
    executeUpdate("UPDATE lecteur SET statut = :statut WHERE id = :id",
        [':statut' => $statut, ':id' => $id]);
}

function deleteLecteurAdmin(int $id): void {
    executeUpdate("DELETE FROM signalement WHERE lecteur_id = :id", [':id'=>$id]);
    executeUpdate("DELETE FROM commentaire  WHERE lecteur_id = :id", [':id'=>$id]);
    executeUpdate("DELETE FROM lecteur      WHERE id = :id",         [':id'=>$id]);
}

/* ════════ SIGNALEMENTS ════════ */

function countAllSignalements(string $statut = '', string $search = ''): int {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "libelle ILIKE :s"; $params[':s'] = '%'.$search.'%'; }
    $sql = "SELECT COUNT(*) AS total FROM signalement"
         . (count($where) ? ' WHERE '.implode(' AND ',$where) : '');
    return (int)(executeSelect($sql, $params, true)['total'] ?? 0);
}

function getAllSignalements(string $statut = '', string $search = '', int $page = 1, int $perPage = 10): array {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "s.statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "s.libelle ILIKE :sr"; $params[':sr'] = '%'.$search.'%'; }
    $params[':limit']  = $perPage;
    $params[':offset'] = ($page - 1) * $perPage;
    $sql = "SELECT s.id, s.libelle, s.description, s.statut, s.date_creation,
                   s.article_id, s.commentaire_id,
                   COALESCE(au.prenom||' '||au.nom, le.prenom||' '||le.nom,'Inconnu') AS signaleur,
                   ar.libelle AS article_libelle
            FROM signalement s
            LEFT JOIN auteur  au ON au.id = s.auteur_id
            LEFT JOIN lecteur le ON le.id = s.lecteur_id
            LEFT JOIN article ar ON ar.id = s.article_id
                                 OR ar.id = (SELECT article_id FROM commentaire WHERE id = s.commentaire_id LIMIT 1)"
          . (count($where) ? ' WHERE '.implode(' AND ',$where) : '')
          . " ORDER BY s.date_creation DESC LIMIT :limit OFFSET :offset";
    return executeSelect($sql, $params);
}

function updateStatutSignalement(int $id, string $statut): void {
    executeUpdate("UPDATE signalement SET statut = :statut WHERE id = :id",
        [':statut' => $statut, ':id' => $id]);
}

function deleteSignalement(int $id): void {
    executeUpdate("DELETE FROM signalement WHERE id = :id", [':id'=>$id]);
}

function getNbSignalementsNonTraites(): int {
    $res = executeSelect("SELECT COUNT(*) AS total FROM signalement WHERE statut = 'Non traiter'", [], true);
    return (int)($res['total'] ?? 0);
}