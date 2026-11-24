<?php

if (!defined('BASE_URL')) {
    define('BASE_URL', '/Aqua_galaxy');
}

// Prevent Windows Apache double-slash UNC network lag
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '//') !== false) {
    $cleanUri = preg_replace('#/+#', '/', $_SERVER['REQUEST_URI']);
    header("Location: " . $cleanUri, true, 301);
    exit;
}
