<?php

/* ════════ AUTH ════════ */

function loginAdmin(string $email, string $mdp): array|false {
    $sql = "SELECT id, prenom, nom, email, mot_de_passe
            FROM admin WHERE email = :email";
    $admin = executeSelect($sql, [':email' => $email], true);
    if (!$admin) return false;
    // Comparaison directe
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
    $where = ["a.statut != 'Inactif'"]; $params = [];
    if ($statut !== '') { $where[] = "a.statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "a.libelle ILIKE :s"; $params[':s'] = '%'.$search.'%'; }
    $sql = "SELECT COUNT(*) AS total FROM article a WHERE ".implode(' AND ',$where);
    return (int)(executeSelect($sql, $params, true)['total'] ?? 0);
}

function getAllArticles(string $statut = '', string $search = '', int $page = 1, int $perPage = 10): array {
    $where = ["a.statut != 'Inactif'"]; $params = [];
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
          . " WHERE ".implode(' AND ',$where)
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

function countAllSignalements(string $statut = '', string $search = '', string $type = ''): int {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "s.statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "s.libelle ILIKE :s"; $params[':s'] = '%'.$search.'%'; }
    if ($type === 'article')     { $where[] = "s.commentaire_id IS NULL AND s.article_id IS NOT NULL"; }
    if ($type === 'commentaire') { $where[] = "s.commentaire_id IS NOT NULL"; }
    $sql = "SELECT COUNT(*) AS total FROM signalement s"
         . (count($where) ? ' WHERE '.implode(' AND ',$where) : '');
    return (int)(executeSelect($sql, $params, true)['total'] ?? 0);
}

function getAllSignalements(string $statut = '', string $search = '', int $page = 1, int $perPage = 10, string $type = ''): array {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "s.statut = :statut"; $params[':statut'] = $statut; }
    if ($search !== '') { $where[] = "s.libelle ILIKE :sr"; $params[':sr'] = '%'.$search.'%'; }
    if ($type === 'article')     { $where[] = "s.commentaire_id IS NULL AND s.article_id IS NOT NULL"; }
    if ($type === 'commentaire') { $where[] = "s.commentaire_id IS NOT NULL"; }
    $params[':limit']  = $perPage;
    $params[':offset'] = ($page - 1) * $perPage;
    $sql = "SELECT s.id, s.libelle, s.description, s.statut, s.date_creation,
               s.article_id, s.commentaire_id,
               COALESCE(au.prenom||' '||au.nom, le.prenom||' '||le.nom,'Inconnu') AS signaleur,
               ar.libelle AS article_libelle,
               com.contenue AS commentaire_contenu
        FROM signalement s
        LEFT JOIN auteur  au  ON au.id  = s.auteur_id
        LEFT JOIN lecteur le  ON le.id  = s.lecteur_id
        LEFT JOIN article ar  ON ar.id  = s.article_id
        LEFT JOIN commentaire com ON com.id = s.commentaire_id
        LEFT JOIN article ar2 ON ar2.id = com.article_id"
          . (count($where) ? ' WHERE '.implode(' AND ',$where) : '')
          . " ORDER BY s.date_creation DESC LIMIT :limit OFFSET :offset";
    return executeSelect($sql, $params);
}

// Invalider un article + marquer le signalement comme traité
function invaliderArticleEtTraiter(int $signalementId, int $articleId): void {
    executeUpdate("UPDATE article     SET statut = 'Invalide' WHERE id = :id",   [':id' => $articleId]);
    executeUpdate("UPDATE signalement SET statut = 'Traiter'  WHERE id = :id",   [':id' => $signalementId]);
}

// Supprimer commentaire + marquer le signalement comme traité
function supprimerCommentaireEtTraiter(int $signalementId, int $commentaireId): void {
    deleteCommentaireAdmin($commentaireId); // supprime aussi les signalements liés à ce commentaire
    // Le signalement lui-même a été supprimé en cascade, donc on ne fait rien de plus
    // Mais si tu n'as pas de CASCADE en base, on le supprime manuellement :
    executeUpdate("DELETE FROM signalement WHERE id = :id", [':id' => $signalementId]);
}

function updateStatutSignalement(int $id, string $statut): void {
    executeUpdate("UPDATE signalement SET statut = :statut WHERE id = :id",
        [':statut' => $statut, ':id' => $id]);
}

function deleteSignalement(int $id): void {
    executeUpdate("DELETE FROM signalement WHERE id = :id", [':id' => $id]);
}

function getNbSignalementsNonTraites(): int {
    $res = executeSelect("SELECT COUNT(*) AS total FROM signalement WHERE statut = 'Non traiter'", [], true);
    return (int)($res['total'] ?? 0);
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


/**
 * Compte les demandes auteur en attente.
 */
function getNbDemandesAuteurEnAttente(): int {
    $res = executeSelect("SELECT COUNT(*) AS total FROM demande_auteur WHERE statut = 'En attente'", [], true);
    return (int)($res['total'] ?? 0);
}

/**
 * Liste les demandes auteur (avec infos du lecteur).
 */
function getAllDemandesAuteur(string $statut = '', int $page = 1, int $perPage = 10): array {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "d.statut = :statut"; $params[':statut'] = $statut; }
    $params[':limit']  = $perPage;
    $params[':offset'] = ($page - 1) * $perPage;
    $sql = "SELECT d.id, d.message, d.statut, d.date_demande,
                   l.id AS lecteur_id, l.nom, l.prenom, l.email, l.photo
            FROM demande_auteur d
            JOIN lecteur l ON l.id = d.lecteur_id"
          . (count($where) ? ' WHERE '.implode(' AND ',$where) : '')
          . " ORDER BY d.date_demande DESC LIMIT :limit OFFSET :offset";
    return executeSelect($sql, $params);
}

function countAllDemandesAuteur(string $statut = ''): int {
    $where = []; $params = [];
    if ($statut !== '') { $where[] = "statut = :statut"; $params[':statut'] = $statut; }
    $sql = "SELECT COUNT(*) AS total FROM demande_auteur"
         . (count($where) ? ' WHERE '.implode(' AND ',$where) : '');
    return (int)(executeSelect($sql, $params, true)['total'] ?? 0);
}

/**
 * Accepte une demande : transforme le lecteur en auteur.
 */
function accepterDemandeAuteur(int $demandeId, int $lecteurId): void {
    // Récupère les infos du lecteur
    $lecteur = executeSelect("SELECT * FROM lecteur WHERE id = :id", [':id' => $lecteurId], true);
    if (!$lecteur) return;

    // Insère dans auteur
    $sql = "INSERT INTO auteur (nom, prenom, email, mot_de_passe, date_inscription, statut, admin, bio, photo)
            VALUES (:nom, :prenom, :email, :mdp, CURRENT_DATE, 'Actif', 1, :bio, :photo)
            RETURNING id";
    $res = executeSelect($sql, [
        ':nom'    => $lecteur['nom'],
        ':prenom' => $lecteur['prenom'],
        ':email'  => $lecteur['email'],
        ':mdp'    => $lecteur['mot_de_passe'],
        ':bio'    => '', // sera mis à jour ensuite avec le message de la demande
        ':photo'  => $lecteur['photo'],
    ], true);
    $nouvelAuteurId = $res['id'] ?? null;
    if (!$nouvelAuteurId) return;

    // Récupère le message de la demande pour pré-remplir la bio
    $demande = executeSelect("SELECT message FROM demande_auteur WHERE id = :id", [':id' => $demandeId], true);
    if ($demande && !empty($demande['message'])) {
        executeUpdate("UPDATE auteur SET bio = :bio WHERE id = :id",
            [':bio' => $demande['message'], ':id' => $nouvelAuteurId]);
    }

    // Migration des commentaires
    executeUpdate(
        "UPDATE commentaire SET auteur_id = :auteur_id, lecteur_id = NULL WHERE lecteur_id = :lecteur_id",
        [':auteur_id' => $nouvelAuteurId, ':lecteur_id' => $lecteurId]
    );

    // Migration des signalements
    executeUpdate(
        "UPDATE signalement SET auteur_id = :auteur_id, lecteur_id = NULL WHERE lecteur_id = :lecteur_id",
        [':auteur_id' => $nouvelAuteurId, ':lecteur_id' => $lecteurId]
    );

    // Supprime le lecteur
    executeUpdate("DELETE FROM lecteur WHERE id = :id", [':id' => $lecteurId]);

    // Marque la demande comme acceptée
    executeUpdate("UPDATE demande_auteur SET statut = 'Acceptee' WHERE id = :id", [':id' => $demandeId]);
}

/**
 * Refuse une demande.
 */
function refuserDemandeAuteur(int $demandeId): void {
    executeUpdate("UPDATE demande_auteur SET statut = 'Refusee' WHERE id = :id", [':id' => $demandeId]);
}