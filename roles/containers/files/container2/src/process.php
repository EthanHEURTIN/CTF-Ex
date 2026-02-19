<?php
declare(strict_types=1);

session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email    = trim($_POST['user_email'] ?? '');
$password = $_POST['user_password'] ?? '';

if ($email === '' || $password === '') {
    header('Location: login.php?fail=1');
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT id, email, password_hash 
        FROM dogs 
        WHERE email = :email
        LIMIT 1
    ");

    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['dog_id'] = $user['id'];
        $_SESSION['dog_email'] = $user['email'];

        session_regenerate_id(true);

        header('Location: welcome.php');
        exit;
    }

    header('Location: login.php?fail=1');
    exit;

} catch (PDOException $e) {
    header('Location: login.php?fail=1');
    exit;
}