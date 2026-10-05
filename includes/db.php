<?php
// db.php
/*$host = 'localhost';
$dbname = 'gvnm3099_appcalc';      // ⚠️ à adapter
$username = 'gvnm3099_calculator';       // ⚠️ à adapter
$password = '!_trululu@94_!';           // ⚠️ à adapter

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // active les exceptions
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch associatif par défaut
            PDO::ATTR_EMULATE_PREPARES => false, // meilleure sécurité sur les requêtes préparées
        ]
    );
} catch (PDOException $e) {
    // Message propre pour éviter d'afficher des infos sensibles
    die("❌ Erreur de connexion à la base de données : " . $e->getMessage());
}*/


$host = 'localhost';
$db   = 'faqu6267_indexpilot';
$user = 'faqu6267_Al6994';
$pass = '68%Um;8xF4prBzTy';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
