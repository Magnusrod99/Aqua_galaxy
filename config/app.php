<?php

if (!defined('BASE_URL')) {
    $docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
    $projectRoot = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/');

    if ($docRoot !== '' && strpos($projectRoot, $docRoot) === 0) {
        $base = substr($projectRoot, strlen($docRoot));
        define('BASE_URL', $base === '' ? '' : $base);
    } else {
        define('BASE_URL', '/Aqua_galaxy');
    }
}

// Prevent Windows Apache double-slash UNC network lag
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '//') !== false) {
    $cleanUri = preg_replace('#/+#', '/', $_SERVER['REQUEST_URI']);
    header("Location: " . $cleanUri, true, 301);
    exit;
}