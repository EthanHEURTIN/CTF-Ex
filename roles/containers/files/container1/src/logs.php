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
    exit;
}


$selectedDate = $_GET['date'] ?? '';
$logContent = "";

if ($selectedDate) {
    if(strpos($selectedDate, ' ') !== false){
        $logContent = "⚠️ SECURITY_ALERT: Hacker Detected ! Space characters blocked.";
    } else {

        $logContent = shell_exec("cat logs/" . $selectedDate . ".log");

        if ($logContent === null) {
            $logContent = "ERROR: Command execution failed (shell_exec returned null)";
        } elseif (trim($logContent) === '') {
            $logContent = "ERROR: Log file empty, not found or permission denied.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Internal Logs - Dollex</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #121212; color: #00ff00; font-family: 'Courier New', monospace; }
        .navbar { background: #1e1e1e !important; border-bottom: 1px solid #00ff00; }
        .nav-link { color: #00ff00 !important; margin-right: 15px; }
        .nav-link:hover { text-decoration: underline; color: #fff !important; }
        .card { background: #1e1e1e; border: 1px solid #00ff00; color: #00ff00; }
        .btn-request { border: 1px solid #00ff00; color: #00ff00; font-weight: bold; }
        .btn-request:hover { background: #00ff00; color: #000; }
        .log-viewer { background: #000; border: 1px dashed #00ff00; padding: 15px; color: #00ff00; min-height: 200px; white-space: pre-wrap; }
        select { background: #1e1e1e !important; color: #00ff00 !important; border: 1px solid #00ff00 !important; }
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
                    <a class="nav-link btn btn-request px-3 active" href="logs">LOGS</a>
                    <a class="nav-link" href="admin_tickets">TICKETS</a>
                <?php endif; ?>
                <a class="nav-link text-danger ms-3" href="logout">LOGOUT</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card p-4 shadow-lg">
            <h2 class="mb-4">SYSTEM_LOG_EXPLORER</h2>
            
            <form method="GET" class="mb-4">
                <label for="dateSelect" class="form-label">Select a log file :</label>
                <div class="input-group">
                    <select name="date" id="dateSelect" class="form-select">
                        <option value="">-- Choose a date --</option>
                        <option value="2023-11-04" <?php if($selectedDate == '2023-11-04') echo 'selected'; ?>>2023-11-04</option>
                        <option value="2024-03-12" <?php if($selectedDate == '2024-03-12') echo 'selected'; ?>>2024-03-12</option>
                        <option value="2024-12-30" <?php if($selectedDate == '2024-12-30') echo 'selected'; ?>>2024-12-30</option>
                        <option value="2025-02-06" <?php if($selectedDate == '2025-02-06') echo 'selected'; ?>>2025-02-06</option>
                        <option value="2025-07-19" <?php if($selectedDate == '2025-07-19') echo 'selected'; ?>>2025-07-19</option>
                        <option value="2026-01-14" <?php if($selectedDate == '2026-01-14') echo 'selected'; ?>>2026-01-14</option>
                    </select>
                    <button type="submit" class="btn btn-request">CHARGER</button>
                </div>
            </form>

            <div class="log-viewer">
                <?php if($selectedDate): ?>
                    <p class="text-secondary small">> Searching for the <code><?= htmlspecialchars($selectedDate) ?></code> log file</p>
                    <hr class="border-secondary">
                    <pre class="text-light m-0" style="white-space: pre-wrap;"><code><?= htmlspecialchars($logContent) ?></code></pre>
                <?php else: ?>
                    <span class="text-secondary">Waiting for selection...</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>