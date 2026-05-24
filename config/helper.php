<?php

/**
 * Exécute une requête SELECT et retourne les résultats
 * 
 * @param string $sql   La requête SQL (avec des ? ou :nom comme marqueurs)
 * @param array $data   Les valeurs à injecter dans les marqueurs (ex: [5, "dupont"])
 * @param bool $one     false = retourne tous les résultats, true = retourne un seul résultat
 * @return array|false  Tableau des résultats (ou false si rien trouvé)
 */
function executeSelect(string $sql,array $data=[],$one=false) {
        $result=null;
        $conn=openConnexion();
        $statement = $conn->prepare($sql);
      count($data)==0?$statement->execute():$statement->execute($data);
      $result=$one==true?$statement->fetch():$statement->fetchAll();
        closeConnexion($conn);
        return $result ;
  
}

/**
 * Exécute une requête de modification (INSERT, UPDATE, DELETE)
 * 
 * @param string $sql   La requête SQL (avec des ? ou :nom comme marqueurs)
 * @param array $data   Les valeurs à injecter dans les marqueurs (obligatoire)
 * @return void         Ne retourne rien (car c'est une modification)
 */
function executeUpdate(string $sql,array $data){
    $conn=openConnexion();
        $statement = $conn->prepare($sql);
        $statement->execute($data);
   closeConnexion($conn);
}