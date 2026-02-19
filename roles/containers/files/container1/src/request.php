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
        
        /* Styles spécifiques pour les entrées du formulaire */
        input, textarea, select { 
            background: #121212 !important; 
            border: 1px solid #00ff00 !important; 
            color: #00ff00 !important; 
            border-radius: 0 !important;
        }
        input:focus, textarea:focus, select:focus { 
            box-shadow: 0 0 10px #00ff00 !important; 
            outline: none; 
        }
        ::placeholder { color: rgba(0, 255, 0, 0.5) !important; }
        label { margin-bottom: 5px; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-success" href="#">// DOLLEX_INFRA </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard">DASHBOARD</a>
                <a class="nav-link btn btn-request px-3 active" href="request">REQUEST</a>
                <?php if ($isDev): ?>
                    <a class="nav-link" href="logs">LOGS</a>
                    <a class="nav-link" href="admin_tickets">TICKETS</a>
                <?php endif; ?>
                <a class="nav-link text-danger ms-3" href="logout">LOGOUT</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4 shadow-lg">
                    <h2 class="mb-4">OPEN_SUPPORT_TICKET [v39.9]</h2>
                    <div class="scanline"></div>
                    <p class="mb-4">Please fill out this ticket so that it can be forwarded to the lazy developer (odladsad).</p>
                    
                    <form action="submit_ticket" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Subject_Header</label>
                                <input type="text" name="subject" class="form-control" placeholder="Type the header..." required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Urgency_Level</label>
                                <select name="priority" class="form-select">
                                    <option value="low">0x01 - LOW (Rhobalas on Carry The Glass)</option>
                                    <option value="med">0x02 - MEDIUM (NORAFFLE)</option>
                                    <option value="high">0x03 - CRITICAL (Coulon Façades comes back!)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label>Data_Payload (Message)</label>
                            <textarea name="content" class="form-control" rows="6" placeholder="Describe your request..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-request p-3">EXECUTE_SUBMISSION</button>
                        </div>
                    </form>

                    <hr class="border-success mt-4">
                    <div class="text-center small">
                        <span class="text-secondary text-uppercase">Encrypted transmission via Node: 53a43fa26252</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>