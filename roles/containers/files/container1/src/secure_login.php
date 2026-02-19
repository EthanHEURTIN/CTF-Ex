<?php
$error = $_GET['e'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dollex Console - Auth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #121212; color: #00ff00; font-family: 'Courier New', monospace; }
        .card { background: #1e1e1e; border: 1px solid #00ff00; color: white; }
        .btn-dev { background: #00ff00; color: black; font-weight: bold; border-radius: 0; }
        .btn-dev:hover { background: #00cc00; }
        input { background: #474747 !important; border: 1px solid #444 !important; color: #00ff00 !important; }
        #registerInput { color: #00a2ff !important; }
    </style>
</head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <h2><a href="https://dollex.io" style="all: unset; cursor: pointer;" target="_blank">// INTERNAL_DOLLEX_INFRA_LOGIN_V2</a></h2>
                <p class="text-warning">WARNING: UNAUTHORIZED ACCESS IS LOGGED</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card p-4">
                        <h4>Sign In</h4>
                        <form action="connection" method="POST">
                            <input type="hidden" name="action" value="login">
                            <input type="text" name="user" class="form-control mb-3" placeholder="Username" required>
                            <input type="password" name="pass" class="form-control mb-3" placeholder="Password" required>
                            <button type="submit" class="btn btn-dev w-100">AUTH_REQUEST</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-4 border-info">
                        <h4 class="text-info">Register</h4>
                        <form action="connection" method="POST">
                            <input type="hidden" name="action" value="register">
                            <input id="registerInput" name="user" type="text" class="form-control mb-2" placeholder="New User" required>
                            <input id="registerInput" name="pass" type="password" class="form-control mb-2" placeholder="Password" required>
                            <button type="submit" class="btn btn-outline-info w-100">REGISTER_REQUEST</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>