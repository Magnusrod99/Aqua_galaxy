<?php
require_once __DIR__ . "/app.php";

$host = "127.0.0.1";
$port = 3306;
$dbname = "aqua_galaxy";
$username = "root";
$password = "";

$pdo = null;

// Probe MySQL port with a 0.15s timeout so pages load INSTANTLY without buffering if MySQL is offline
$probe = @fsockopen($host, $port, $errno, $errstr, 0.15);

if ($probe) {
    fclose($probe);
    try {
        $pdo = new PDO(
            "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 1,
            ]
        );
    } catch (Throwable $e) {
        $pdo = null;
        error_log("Database connection failed: " . $e->getMessage());
    }
}
