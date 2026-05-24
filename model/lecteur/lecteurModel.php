<?php
function getArticleWeek(){
    $sql = "SELECT *FROM article LIMIT 5";
    return executeSelect($sql);
}

function getPrincipalCategorie(){
    $sql = "SELECT *FROM categorie LIMIT 4";
    return executeSelect($sql);
}