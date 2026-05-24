<?php
try {
    $host = 'localhost';
    $port = '5432';
    $dbname = 'horizonblog';
    $user = 'postgres';
    $password = 'seydinathiam05';
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "Connexion réussie !";
    
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>