<?php
function getArticleVisuel(){
    $sql = "SELECT 
        a.id,
        a.libelle,
        a.description,
        a.statut,
        ai.url AS image_p,
        au.prenom || ' ' || au.nom AS auteur,
        STRING_AGG(c.libelle, ', ') AS categories
    FROM article a
    LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
    LEFT JOIN auteur au ON au.id = a.auteur_id
    LEFT JOIN article_categorie ac ON ac.article_id = a.id
    LEFT JOIN categorie c ON c.id = ac.categorie_id
    WHERE a.statut = 'Actif'
    GROUP BY a.id, a.libelle, a.description, a.statut, ai.url, au.prenom, au.nom
    ORDER BY a.date_creation DESC
    LIMIT 5";
    return executeSelect($sql);
}

function getPrincipalCategorie(){
    $sql = "SELECT *FROM categorie LIMIT 4";
    return executeSelect($sql);
}
/*
tous les articles avec leurs images

SELECT 
    a.id,
    a.libelle,
    a.description,
    a.statut,
    ai.url,
    ai.legende,
    ai.ordre
FROM article a
LEFT JOIN article_image ai ON ai.article_id = a.id
ORDER BY a.id, ai.ordre;

Tous les articles avec uniquement leur image principale
SELECT 
    a.id,
    a.libelle,
    a.description,
    a.statut,
    ai.url AS image_principale
FROM article a
LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
ORDER BY a.id;


Un seul article avec toutes ses images
SELECT 
    a.id,
    a.libelle,
    a.description,
    a.statut,
    ai.url,
    ai.legende,
    ai.ordre
FROM article a
LEFT JOIN article_image ai ON ai.article_id = a.id
WHERE a.id = 1
ORDER BY ai.ordre;


Articles avec leur auteur + image principale 
SELECT 
    a.id,
    a.libelle,
    a.statut,
    au.prenom || ' ' || au.nom AS auteur,
    ai.url AS image_principale
FROM article a
LEFT JOIN auteur au       ON au.id = a.auteur_id
LEFT JOIN article_image ai ON ai.article_id = a.id AND ai.ordre = 1
ORDER BY a.date_creation DESC;
*/