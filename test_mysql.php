<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$t0 = microtime(true);
$probe = @fsockopen("127.0.0.1", 3306, $err, $str, 0.5);
echo "fsockopen: " . ($probe ? "OPEN" : "CLOSED") . " in " . round((microtime(true) - $t0) * 1000, 2) . "ms<br>";
if ($probe) fclose($probe);

$t1 = microtime(true);
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=aqua_galaxy;charset=utf8mb4", "root", "", [
        PDO::ATTR_TIMEOUT => 2
    ]);
    echo "PDO connected to aqua_galaxy in " . round((microtime(true) - $t1) * 1000, 2) . "ms";
} catch (Throwable $e) {
    echo "PDO error in " . round((microtime(true) - $t1) * 1000, 2) . "ms: " . $e->getMessage();
}