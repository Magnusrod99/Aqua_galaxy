<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$t0 = microtime(true);
$probe = @fsockopen("127.0.0.1", 3306, $err, $str, 0.5);
echo "fsockopen to 127.0.0.1:3306: " . ($probe ? "OPEN" : "CLOSED ($err: $str)") . " in " . round((microtime(true) - $t0) * 1000, 2) . "ms\n";
if ($probe) fclose($probe);

$t1 = microtime(true);
$probe2 = @fsockopen("localhost", 3306, $err2, $str2, 0.5);
echo "fsockopen to localhost:3306: " . ($probe2 ? "OPEN" : "CLOSED ($err2: $str2)") . " in " . round((microtime(true) - $t1) * 1000, 2) . "ms\n";
if ($probe2) fclose($probe2);
