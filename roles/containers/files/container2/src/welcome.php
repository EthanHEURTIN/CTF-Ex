<?php
session_start();

if (!isset($_SESSION['dog_id']) || empty($_SESSION['dog_id'])) {
    header("Location: login.php");
    exit;
}

$upload_dir = 'uploads/';
$upload_message = '';
$upload_success = false;

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dog_image'])) {
    $file = $_FILES['dog_image'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png'];

    if (!in_array($ext, $allowed)) {
        $upload_message = "Only JPG, JPEG, PNG files are allowed.";
    } elseif ($file['size'] > 5 * 1024 * 1024) {
        $upload_message = "File too large (max 5MB).";
    } else {
        $new_name = 'dog_' . time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
        $destination = $upload_dir . $new_name;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $upload_message = "Image uploaded: <strong>$new_name</strong>";
            $upload_success = true;
        } else {
            $upload_message = "Upload failed.";
        }
    }
}

$official_images = [
    'breath_sniffa.png'       => 'Breath Sniffa',
    'cosmo_enjoyer.png'       => 'Cosmo Enjoyer',
    'pouik.png'               => 'Pouik',
    'this_is_my_son.png'      => 'This is my son',
];

$selected_file = $_POST['img'] ?? '';
$selected_file = explode('?', $selected_file)[0] ?? $selected_file;
$selected_file = trim($selected_file, '/');

$file_path = 'assets/' . $selected_file;

$message = '';
$content_display = '';

if (!empty($selected_file)) {
    $selected_file = trim($selected_file, '/ ');

    $blocked = false;
    $message = '';

    if (preg_match('/\.(php|inc|phtml|php3|php4|php5|php7|phps|php~)$/i', $selected_file)) {
        $message = "PHP files cannot be viewed directly here.";
        $blocked = true;
    }

    $file_path = 'assets/' . $selected_file;

    if ($blocked) {
        $content_display = "<div class=\"alert alert-warning\">" . htmlspecialchars($message) . "</div>";
    } elseif (file_exists($file_path) && is_readable($file_path)) {
        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        if (in_array($ext, ['png','jpg','jpeg','gif','webp'])) {
            $content_display = "<img src=\"/assets/" . htmlspecialchars($selected_file) . "\" class=\"gallery-img mb-3\" alt=\"Image\">";
        } else {
            $log_content = file_get_contents($file_path);
            $clean_log = str_replace('\\"', '"', $log_content);

            $tmp_file = tempnam(sys_get_temp_dir(), 'temp_');
            file_put_contents($tmp_file, $clean_log);

            ob_start();
            include $tmp_file;
            $content_display = ob_get_clean();

            unlink($tmp_file);
        }
    } else {
        $content_display = "<div class=\"alert alert-info\">File not found or not readable: <code>" . htmlspecialchars($selected_file) . "</code></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Doghouse - Your Kennel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Quicksand:wght@500&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                        url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Quicksand', sans-serif;
        }

        .doghouse-wrapper { position: relative; width: 100%; max-width: 720px; filter: drop-shadow(0 20px 35px rgba(0,0,0,0.5)); }
        .roof { width: 100%; height: 110px; background: #5d4037; clip-path: polygon(50% 0%, 0% 100%, 100% 100%); margin-bottom: -2px; border-bottom: 6px solid #4e342e; }
        .house-body { background: #8d6e63; background-image: repeating-linear-gradient(90deg, transparent, transparent 40px, rgba(0,0,0,0.05) 41px); padding: 30px 25px; border-radius: 0 0 22px 22px; border-bottom: 14px solid #5d4037; }
        .entrance-arch { background: #efebe9; border-radius: 140px 140px 22px 22px; padding: 30px 20px; box-shadow: inset 0 8px 12px rgba(0,0,0,0.2); border: 5px solid #d7ccc8; }
        h2 { font-family: 'Fredoka One', cursive; color: #5d4037; font-size: 2.4rem; }
        .btn-primary, .btn-danger { border: none; box-shadow: 0 4px 0 #bf360c; font-size: 1.1rem; padding: 12px; }
        .btn-primary { background: #d84315; }
        .btn-primary:hover { background: #e64a19; transform: translateY(-2px); }
        .btn-danger { background: #d84315; }
        .btn-danger:hover { background: #e64a19; transform: translateY(-2px); }
        .bone-icon { width: 55px; margin-bottom: 10px; }
        .gallery-img { max-width: 100%; max-height: 50vh; border-radius: 12px; border: 4px solid #d7ccc8; box-shadow: 0 6px 15px rgba(0,0,0,0.35); margin: 1.2rem 0; }
        select.form-select, input[type="file"] { border-radius: 15px; border: 2px solid #d7ccc8; padding: 10px 14px; font-size: 1rem; }
        .form-label { font-size: 1.1rem; }
        small.text-muted { font-size: 0.85rem; }
        pre { font-family: 'Courier New', monospace; background: #f8f9fa; border: 1px solid #d7ccc8; }
        code { background: #e9ecef; padding: 0.15rem 0.4rem; border-radius: 4px; }
        .form-select option:first-child {
            text-align: center;
        }
        .form-select {
            text-align: center;
            text-align-last: center;
        }
    </style>
</head>
<body>

<div class="doghouse-wrapper">
    <div class="roof"></div>
    <div class="house-body text-center">
        <div class="entrance-arch">

            <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" class="bone-icon" alt="Bone">
            <h2 class="mb-3">Your Private Kennel</h2>

            <?php if ($upload_message): ?>
                <div class="alert <?= $upload_success ? 'alert-success' : 'alert-danger' ?> mb-4 py-2 small">
                    <?= $upload_message ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data" class="mb-4">
                <div class="mb-3">
                    <label class="form-label fw-bold" style="color:#795548;">Upload your photo</label>
                    <input type="file" name="dog_image" accept="image/*" class="form-control" required>
                    <small class="text-muted d-block mt-1">Max 5MB – JPG, PNG</small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Upload</button>
            </form>

            <p class="mb-3 fw-bold small" style="color:#795548;">View an image or file:</p>

            <form action="" method="post" class="mb-4">
                <select name="img" class="form-select" onchange="this.form.submit()">
                    <option value="">--- Official images of the doghouse ---</option>
                    <?php foreach ($official_images as $filename => $title): ?>
                        <option value="<?= htmlspecialchars($filename) ?>"
                            <?= $selected_file === $filename ? 'selected' : '' ?>>
                            <?= htmlspecialchars($title) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php if (!empty($selected_file)): ?>
                <div class="mt-3">
                    <h6 style="color:#5d4037; word-break: break-all; font-size: 0.95rem;">
                        Requested: <code><?= htmlspecialchars($selected_file) ?></code>
                    </h6>

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-warning mt-3 py-2 small"><?= htmlspecialchars($message) ?></div>
                    <?php elseif (!empty($content_display)): ?>
                        <div class="text-center">
                            <?= $content_display ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary mt-3 py-2 small">
                            File empty or not readable.
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info mt-3 py-3 small">
                    Select an image from the list or try other paths in the URL...
                </div>
            <?php endif; ?>

            <div class="mt-4">
                <p class="mb-1 small">Logged in as:</p>
                <strong style="color:#5d4037; font-size: 1rem;"><?= htmlspecialchars($_SESSION['dog_email'] ?? 'Mystery Dog') ?></strong>
            </div>

            <a href="logout.php" class="btn btn-danger mt-4 w-75 mx-auto d-block">
                Leave the Kennel
            </a>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>