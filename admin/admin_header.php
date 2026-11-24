<?php
// admin_header.php – Authenticates admin and starts session
// Include this FIRST at the top of every admin page (before any output)
session_start();
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}

// Generate CSRF token once per session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$_adminName  = htmlspecialchars($_SESSION['admin_name']  ?? 'Admin', ENT_QUOTES, 'UTF-8');
$_adminEmail = htmlspecialchars($_SESSION['admin_email'] ?? '',       ENT_QUOTES, 'UTF-8');
