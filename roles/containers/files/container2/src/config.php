<?php
// config.php
declare(strict_types=1);

define('DB_HOST', '127.0.0.1');
define('DB_PORT', 5432);
define('DB_NAME', 'doghouse');
define('DB_USER', 'dog');
define('DB_PASS', 'dogowner');

$pdo = null;

try {
    $dsn = sprintf(
        "pgsql:host=%s;port=%d;dbname=%s",
        DB_HOST,
        DB_PORT,
        DB_NAME
    );

    $pdo = new PDO(
        $dsn,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );


} catch (PDOException $e) {
    http_response_code(500);
    // À NE FAIRE QU'EN PHASE DE TEST (supprimer après)
    die("Erreur : " . $e->getMessage()); 
}
?>