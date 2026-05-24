<?php

require_once ROOT . "config/config.php";

/**
 * Exécute une requête SELECT et retourne les résultats.
 *
 * @param string $sql   Requête SQL (marqueurs ? ou :nom)
 * @param array  $data  Valeurs à injecter
 * @param bool   $one   false = tous les résultats, true = un seul
 */
function executeSelect(string $sql, array $data = [], bool $one = false) {
    $conn      = getConnexion();
    $statement = $conn->prepare($sql);
    count($data) === 0 ? $statement->execute() : $statement->execute($data);
    return $one ? $statement->fetch() : $statement->fetchAll();
}

/**
 * Exécute une requête de modification (INSERT, UPDATE, DELETE).
 *
 * @param string $sql   Requête SQL
 * @param array  $data  Valeurs à injecter
 */
function executeUpdate(string $sql, array $data): void {
    $conn      = getConnexion();
    $statement = $conn->prepare($sql);
    $statement->execute($data);
}

/**
 * Debug rapide — dump & die.
 */
function dd($test): void {
    echo "<pre>";
    var_dump($test);
    echo "</pre>";
    die("dd()");
}

/**
 * Charge une vue dans un layout.
 *
 * @param string $view    Chemin relatif à view/ (sans .php)
 * @param array  $datas   Variables à extraire dans la vue
 * @param string $layout  Nom du layout (dans view/layout/)
 */
function loadView(string $view, array $datas = [], string $layout = "base"): void {
    ob_start();
    extract($datas);
    require_once ROOT . "view/" . $view . ".php";
    $content = ob_get_clean();

    // L'action courante est transmise au layout pour gérer le menu actif
    $currentAction = $GLOBALS['currentAction'] ?? '';

    require_once ROOT . "view/layout/$layout.layout.php";
}

/**
 * Génère une URL vers un contrôleur/action avec des paramètres.
 *
 * @param string $controller  Nom du contrôleur
 * @param string $action      Nom de l'action
 * @param array  $params      Paramètres supplémentaires
 */
function path(string $controller, string $action, array $params = []): string {
    $url = WEBROOT . "?controller=" . urlencode($controller)
         . "&action=" . urlencode($action);

    foreach ($params as $key => $value) {
        if ($value !== '' && $value !== null) {
            $url .= "&" . urlencode($key) . "=" . urlencode($value);
        }
    }

    return $url;
}

/**
 * Compte le nombre de lignes dans une table.
 */
function countTable(string $table): int {
    $sql = "SELECT COUNT(*) AS total FROM $table";
    return (int) executeSelect($sql, [], true)["total"];
}