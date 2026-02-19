<?php
require __DIR__ . '/jwt.php';
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: secure_login');
    exit;
}

$action = $_POST['action'] ?? 'login';
$user   = trim($_POST['user'] ?? '');
$pass   = $_POST['pass'] ?? '';

if ($user === '' || $pass === '') {
    header('Location: secure_login?e=Missing%20fields');
    exit;
}

try {
    $pdo = db();

    if ($action === 'register') {
        // vérifier si user existe déjà
        $stmt = $pdo->prepare("SELECT 1 FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $user]);
        if ($stmt->fetchColumn()) {
            header('Location: secure_login?e=User%20already%20exists');
            exit;
        }

        // créer user (hashé)
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("
            INSERT INTO users(username, password_hash, role, is_dev)
            VALUES(:u, :p, 'user', 0)
        ");
        $stmt->execute([':u' => $user, ':p' => $hash]);

        // auto-login après inscription (optionnel)
        $row = [
            'username' => $user,
            'role' => 'user',
            'is_dev' => 0
        ];
    } else {
        // LOGIN
        $stmt = $pdo->prepare("SELECT username, password_hash, role, is_dev FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $user]);
        $row = $stmt->fetch();

        if (!$row || !password_verify($pass, $row['password_hash'])) {
            header('Location: secure_login?e=Invalid%20credentials');
            exit;
        }
    }

    // JWT
    $payload = [
        'sub'    => $row['username'],
        'role'   => $row['role'],
        'is_dev' => ((int)$row['is_dev'] === 1),
    ];

    $jwt = jwt_sign($payload);
    set_auth_cookie($jwt);

    header('Location: dashboard');
    exit;

} catch (Throwable $e) {
    header('Location: secure_login?e=DB%20error');
    exit;
}
