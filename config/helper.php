<?php

/**
 * Exécute une requête SELECT et retourne les résultats
 *
 * @param string $sql   La requête SQL (avec des ? ou :nom comme marqueurs)
 * @param array  $data  Les valeurs à injecter dans les marqueurs
 * @param bool   $one   false = tous les résultats, true = un seul résultat
 * @return array|false
 */
function executeSelect(string $sql, array $data = [], bool $one = false) {
    $conn      = getConnexion();
    $statement = $conn->prepare($sql);
    count($data) === 0 ? $statement->execute() : $statement->execute($data);
    return $one ? $statement->fetch() : $statement->fetchAll();
}

/**
 * Exécute une requête de modification (INSERT, UPDATE, DELETE)
 *
 * @param string $sql   La requête SQL
 * @param array  $data  Les valeurs à injecter
 * @return void
 */
function executeUpdate(string $sql, array $data): void {
    $conn      = getConnexion();
    $statement = $conn->prepare($sql);
    $statement->execute($data);
}

function dd($test): void {
    echo "<pre>";
    var_dump($test);
    echo "</pre>";
    die("Yallah pitié");
}

function loadView(string $view, array $datas = [], string $layout = "base"): void {
    ob_start();
    extract($datas);
    require_once ROOT . "view/" . $view . ".php";
    $content = ob_get_clean();
    require_once ROOT . "/view/layout/$layout.layout.php";
}

/**
 * Génère une URL vers un contrôleur/action avec des paramètres
 *
 * @param string $controller  Nom du contrôleur
 * @param string $action      Nom de l'action
 * @param array  $params      Paramètres supplémentaires
 * @return string
 */
function path(string $controller, string $action, array $params = []): string {
    $url = WEBROOT . "?controller=" . urlencode($controller) .
           "&action=" . urlencode($action);
    foreach ($params as $key => $value) {
        $url .= "&" . urlencode($key) . "=" . urlencode($value);
    }
    return $url;
}

function countTable(string $table): int {
    $sql = "SELECT COUNT(*) as total FROM $table";
    return (int) executeSelect($sql, [], true)["total"];
}