<?php
require __DIR__ . '/jwt.php';

$token = get_auth_token();
if (!$token) {
    header('Location: secure_login?e=No%20token');
    exit;
}

try {
    $claims = jwt_verify($token);
} catch (Exception $e) {
    clear_auth_cookie();
    header('Location: secure_login?e=' . urlencode($e->getMessage()));
    exit;
}

$isDev = !empty($claims['is_dev']);
if(!$isDev){
    header('Location: dashboard');
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Internal Dashboard - Dollex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #121212; color: #00ff00; font-family: 'Courier New', monospace; }
        .navbar { background: #1e1e1e !important; border-bottom: 1px solid #00ff00; }
        .nav-link { color: #00ff00 !important; margin-right: 15px; }
        .nav-link:hover { text-decoration: underline; color: #fff !important; }
        .card { background: #1e1e1e; border: 1px solid #00ff00; color: #00ff00; }
        .btn-request { border: 1px solid #00ff00; color: #00ff00; font-weight: bold; }
        .btn-request:hover { background: #00ff00; color: #000; }
        .scanline { width: 100%; height: 2px; background: rgba(0, 255, 0, 0.1); margin: 10px 0; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-success" href="#">// DOLLEX_INFRA </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard">DASHBOARD</a>
                <a class="nav-link" href="request">REQUEST</a>
                <?php if ($isDev): ?>
                    <a class="nav-link" href="logs">LOGS</a>
                    <a class="nav-link btn btn-request px-3 active" href="admin_tickets">TICKETS</a>
                <?php endif; ?>
                <a class="nav-link text-danger ms-3" href="logout">LOGOUT</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-danger mb-4">USERS_TICKETS</h2>
        
        <?php
        $file = 'data/tickets_db.json';
        if (file_exists($file)) {
            $tickets = json_decode(file_get_contents($file), true);
            foreach (array_reverse($tickets) as $ticket) {
                echo '<div class="card p-3">';
                echo '<strong>Sujet :</strong> ' . htmlspecialchars($ticket['subject']) . '<br>';
                echo '<strong>Priorité :</strong> ' . $ticket['priority'] . '<br>';
                echo '<hr border-color="#ff0000">';
                echo '<strong>Message :</strong> ' . $ticket['content']; 
                echo '</div>';
            }
        } else {
            echo "<p>Aucun ticket en attente.</p>";
        }
        ?>
    </div>

</body>
</html>