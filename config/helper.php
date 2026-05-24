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

function dd($test)
{
    echo "<pre>";
    var_dump($test);
    echo "</pre>";
    die("Yallah pitié");
}

function loadView(string $view,array $datas=[],string $layout="base") {
    ob_start();
    extract($datas);
    require_once(ROOT."view/".$view.".php");
    $content=ob_get_clean();
    require_once ROOT."/view/layout/$layout.layout.php";

}

/**
 * Génère une URL vers un contrôleur/action avec des paramètres
 * 
 * @param string $controller  Nom du contrôleur (ex: "lecteur")
 * @param string $action      Nom de l'action (ex: "show")
 * @param array $params       Paramètres supplémentaires (ex: ["id" => 5, "page" => 2])
 * @return string             L'URL complète
 */
function path(string $controller, string $action, array $params = []): string {
    // On commence par l'URL de base avec le contrôleur et l'action
    $url = WEBROOT . "?controller=" . urlencode($controller) . 
        "&action=" . urlencode($action);
    
    // On ajoute chaque paramètre supplémentaire
    foreach ($params as $key => $value) {
        $url .= "&" . urlencode($key) . "=" . urlencode($value);
    }
    
    return $url;
}

function countTable(string $table){
    $sql="SELECT COUNT(*) as total FROM $table";
   return executeSelect($sql,[],true)["total"];
}