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
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

/**
 * Retourne le SVG correspondant au slug icone d'une catégorie.
 */
function getIconeCategorie(string $icone, int $size = 22): string {
    $s = $size;
    return match($icone) {
        'tech'          => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><rect x=\"2\" y=\"3\" width=\"20\" height=\"14\" rx=\"2\"/><line x1=\"8\" y1=\"21\" x2=\"16\" y2=\"21\"/><line x1=\"12\" y1=\"17\" x2=\"12\" y2=\"21\"/></svg>",
        'politique'     => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><path d=\"M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z\"/><polyline points=\"9 22 9 12 15 12 15 22\"/></svg>",
        'sport'         => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><circle cx=\"12\" cy=\"12\" r=\"10\"/><path d=\"M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z\"/><path d=\"M2 12h20\"/></svg>",
        'culture'       => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><path d=\"M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77\"/></svg>",
        'sante'         => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><path d=\"M22 12h-4l-3 9L9 3l-3 9H2\"/></svg>",
        'economie'      => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><line x1=\"12\" y1=\"1\" x2=\"12\" y2=\"23\"/><path d=\"M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6\"/></svg>",
        'education'     => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><path d=\"M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z\"/><path d=\"M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z\"/></svg>",
        'environnement' => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><path d=\"M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10z\"/><path d=\"M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12\"/></svg>",
        'societe'       => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><path d=\"M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2\"/><circle cx=\"9\" cy=\"7\" r=\"4\"/><path d=\"M23 21v-2a4 4 0 0 0-3-3.87\"/><path d=\"M16 3.13a4 4 0 0 1 0 7.75\"/></svg>",
        'science'       => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><path d=\"M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2v-4M9 21H5a2 2 0 0 1-2-2v-4m0 0h18\"/></svg>",
        default         => "<svg width=\"$s\" height=\"$s\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"white\" stroke-width=\"2\"><circle cx=\"12\" cy=\"12\" r=\"10\"/></svg>",
    };
}

/**
 * Retourne la couleur CSS correspondant au slug icone d'une catégorie.
 */
function getCouleurCategorie(string $icone): string {
    return match($icone) {
        'tech'          => 'linear-gradient(135deg,#6366f1,#3b82f6)',
        'politique'     => '#dc2626',
        'sport'         => '#7c3aed',
        'culture'       => 'linear-gradient(135deg,#f59e0b,#ea580c)',
        'sante'         => '#16a34a',
        'economie'      => 'linear-gradient(135deg,#0ea5e9,#0369a1)',
        'education'     => 'linear-gradient(135deg,#8b5cf6,#6d28d9)',
        'environnement' => '#15803d',
        'societe'       => 'linear-gradient(135deg,#ec4899,#be185d)',
        'science'       => 'linear-gradient(135deg,#f97316,#c2410c)',
        default         => 'var(--green)',
    };
}