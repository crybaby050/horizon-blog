<?php

/**
 * Vérifie si un email existe déjà dans la table lecteur ou auteur
 */
function emailExists(string $email): bool {
    $sqlLecteur = "SELECT COUNT(*) AS total FROM lecteur WHERE email = :email";
    $sqlAuteur  = "SELECT COUNT(*) AS total FROM auteur WHERE email = :email";
    
    $countLecteur = (int) executeSelect($sqlLecteur, [':email' => $email], true)['total'];
    $countAuteur  = (int) executeSelect($sqlAuteur,  [':email' => $email], true)['total'];
    
    return ($countLecteur + $countAuteur) > 0;
}

/**
 * Inscrit un nouveau lecteur
 */
function registerLecteur(string $nom, string $prenom, string $email, string $motDePasse): bool {
    $sql = "INSERT INTO lecteur (nom, prenom, email, mot_de_passe, statut, date_inscription, admin)
            VALUES (:nom, :prenom, :email, :mdp, 'Actif', NOW(), 0)";
    
    try {
        executeUpdate($sql, [
            ':nom'   => $nom,
            ':prenom'=> $prenom,
            ':email' => $email,
            ':mdp'   => $motDePasse
        ]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Connecte un lecteur par email/mot de passe
 */
function loginLecteur(string $email, string $motDePasse): array|false {
    $sql = "SELECT id, nom, prenom, email, statut
            FROM lecteur
            WHERE email = :email AND mot_de_passe = :mdp AND statut = 'Actif'";
    
    return executeSelect($sql, [':email' => $email, ':mdp' => $motDePasse], true);
}

/**
 * Connecte un auteur par email/mot de passe
 */
function loginAuteur(string $email, string $motDePasse): array|false {
    $sql = "SELECT id, nom, prenom, email, statut, bio
            FROM auteur
            WHERE email = :email AND mot_de_passe = :mdp AND statut = 'Actif'";
    
    return executeSelect($sql, [':email' => $email, ':mdp' => $motDePasse], true);
}

/**
 * Récupère les informations d'un lecteur par son ID
 */
function getLecteurById(int $id): array|false {
    $sql = "SELECT id, nom, prenom, email, statut, date_inscription
            FROM lecteur
            WHERE id = :id";
    return executeSelect($sql, [':id' => $id], true);
}

/**
 * Récupère les informations d'un auteur par son ID
 */
function getAuteurById(int $id): array|false {
    $sql = "SELECT id, nom, prenom, email, statut, bio
            FROM auteur
            WHERE id = :id";
    return executeSelect($sql, [':id' => $id], true);
}