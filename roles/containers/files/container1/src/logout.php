<?php
ob_start(); 

require_once __DIR__ . '/jwt.php';

clear_auth_cookie();

ob_end_flush();

header('Location: secure_login');
exit;